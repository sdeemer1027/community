<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('connection_blocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('blocker_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('blocked_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(
                ['blocker_id', 'blocked_id'],
                'connection_blocks_blocker_blocked_unique'
            );

            $table->index('blocked_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('connection_blocks');
    }
};
