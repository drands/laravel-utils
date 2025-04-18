<?php

namespace Drands\LaravelUtils\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class StorageClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the storage folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing the storage folder...');

        $file = new Filesystem;
        
        //remove all files from the public storage directory minus .gitignore
        $file->cleanDirectory('storage/app/public');
        $file->put('storage/app/public/.gitignore', "*\n!.gitignore");
        
        $file->cleanDirectory('storage/app/fonts');
        
        $this->info('Storage folder has been cleared.');
    }
}
