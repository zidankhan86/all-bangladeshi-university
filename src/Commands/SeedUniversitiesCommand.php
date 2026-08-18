<?php

namespace NexCoreIT\BangladeshUniversities\Commands;

use Illuminate\Console\Command;
use NexCoreIT\BangladeshUniversities\Contracts\UniversityImporter;
use NexCoreIT\BangladeshUniversities\Support\UniversityType;

class SeedUniversitiesCommand extends Command
{
    protected $signature = 'universities:seed {--type= : public, private, international, or specialized}';
    protected $description = 'Seed Bangladesh university data from the package JSON dataset.';

    public function handle(UniversityImporter $importer): int
    {
        $type = $this->option('type');

        if ($type !== null && ! in_array($type, UniversityType::values(), true)) {
            $this->error('Invalid type. Supported values: '.implode(', ', UniversityType::values()));

            return self::FAILURE;
        }

        $stats = $importer->import($type);

        $this->info("Seeded {$stats['universities']} universities and {$stats['campuses']} campuses.");

        return self::SUCCESS;
    }
}
