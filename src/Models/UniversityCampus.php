<?php

namespace NexCoreIT\BangladeshUniversities\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use NexCoreIT\BangladeshUniversities\Models\Concerns\UsesConfiguredTable;

class UniversityCampus extends Model
{
    use UsesConfiguredTable;

    protected string $tableConfigKey = 'bangladesh-universities.tables.campuses';
    protected string $fallbackTable = 'bd_university_campuses';

    protected $guarded = [];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_main_campus' => 'boolean',
        'last_verified_at' => 'date',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class, 'university_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function scopeInDivision($query, string $division)
    {
        return $query->where('division', $division);
    }

    public function scopeInDistrict($query, string $district)
    {
        return $query->where('district', $district);
    }
}
