<?php

namespace NexCoreIT\BangladeshUniversities\Commands;

use Illuminate\Console\Command;

class InstallUniversitiesCommand extends Command
{
    protected $signature = 'universities:install {--seed : Seed the package dataset after publishing resources}';
    protected $description = 'Publish Bangladesh Universities config and migrations.';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--provider' => 'NexCoreIT\\BangladeshUniversities\\BangladeshUniversitiesServiceProvider',
            '--tag' => 'bangladesh-universities-config',
            '--force' => false,
        ]);

        $this->call('vendor:publish', [
            '--provider' => 'NexCoreIT\\BangladeshUniversities\\BangladeshUniversitiesServiceProvider',
            '--tag' => 'bangladesh-universities-migrations',
            '--force' => false,
        ]);

        if ($this->option('seed')) {
            $this->call('universities:seed');
        }

        $this->info('Bangladesh Universities package installed.');

        return self::SUCCESS;
    }
}
