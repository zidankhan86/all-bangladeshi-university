<?php

namespace NexCoreIT\BangladeshUniversities\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use NexCoreIT\BangladeshUniversities\Models\Concerns\UsesConfiguredTable;

class Location extends Model
{
    use UsesConfiguredTable;

    protected string $tableConfigKey = 'bangladesh-universities.tables.locations';
    protected string $fallbackTable = 'bd_university_locations';

    protected $guarded = [];

    public function campuses(): HasMany
    {
        return $this->hasMany(UniversityCampus::class, 'location_id');
    }

    public function scopeDivision($query, string $division)
    {
        return $query->where('division', $division);
    }

    public function scopeDistrict($query, string $district)
    {
        return $query->where('district', $district);
    }
}
