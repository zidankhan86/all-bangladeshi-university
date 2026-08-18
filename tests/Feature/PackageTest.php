<?php

namespace NexCoreIT\BangladeshUniversities\Tests\Feature;

use NexCoreIT\BangladeshUniversities\Contracts\UniversityImporter;
use NexCoreIT\BangladeshUniversities\Facades\Universities;
use NexCoreIT\BangladeshUniversities\Models\University;
use NexCoreIT\BangladeshUniversities\Models\UniversityCampus;
use NexCoreIT\BangladeshUniversities\Tests\TestCase;

class PackageTest extends TestCase
{
    public function test_package_boots(): void
    {
        $this->assertTrue($this->app->bound('bangladesh-universities'));
        $this->assertTrue($this->app->bound(UniversityImporter::class));
    }

    public function test_seeder_is_idempotent_and_relationships_work(): void
    {
        $importer = $this->app->make(UniversityImporter::class);

        $first = $importer->import();
        $second = $importer->import();

        $this->assertSame($first['universities'], University::query()->count());
        $this->assertSame($first['campuses'], UniversityCampus::query()->count());
        $this->assertSame($first['universities'], $second['universities']);

        $university = University::query()->where('slug', 'university-of-dhaka')->firstOrFail();
        $this->assertNotNull($university->campuses()->first());
        $this->assertSame('public', $university->type);
    }

    public function test_scopes_and_facade(): void
    {
        $this->app->make(UniversityImporter::class)->import();

        $this->assertGreaterThan(0, University::query()->public()->count());
        $this->assertGreaterThan(0, University::query()->private()->count());
        $this->assertGreaterThan(0, University::query()->inDivision('Dhaka')->count());
        $this->assertGreaterThan(0, University::query()->inDistrict('Dhaka')->count());
        $this->assertSame('University of Dhaka', University::query()->search('DU')->first()->name);
        $this->assertSame('University of Dhaka', Universities::findBySlug('university-of-dhaka')->name);
    }

    public function test_seed_command_accepts_type_option(): void
    {
        $this->artisan('universities:seed', ['--type' => 'public'])
            ->assertExitCode(0);

        $this->assertGreaterThan(0, University::query()->public()->count());
        $this->assertSame(0, University::query()->private()->count());
    }
}
