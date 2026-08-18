<?php

namespace NexCoreIT\BangladeshUniversities\Tests\Unit;

use NexCoreIT\BangladeshUniversities\Services\DatasetRepository;
use NexCoreIT\BangladeshUniversities\Support\UniversityCategory;
use NexCoreIT\BangladeshUniversities\Support\UniversityType;
use NexCoreIT\BangladeshUniversities\Tests\TestCase;

class DatasetValidationTest extends TestCase
{
    public function test_university_dataset_is_valid(): void
    {
        $records = $this->app->make(DatasetRepository::class)->universities();
        $slugs = [];

        foreach ($records as $record) {
            $this->assertNotEmpty($record['name']);
            $this->assertNotEmpty($record['slug']);
            $this->assertContains($record['type'], UniversityType::values());
            $this->assertContains($record['category'], UniversityCategory::values());
            $this->assertNotContains($record['slug'], $slugs);
            $slugs[] = $record['slug'];

            foreach ($record['campuses'] ?? [] as $campus) {
                $this->assertNotEmpty($campus['slug']);
                $this->assertSame($record['source_url'], $campus['source_url']);

                if (($campus['latitude'] ?? null) !== null) {
                    $this->assertGreaterThanOrEqual(-90, $campus['latitude']);
                    $this->assertLessThanOrEqual(90, $campus['latitude']);
                }

                if (($campus['longitude'] ?? null) !== null) {
                    $this->assertGreaterThanOrEqual(-180, $campus['longitude']);
                    $this->assertLessThanOrEqual(180, $campus['longitude']);
                }
            }
        }
    }
}
