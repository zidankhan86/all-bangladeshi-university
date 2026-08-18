<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('bangladesh-universities.tables.locations', 'bd_university_locations'), function (Blueprint $table) {
            $table->id();
            $table->string('division')->nullable()->index();
            $table->string('district')->nullable()->index();
            $table->string('upazila')->nullable()->index();
            $table->string('area')->nullable()->index();
            $table->string('source_url')->nullable();
            $table->timestamps();

            $table->unique(['division', 'district', 'upazila', 'area'], 'bd_uni_locations_unique_place');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('bangladesh-universities.tables.locations', 'bd_university_locations'));
    }
};
