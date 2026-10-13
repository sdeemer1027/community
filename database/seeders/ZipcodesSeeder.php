<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ZipcodesSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/zipcodes.sql');

        if (!File::exists($path)) {
            throw new \RuntimeException(
                "ZIP-code SQL file not found: {$path}"
            );
        }

        $this->command->info('Importing ZIP-code data from SQL file...');

        DB::unprepared(File::get($path));

        $count = DB::table('zipcodes')->count();

        $this->command->info(
            "ZIP-code import complete. Imported {$count} records."
        );
    }
}
