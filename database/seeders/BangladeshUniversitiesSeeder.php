<?php

namespace NexCoreIT\BangladeshUniversities\Database\Seeders;

use Illuminate\Database\Seeder;
use NexCoreIT\BangladeshUniversities\Contracts\UniversityImporter;

class BangladeshUniversitiesSeeder extends Seeder
{
    public function run(UniversityImporter $importer): void
    {
        $importer->import();
    }
}
