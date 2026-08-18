<?php

namespace NexCoreIT\BangladeshUniversities;

use Illuminate\Support\ServiceProvider;
use NexCoreIT\BangladeshUniversities\Commands\InstallUniversitiesCommand;
use NexCoreIT\BangladeshUniversities\Commands\SeedUniversitiesCommand;
use NexCoreIT\BangladeshUniversities\Commands\UpdateUniversitiesCommand;
use NexCoreIT\BangladeshUniversities\Contracts\UniversityImporter;
use NexCoreIT\BangladeshUniversities\Services\DatasetRepository;
use NexCoreIT\BangladeshUniversities\Services\Universities;
use NexCoreIT\BangladeshUniversities\Services\UniversityDatasetImporter;

class BangladeshUniversitiesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bangladesh-universities.php', 'bangladesh-universities');

        $this->app->singleton(DatasetRepository::class);
        $this->app->singleton(UniversityImporter::class, UniversityDatasetImporter::class);
        $this->app->singleton('bangladesh-universities', Universities::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/bangladesh-universities.php' => config_path('bangladesh-universities.php'),
        ], 'bangladesh-universities-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'bangladesh-universities-migrations');

        $this->publishes([
            __DIR__.'/../data' => database_path('data/bangladesh-universities'),
        ], 'bangladesh-universities-data');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallUniversitiesCommand::class,
                SeedUniversitiesCommand::class,
                UpdateUniversitiesCommand::class,
            ]);
        }
    }
}
