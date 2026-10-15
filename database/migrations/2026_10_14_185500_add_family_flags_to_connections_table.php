<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->boolean('requester_is_family')->default(false);
            $table->boolean('recipient_is_family')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('connections', function (Blueprint $table) {
            $table->dropColumn([
                'requester_is_family',
                'recipient_is_family',
            ]);
        });
    }
};
