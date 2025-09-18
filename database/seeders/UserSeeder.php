<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'user_id' => 1,
                'name' => 'Super Admin',
                'username' => 'admin',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'shift' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 2,
                'name' => 'Muhammad Fikri',
                'username' => 'fikri',
                'password' => Hash::make('fikri'),
                'role' => 'employee',
                'shift' => 'night',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 3,
                'name' => 'Andra',
                'username' => 'andra',
                'password' => Hash::make('andra'),
                'role' => 'employee',
                'shift' => 'morning',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('tbl_users')->insert($users);
    }
}
