<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_one_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('user_two_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamp('last_message_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['user_one_id', 'user_two_id'],
                'chat_conversations_users_unique'
            );

            $table->index('last_message_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_conversations');
    }
};
