<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crime_data_sources', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('agency_name')->nullable();
            $table->string('source_url', 2048);
            $table->string('api_endpoint', 2048);

            $table->string('country', 100)->default('United States');
            $table->string('state', 100)->nullable();
            $table->string('city', 150)->nullable();
            $table->string('county', 150)->nullable();
            $table->string('zip_code', 10)->nullable();

            $table->string('coverage_type', 30)->default('unknown');
            $table->string('data_format', 30)->default('json');

            $table->json('field_mapping')->nullable();
            $table->json('query_config')->nullable();

            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['country', 'state']);
            $table->index(['state', 'city']);
            $table->index(['state', 'county']);
            $table->index(['zip_code', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crime_data_sources');
    }
};
