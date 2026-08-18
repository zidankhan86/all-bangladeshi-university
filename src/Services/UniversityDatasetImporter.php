<?php

namespace NexCoreIT\BangladeshUniversities\Services;

use Illuminate\Support\Arr;
use NexCoreIT\BangladeshUniversities\Contracts\UniversityImporter;
use NexCoreIT\BangladeshUniversities\Models\Location;
use NexCoreIT\BangladeshUniversities\Models\University;

class UniversityDatasetImporter implements UniversityImporter
{
    public function __construct(protected DatasetRepository $datasets)
    {
    }

    public function import(?string $type = null): array
    {
        $stats = [
            'universities' => 0,
            'campuses' => 0,
            'locations' => 0,
        ];

        foreach ($this->datasets->universities($type) as $record) {
            $university = University::query()->updateOrCreate(
                ['slug' => $record['slug']],
                Arr::only($record, [
                    'name',
                    'name_bn',
                    'short_name',
                    'type',
                    'category',
                    'established_year',
                    'website',
                    'email',
                    'phone',
                    'ugc_status',
                    'status',
                    'source_url',
                    'last_verified_at',
                    'metadata',
                ])
            );

            $stats['universities']++;

            foreach ($record['campuses'] ?? [] as $campus) {
                $location = Location::query()->firstOrCreate(
                    [
                        'division' => $campus['division'] ?? null,
                        'district' => $campus['district'] ?? null,
                        'upazila' => $campus['upazila'] ?? null,
                        'area' => $campus['area'] ?? null,
                    ],
                    ['source_url' => $campus['source_url'] ?? $record['source_url'] ?? null]
                );

                $university->campuses()->updateOrCreate(
                    ['slug' => $campus['slug']],
                    array_merge(Arr::only($campus, [
                        'name',
                        'campus_type',
                        'address',
                        'division',
                        'district',
                        'upazila',
                        'area',
                        'postal_code',
                        'latitude',
                        'longitude',
                        'is_main_campus',
                        'source_url',
                        'last_verified_at',
                    ]), ['location_id' => $location->getKey()])
                );

                $stats['campuses']++;
            }
        }

        $stats['locations'] = Location::query()->count();

        return $stats;
    }
}
