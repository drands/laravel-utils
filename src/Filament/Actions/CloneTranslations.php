<?php

namespace Drands\LaravelUtils\Filament\Actions;

use Closure;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Actions\Contracts\HasActions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CloneTranslations extends Action
{
    use CanCustomizeProcess;

    protected ?Closure $mutateRecordDataUsing = null;

    private array $builderFields = [];  

    public static function getDefaultName(): ?string
    {
        return 'cloneTranslations';
    }

    protected function setUp(): void
    {
        parent::setUp();

        //$this->requiresConfirmation();

        $this->label(__('filament-actions::cloneTranslations.single.label'));

        $this->modalHeading(fn(): string => __('filament-actions::cloneTranslations.single.modal.heading', ['label' => $this->getRecordTitle()]));
        
        $this->modalDescription(__('filament-actions::cloneTranslations.single.modal.description'));

        $this->modalSubmitActionLabel(__('filament-actions::cloneTranslations.single.modal.actions.clone.label'));

        $this->successNotificationTitle(__('filament-actions::cloneTranslations.single.notifications.cloned.title'));

        $this->defaultColor('warning');

        $this->groupedIcon(FilamentIcon::resolve('actions::cloneTranslations-action.grouped') ?? 'heroicon-m-document-duplicate');

        $this->modalIcon(FilamentIcon::resolve('actions::cloneTranslations-action.modal') ?? 'heroicon-o-document-duplicate');

        //$this->keyBindings(['ctrl+d']);

        /*
        $this->hidden(static function (Model $record): bool {
            if (! method_exists($record, 'trashed')) {
                return false;
            }

            return $record->trashed();
        });*/

        $allLocales = array_map(fn($locale) => $locale['name'], LaravelLocalization::getSupportedLocales());

        $translatableFields = $this->record->translatable ?? [];

        $copyFromLocaleDefault = App::getLocale();

        $copyToLocalesDefault = collect($allLocales)
            ->filter(fn($locale, $key) => $key !== $copyFromLocaleDefault)
            ->keys()
            ->all();

        $this->fillForm([
            'fromLocale' => $copyFromLocaleDefault,
            'toLocales' => $copyToLocalesDefault,
            'fields' => $translatableFields,
        ]);

        $this->form([
            Forms\Components\Select::make('fromLocale')
                ->label(__('filament-actions::cloneTranslations.single.form.fromLocale.label'))
                ->options($allLocales)
                ->required(),
            Forms\Components\Select::make('toLocales')
                ->multiple()
                ->label(__('filament-actions::cloneTranslations.single.form.toLocales.label'))
                ->options($allLocales)
                ->required(),
            Forms\Components\Select::make('fields')
                ->multiple()
                ->label(__('filament-actions::cloneTranslations.single.form.fields.label'))
                ->options(array_combine($translatableFields, $translatableFields))
                ->required(),
        ]);

        $this->action(function (): void {

            $this->process(function (array $data, EditRecord $livewire, Model $record) {
                $isDirty = false;

                foreach ($data['fields'] as $field) {
                    $sourceContent = $record->getTranslation($field, $data['fromLocale'], false);
                    foreach ($data['toLocales'] as $locale) {
                        if (in_array($field, $this->builderFields)) {
                            $clonedContent = $this->builderClone($sourceContent);
                            $record->setTranslation($field, $locale, $clonedContent);
                        } else {
                            $record->setTranslation($field, $locale, $sourceContent);
                        }
                        $isDirty = true;
                    }
                }

                if ($isDirty) {
                    $record->save();
                    $livewire->refreshFormData($data['fields']);

                }
            });

            $this->success();
        });
    }

    public function builderFields($fields): static
    {
        $this->builderFields = $fields;

        return $this;
    }

    private function builderClone($sourceContent): array
    {
        $output = [];

        foreach ($sourceContent as &$block) {
            $output[] = $this->processBlock($block);
        }

        return $output;
    }

    private function processBlock(array $block): array
    {
        if ($this->isRow($block)) {
            foreach ($block['data']['row'] as &$rowField) {
                $rowField = $this->processBlock($rowField);
            }
            unset($rowField);
        } elseif ($this->isGroup($block)) {
            foreach ($block['data']['group'] as &$groupField) {
                $groupField = $this->processBlock($groupField);
            }
            unset($groupField);
        }

        if ($field = $this->isFile($block['data'])) {
            $block = $this->copyFile($block, $field);
        }

        return $block;
    }

    private function isRow(array $field): bool
    {
        return $field['type'] === 'Row';
    }

    private function isGroup(array $field): bool
    {
        return $field['type'] === 'Group';
    }
    
    private function isFile(array $field): bool|string
    {
        foreach($field as $key => $value) {
            if (is_string($value)
                && !str_starts_with($value, '//')
                && !str_starts_with($value, 'http')
                && preg_match('#.+/.+\..+#', $value) === 1
            ) {
                return $key;
            }
        }
        return false;
    }

    private function copyFile(array $block, $field): array
    {
        $currentPath = $block['data'][$field];
        $directory = dirname($currentPath);
        $newPath = $directory . '/' . uniqid() . '-' . basename($currentPath);
        
        //File::copy(storage_path('app/public/' . $currentPath), storage_path('app/public/' . $newPath));
        Storage::disk('public')->copy($currentPath, $newPath);

        $block['data'][$field] = $newPath;

        return $block;
    }
}
