<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_media', function (Blueprint $table) {
            $table->id();

            $table->foreignId('post_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('media_item_id')
                ->constrained('media_items')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['post_id', 'media_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_media');
    }
};
