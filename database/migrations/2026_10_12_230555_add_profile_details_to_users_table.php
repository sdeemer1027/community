<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('gender', 30)->nullable();
            $table->date('birthdate')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_super_admin')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'city',
                'state',
                'phone',
                'gender',
                'birthdate',
                'bio',
                'is_super_admin',
            ]);
        });
    }
};
