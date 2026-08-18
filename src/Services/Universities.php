<?php

namespace NexCoreIT\BangladeshUniversities\Services;

use Illuminate\Database\Eloquent\Collection;
use NexCoreIT\BangladeshUniversities\Models\University;

class Universities
{
    public function all(): Collection
    {
        return University::query()->with('campuses')->get();
    }

    public function public(): Collection
    {
        return University::query()->public()->with('campuses')->get();
    }

    public function private(): Collection
    {
        return University::query()->private()->with('campuses')->get();
    }

    public function search(string $term): Collection
    {
        return University::query()->search($term)->with('campuses')->get();
    }

    public function byDivision(string $division): Collection
    {
        return University::query()->inDivision($division)->with('campuses')->get();
    }

    public function byDistrict(string $district): Collection
    {
        return University::query()->inDistrict($district)->with('campuses')->get();
    }

    public function findBySlug(string $slug): ?University
    {
        return University::query()->where('slug', $slug)->with('campuses')->first();
    }
}
