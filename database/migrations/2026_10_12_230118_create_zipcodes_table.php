<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zipcodes', function (Blueprint $table) {
            $table->id();
            $table->string('zip', 191);
            $table->string('type', 191);
            $table->string('city', 191);
            $table->string('typeb', 191);
            $table->string('statename', 191);
            $table->string('stateabv', 191);
            $table->string('code', 191);
            $table->string('lat', 191);
            $table->string('lng', 191);
            $table->string('country', 191);
            $table->string('status', 191);
            $table->timestamps();

            $table->index('zip');
            $table->index(['stateabv', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zipcodes');
    }
};