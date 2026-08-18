<?php

namespace NexCoreIT\BangladeshUniversities\Tests;

use NexCoreIT\BangladeshUniversities\BangladeshUniversitiesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [BangladeshUniversitiesServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Universities' => \NexCoreIT\BangladeshUniversities\Facades\Universities::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
