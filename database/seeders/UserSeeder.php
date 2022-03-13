<?php

namespace Database\Seeders;

use DB;
use Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Kelly Slater',
            'email' => 'kelly@example.com',
            'password' => Hash::make('password'),
            'created_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
            'updated_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'name' => 'Jordy Smith',
            'email' => 'jordy@example.com',
            'password' => Hash::make('password'),
            'created_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
            'updated_at' => \Carbon\Carbon::createMidnightDate(2018, 7, 1),
        ]);

    }
}
