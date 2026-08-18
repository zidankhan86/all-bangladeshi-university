<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('bangladesh-universities.tables.universities', 'bd_universities'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('slug')->unique();
            $table->string('short_name')->nullable()->index();
            $table->string('type')->index();
            $table->string('category')->default('general')->index();
            $table->unsignedSmallInteger('established_year')->nullable()->index();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('ugc_status')->nullable()->index();
            $table->string('status')->default('active')->index();
            $table->string('source_url')->nullable();
            $table->date('last_verified_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['type', 'category'], 'bd_universities_type_category_index');
            $table->index(['name', 'short_name'], 'bd_universities_name_short_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('bangladesh-universities.tables.universities', 'bd_universities'));
    }
};
