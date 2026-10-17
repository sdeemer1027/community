<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('post_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('reporter_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('reason', 50);
            $table->text('details')->nullable();

            $table->string('status', 20)->default('pending')->index();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['post_id', 'reporter_id'],
                'post_reports_post_reporter_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_reports');
    }
};
