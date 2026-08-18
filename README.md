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

```bash
composer require nexcoreit/bangladesh-universities
```

Publish config and migrations:

```bash
php artisan universities:install
php artisan migrate
php artisan universities:seed
```

Or publish resources manually:

```bash
php artisan vendor:publish --tag=bangladesh-universities-config
php artisan vendor:publish --tag=bangladesh-universities-migrations
php artisan vendor:publish --tag=bangladesh-universities-data
```

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

## Usage

```php
use NexCoreIT\BangladeshUniversities\Models\University;

$universities = University::query()->active()->get();
$public = University::public()->get();
$private = University::private()->get();
$engineering = University::byCategory('engineering')->get();
```

## Location Filtering

```php
University::inDivision('Dhaka')->get();
University::inDistrict('Gazipur')->get();
```

Campus data is available through the relationship:

```php
$university = University::where('slug', 'university-of-dhaka')->first();
$campuses = $university->campuses;
$mainCampus = $university->mainCampus;
```

## Search

Search covers English name, Bangla name when available, short name, and slug:

```php
University::search('Dhaka')->get();
University::search('DU')->get();
University::search('ঢাকা')->get();
```

No external search service is required.

## Facade API

```php
Universities::all();
Universities::public();
Universities::private();
Universities::search('North South');
Universities::byDivision('Dhaka');
Universities::byDistrict('Gazipur');
Universities::findBySlug('university-of-dhaka');
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

Edit the JSON files, preserve `source_url` and `last_verified_at`, run the test suite, then seed again:

```bash
php artisan universities:seed
```

The importer uses `updateOrCreate()` by university slug and campus slug, so repeated seeding is safe and does not create duplicate package-managed records.

## Testing

```bash
composer install
vendor/bin/phpunit
```

The tests cover package booting, migrations, dataset validation, idempotent seeding, model relationships, scopes, location filters, search, facade usage, and commands.

## Contributing

Corrections are welcome. Please include an authoritative source URL, avoid approximate coordinates, and use `null` for unverified fields. Do not copy restricted datasets into the repository.

## Versioning

This package starts at `v1.0.0` and follows Semantic Versioning.

## License

The package code is released under the MIT License. Dataset contributors should ensure submitted data can be redistributed with attribution.
# all-bangladeshi-university
# all-bangladeshi-university
