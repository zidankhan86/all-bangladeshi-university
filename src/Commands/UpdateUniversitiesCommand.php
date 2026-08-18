<?php

namespace NexCoreIT\BangladeshUniversities\Commands;

use Illuminate\Console\Command;

class UpdateUniversitiesCommand extends Command
{
    protected $signature = 'universities:update';
    protected $description = 'Explain the supported dataset update workflow.';

    public function handle(): int
    {
        $this->warn('No remote maintained dataset/API is bundled with this package.');
        $this->line('Update data/*.json or publish the dataset, review the source_url/last_verified_at fields, then run universities:seed.');

        return self::SUCCESS;
    }
}
