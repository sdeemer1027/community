<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_items', function (Blueprint $table) {
            $table->id();

            // The member who owns this media.
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // The private storage path, not a public URL.
            $table->string('file_path');

            // image or video.
            $table->string('media_type', 20);

            // The filename originally uploaded by the member.
            $table->string('original_filename');

            // Optional description for the library item.
            $table->string('caption', 1000)->nullable();

            // File size in bytes.
            $table->unsignedBigInteger('file_size');

            // MIME type, such as image/jpeg or video/mp4.
            $table->string('mime_type', 100);

            $table->timestamps();

            $table->index(['user_id', 'media_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_items');
    }
};
