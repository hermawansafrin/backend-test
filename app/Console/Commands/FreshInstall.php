<?php

namespace App\Console\Commands;

use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ReferenceSeeder;
use Database\Seeders\UserWithPermissionSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FreshInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fresh-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fresh install the application including database, seeder, and etc';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->clearCache();
        $this->clearStorage();
        $this->generateBaseSeedAndMigration();
        $this->info('Application installed successfully');
        $this->newLine();
    }

    /**
     * Generate base seed and migration for all data application
     * @return void
     */
    private function generateBaseSeedAndMigration(): void
    {
        $this->makeFreshTable();
        $this->generateAllSeed();

        $this->info('Base seed and migration generated successfully');
        $this->newLine();
    }

    /**
     * Make fresh table
     * @return void
     */
    private function makeFreshTable(): void
    {
        $this->comment('Making fresh table...');
        $this->callSilent('migrate:fresh');
        $this->info('Fresh table generated successfully');
        $this->newLine();
    }

    /**
     * Generate all seed
     * @return void
     */
    private function generateAllSeed(): void
    {
        $this->comment('Seeding database...');

        $seeders = [
            UserWithPermissionSeeder::class,// must be first seed cause there is user creation with role permission data
            ReferenceSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
        /** add here if there is something new */
        ];

        collect($seeders)->each(function ($class) {
            $this->seedingOutput($class);
            $this->callSilent('db:seed', ['--class' => $class]);
        });

        $this->info('Database seeded successfully');
        $this->newLine();
    }

    /**
     * Clear the storage application
     * @return void
     */
    public function clearStorage(): void
    {
        $this->comment('Clearing storage...');

        $storages = [
            'public',
            // add here if there is anything else
        ];

        foreach ($storages as $storage) {
            $this->comment('Clearing ' . $storage . ' storage...');
            Storage::disk($storage)->deleteDirectory('/');
        }

        /** create symlink */
        $this->comment('Creating symlink...');
        $this->callSilent('storage:link');
        $this->info('Storage cleared successfully');

        $this->newLine();
    }

    /**
     * Clear the cache application
     * @return void
     */
    private function clearCache(): void
    {
        $this->comment('Clearing cache...');
        try {
            collect([
                'event:clear',
                'view:clear',
                'cache:clear',
                'config:clear',
                'config:cache',
                'route:clear',
            ])->each(function ($command) {
                $this->callSilent($command);
            });

            $this->info('Cache cleared successfully');
        } catch (\Exception $e) {
            $this->error('Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Console output seeding class
     * @param string $lass
     * @return void
     */
    private function seedingOutput(string $class): void
    {
        $this->line("<comment>Seeding : </comment> {$this->cyan($class)}");
    }

    /**
     * Formatting command style
     * @param string $message
     * @return string
     */
    private function cyan(string $message): string
    {
        return "<fg=cyan>{$message}</>";
    }
}
