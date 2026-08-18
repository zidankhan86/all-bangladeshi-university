<?php

namespace NexCoreIT\BangladeshUniversities\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use NexCoreIT\BangladeshUniversities\Models\Concerns\UsesConfiguredTable;

class University extends Model
{
    use UsesConfiguredTable;

    protected string $tableConfigKey = 'bangladesh-universities.tables.universities';
    protected string $fallbackTable = 'bd_universities';

    protected $guarded = [];

    protected $casts = [
        'established_year' => 'integer',
        'last_verified_at' => 'date',
        'metadata' => 'array',
    ];

    public function campuses(): HasMany
    {
        return $this->hasMany(UniversityCampus::class, 'university_id');
    }

    public function mainCampus()
    {
        return $this->hasOne(UniversityCampus::class, 'university_id')->where('is_main_campus', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePublic($query)
    {
        return $query->where('type', 'public');
    }

    public function scopePrivate($query)
    {
        return $query->where('type', 'private');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeInDivision($query, string $division)
    {
        return $query->whereHas('campuses', fn ($campuses) => $campuses->where('division', $division));
    }

    public function scopeInDistrict($query, string $district)
    {
        return $query->whereHas('campuses', fn ($campuses) => $campuses->where('district', $district));
    }

    public function scopeSearch($query, string $term)
    {
        $needle = mb_strtolower(trim($term));

        return $query->where(function ($query) use ($needle) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$needle}%"])
                ->orWhereRaw("LOWER(COALESCE(name_bn, '')) LIKE ?", ["%{$needle}%"])
                ->orWhereRaw("LOWER(COALESCE(short_name, '')) LIKE ?", ["%{$needle}%"])
                ->orWhereRaw('LOWER(slug) LIKE ?', ["%{$needle}%"]);
        });
    }
}
