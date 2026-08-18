# Bangladesh Universities for Laravel

`nexcoreit/bangladesh-universities` is a reusable Laravel package for working with Bangladesh public and private university data. It ships with JSON datasets, configurable database tables, Eloquent models, query scopes, an importer, a facade, and Artisan commands.

The package is designed for admission systems, job portals, student management tools, directories, scholarship platforms, marketplaces, and other Laravel applications that need structured university and campus/location data.

## Features

- Public and private Bangladesh university records
- Multiple-campus capable schema
- Structured division, district, upazila, and area fields
- Configurable table names
- Idempotent JSON dataset importer
- Eloquent relationships and scopes
- Simple `Universities` facade/service API
- Publishable config, migrations, and dataset files
- Orchestra Testbench coverage

## Requirements

- PHP 8.1 or newer
- Laravel 10, 11, or 12

## Installation

After this package is published to Packagist, install it with:

```bash
composer require nexcoreit/bangladesh-universities
```

For local development before Packagist publishing, add this package as a Composer path repository from your Laravel application:

```bash
composer config repositories.bangladesh-universities path ../../university
composer require nexcoreit/bangladesh-universities:@dev
```

If your Laravel app is not two directories below the package folder, adjust `../../university` to the correct relative or absolute path.

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

Or publish resources manually:

```bash
php artisan vendor:publish --tag=bangladesh-universities-config
php artisan vendor:publish --tag=bangladesh-universities-migrations
php artisan vendor:publish --tag=bangladesh-universities-data
```

The seeder is idempotent. Running it multiple times updates existing package-managed university and campus records by slug instead of creating duplicates.

## Configuration

The config file is published to `config/bangladesh-universities.php`.

```php
return [
    'tables' => [
        'universities' => 'bd_universities',
        'campuses' => 'bd_university_campuses',
        'locations' => 'bd_university_locations',
    ],

    'dataset_path' => null,
    'default_language' => 'en',
    'managed_by_package' => true,
];
```

Use `BD_UNIVERSITIES_DATASET_PATH` if your app maintains a reviewed copy of the dataset outside the package.

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

No external search service is required.

## Location Filtering

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

## Type and Category Filtering

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

## Artisan Commands

```bash
php artisan universities:install
php artisan universities:install --seed
php artisan universities:seed
php artisan universities:seed --type=public
php artisan universities:seed --type=private
php artisan universities:update
```

`universities:update` does not download remote data. It explains the supported update workflow because the package does not currently bundle a maintained remote dataset/API.

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

## Dataset Structure

Raw data lives in `data/`:

```text
data/
  universities.json
  public-universities.json
  private-universities.json
  locations.json
```

Each university record includes fields such as:

```json
{
  "name": "University of Dhaka",
  "name_bn": null,
  "slug": "university-of-dhaka",
  "short_name": "DU",
  "type": "public",
  "category": "general",
  "established_year": 1921,
  "website": "https://www.du.ac.bd",
  "source_url": "https://bangladesh.gov.bd/pages/static-pages/69a55ba386514399668e4e8a",
  "last_verified_at": "2026-08-18",
  "campuses": []
}
```

## Data Sources

The v1.0.0 dataset was assembled from the supplied project dataset and source fields, cross-referenced against:

- Bangladesh National Portal public university page citing University Grants Commission Bangladesh: https://bangladesh.gov.bd/pages/static-pages/69a55ba386514399668e4e8a
- UGC Bangladesh private university directory mirror: https://www.royal-edu-bd.info/ugc/en/home/university/private/75.html
- Official university websites where a website was present in the source record

Some records include only city/locality-level campus information because more precise campus addresses were not available in the provided source. Coordinates, phone numbers, emails, and Bangla names are kept `null` unless verified data is available.

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

The importer uses `updateOrCreate()` by university slug and campus slug, so repeated seeding is safe and does not create duplicate package-managed records.

## Testing

```bash
composer install
vendor/bin/phpunit
```

On Laragon/Windows, if SQLite exists but is not enabled in `php.ini`, run:

```bash
php -d extension=php_pdo_sqlite.dll -d extension=php_sqlite3.dll vendor\bin\phpunit
```

The tests cover package booting, migrations, dataset validation, idempotent seeding, model relationships, scopes, location filters, search, facade usage, and commands.

## Contributing

Corrections are welcome. Please include an authoritative source URL, avoid approximate coordinates, and use `null` for unverified fields. Do not copy restricted datasets into the repository.

## Versioning

This package starts at `v1.0.0` and follows Semantic Versioning.

## License

The package code is released under the MIT License. Dataset contributors should ensure submitted data can be redistributed with attribution.
