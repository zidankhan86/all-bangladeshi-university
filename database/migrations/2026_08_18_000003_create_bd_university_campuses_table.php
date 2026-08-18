<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $universities = config('bangladesh-universities.tables.universities', 'bd_universities');
        $locations = config('bangladesh-universities.tables.locations', 'bd_university_locations');

        Schema::create(config('bangladesh-universities.tables.campuses', 'bd_university_campuses'), function (Blueprint $table) use ($universities, $locations) {
            $table->id();
            $table->foreignId('university_id')->constrained($universities)->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained($locations)->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('campus_type')->default('main')->index();
            $table->text('address')->nullable();
            $table->string('division')->nullable()->index();
            $table->string('district')->nullable()->index();
            $table->string('upazila')->nullable()->index();
            $table->string('area')->nullable()->index();
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_main_campus')->default(false)->index();
            $table->string('source_url')->nullable();
            $table->date('last_verified_at')->nullable();
            $table->timestamps();

            $table->unique(['university_id', 'slug'], 'bd_uni_campuses_university_slug_unique');
            $table->index(['division', 'district'], 'bd_uni_campuses_division_district_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('bangladesh-universities.tables.campuses', 'bd_university_campuses'));
    }
};
