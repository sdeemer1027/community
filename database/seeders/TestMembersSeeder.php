<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestMembersSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $adminEmail = 'dr.steve2764@gmail.com';

        DB::table('users')->updateOrInsert(
            ['email' => $adminEmail],
            [
                'name' => 'Dr. Steve',
                'password' => Hash::make('CommunityDev!2026'),
                'email_verified_at' => $now,
                'zip_code' => null,
                'is_super_admin' => true,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        $members = [
            ['Alex Morgan', 'alex.morgan', 'Male', '33324'],
            ['Daniel Carter', 'daniel.carter', 'Male', '33324'],
            ['Ethan Brooks', 'ethan.brooks', 'Male', '33324'],
            ['James Parker', 'james.parker', 'Male', '33324'],
            ['Michael Reed', 'michael.reed', 'Male', '33324'],

            ['Olivia Bennett', 'olivia.bennett', 'Female', '33324'],
            ['Emma Collins', 'emma.collins', 'Female', '33324'],
            ['Sophia Hayes', 'sophia.hayes', 'Female', '33324'],
            ['Ava Mitchell', 'ava.mitchell', 'Female', '33324'],
            ['Isabella Foster', 'isabella.foster', 'Female', '33324'],

            ['Noah Turner', 'noah.turner', 'Male', '33020'],
            ['Liam Cooper', 'liam.cooper', 'Male', '33020'],
            ['Lucas Bailey', 'lucas.bailey', 'Male', '33020'],

            ['Mia Rivera', 'mia.rivera', 'Female', '33020'],
            ['Charlotte Ward', 'charlotte.ward', 'Female', '33020'],
            ['Amelia Price', 'amelia.price', 'Female', '33020'],

            ['Benjamin Gray', 'benjamin.gray', 'Male', '07840'],
            ['Henry James', 'henry.james', 'Male', '07840'],

            ['Harper Ross', 'harper.ross', 'Female', '07840'],
            ['Evelyn Cook', 'evelyn.cook', 'Female', '07840'],
        ];

        foreach ($members as [$name, $emailPrefix, $gender, $zip]) {
            DB::table('users')->updateOrInsert(
                ['email' => $emailPrefix . '@community.test'],
                [
                    'name' => $name,
                    'password' => Hash::make('CommunityDev!2026'),
                    'email_verified_at' => $now,
                    'zip_code' => $zip,
                    'gender' => $gender,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $this->command->info(
            'Administrator account and 20 test-member accounts prepared.'
        );
    }
}
