<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteModelFiles extends Command
{
    protected $signature = 'delete:model {name}';
    protected $description = 'Delete a model, factory, and migration by name';

    public function handle()
    {
        $name = $this->argument('name');

        // Paths to delete
        $modelPath = app_path("Models/{$name}.php");
        $factoryPath = base_path("database/factories/{$name}Factory.php");
        $migrationPath = base_path("database/migrations");

        // Delete Model
        if (File::exists($modelPath)) {
            File::delete($modelPath);
            $this->info("Deleted model: {$modelPath}");
        } else {
            $this->warn("Model not found: {$modelPath}");
        }

        // Delete Factory
        if (File::exists($factoryPath)) {
            File::delete($factoryPath);
            $this->info("Deleted factory: {$factoryPath}");
        } else {
            $this->warn("Factory not found: {$factoryPath}");
        }

        // Delete Migrations
        $migrations = File::files($migrationPath);
        foreach ($migrations as $migration) {
            if (str_contains($migration->getFilename(), "create_{$name}s_table")) {
                File::delete($migration->getPathname());
                $this->info("Deleted migration: {$migration->getFilename()}");
            }
        }

        $this->info("Deletion process completed.");
    }
}
