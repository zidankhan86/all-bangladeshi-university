# Usage Guide

This guide shows common ways to use `nexcoreit/bangladesh-universities` inside a Laravel application.

## Install

```bash
composer require nexcoreit/bangladesh-universities
```

Publish config and migrations:

```bash
php artisan universities:install
php artisan migrate
php artisan universities:seed
```

Seed only one university type:

```bash
php artisan universities:seed --type=public
php artisan universities:seed --type=private
```

The seeder is idempotent. Running it multiple times updates existing package-managed university and campus records by slug instead of creating duplicates.

## Models

Use the package models directly when you want full Eloquent control.

```php
use NexCoreIT\BangladeshUniversities\Models\University;
use NexCoreIT\BangladeshUniversities\Models\UniversityCampus;
use NexCoreIT\BangladeshUniversities\Models\Location;
```

## List Universities

```php
$universities = University::query()
    ->active()
    ->orderBy('name')
    ->get();
```

Public universities:

```php
$publicUniversities = University::public()
    ->orderBy('name')
    ->get();
```

Private universities:

```php
$privateUniversities = University::private()
    ->orderBy('name')
    ->get();
```

## Find by Slug

```php
$university = University::where('slug', 'university-of-dhaka')->firstOrFail();
```

With campuses:

```php
$university = University::with('campuses')
    ->where('slug', 'university-of-dhaka')
    ->firstOrFail();
```

## Relationships

Get all campuses for a university:

```php
$campuses = $university->campuses;
```

Get the main campus:

```php
$mainCampus = $university->mainCampus;
```

Get the university for a campus:

```php
$campus = UniversityCampus::first();
$university = $campus->university;
```

Get the normalized location for a campus:

```php
$location = $campus->location;
```

## Search

Search checks English name, Bangla name when available, short name, and slug.

```php
$results = University::search('Dhaka')->get();
$results = University::search('DU')->get();
$results = University::search('ঢাকা')->get();
```

Search only active universities:

```php
$results = University::active()
    ->search('North South')
    ->get();
```

## Filter by Location

Universities in a division:

```php
$universities = University::inDivision('Dhaka')->get();
```

Universities in a district:

```php
$universities = University::inDistrict('Gazipur')->get();
```

Campuses in a division:

```php
$campuses = UniversityCampus::inDivision('Chattogram')->get();
```

Campuses in a district:

```php
$campuses = UniversityCampus::inDistrict('Cumilla')->get();
```

## Filter by Type and Category

```php
$universities = University::byType('private')->get();
$universities = University::byCategory('engineering')->get();
```

Combine filters:

```php
$universities = University::active()
    ->byType('public')
    ->byCategory('science_technology')
    ->inDivision('Dhaka')
    ->orderBy('name')
    ->get();
```

Supported initial types:

```text
public
private
international
specialized
```

Supported initial categories:

```text
general
engineering
medical
agricultural
science_technology
textile
aviation
maritime
open
other
```

## Facade API

Use the facade when you want a simple read API without building queries manually.

```php
Universities::all();
Universities::public();
Universities::private();
Universities::search('Dhaka');
Universities::byDivision('Dhaka');
Universities::byDistrict('Gazipur');
Universities::findBySlug('university-of-dhaka');
```

Import the facade explicitly if preferred:

```php
use NexCoreIT\BangladeshUniversities\Facades\Universities;
```

## Controller Example

```php
namespace App\Http\Controllers;

use NexCoreIT\BangladeshUniversities\Models\University;

class UniversityDirectoryController
{
    public function index()
    {
        $universities = University::query()
            ->with('mainCampus')
            ->active()
            ->when(request('type'), fn ($query, $type) => $query->byType($type))
            ->when(request('division'), fn ($query, $division) => $query->inDivision($division))
            ->when(request('district'), fn ($query, $district) => $query->inDistrict($district))
            ->when(request('q'), fn ($query, $term) => $query->search($term))
            ->orderBy('name')
            ->paginate(25);

        return view('universities.index', compact('universities'));
    }
}
```

## API Resource Example

```php
use Illuminate\Support\Facades\Route;
use NexCoreIT\BangladeshUniversities\Models\University;

Route::get('/universities', function () {
    return University::query()
        ->with('campuses')
        ->when(request('q'), fn ($query, $term) => $query->search($term))
        ->when(request('type'), fn ($query, $type) => $query->byType($type))
        ->when(request('division'), fn ($query, $division) => $query->inDivision($division))
        ->orderBy('name')
        ->paginate();
});
```

## Custom Table Names

Publish the config:

```bash
php artisan vendor:publish --tag=bangladesh-universities-config
```

Then edit `config/bangladesh-universities.php`:

```php
'tables' => [
    'universities' => 'bd_universities',
    'campuses' => 'bd_university_campuses',
    'locations' => 'bd_university_locations',
],
```

Publish migrations after changing table names so the generated app migrations use the intended config values.

## Custom Dataset Path

Use a reviewed application-specific dataset path when needed:

```env
BD_UNIVERSITIES_DATASET_PATH=/absolute/path/to/bangladesh-universities-data
```

The directory should contain:

```text
universities.json
locations.json
```

Then run:

```bash
php artisan universities:seed
```

## Updating Data

The package does not download remote updates.

Recommended workflow:

1. Update the JSON dataset.
2. Keep `source_url` and `last_verified_at` accurate.
3. Use `null` for unverified fields.
4. Run the package tests.
5. Run `php artisan universities:seed`.

```bash
php artisan universities:update
```

This command explains the same workflow inside Laravel.

## Testing in This Package

```bash
composer install
vendor/bin/phpunit
```

On Laragon/Windows, if SQLite exists but is not enabled in `php.ini`, run:

```bash
php -d extension=php_pdo_sqlite.dll -d extension=php_sqlite3.dll vendor\bin\phpunit
```

