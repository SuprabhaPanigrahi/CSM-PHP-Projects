<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create in-memory users
        DB::table('users')->insert([
            [
                'username' => 'jsmith',
                'password' => Hash::make('epl42$'),
                'role' => 'EMPLOYEE'
            ],
            [
                'username' => 'atrevor',
                'password' => Hash::make('letmein'),
                'role' => 'MANAGER'
            ],
            [
                'username' => 'dalves',
                'password' => Hash::make('secure'),
                'role' => 'MANAGER'
            ]
        ]);

        // Create some sample employees
        DB::table('employees')->insert([
            [
                'firstname' => 'John',
                'lastname' => 'Smith',
                'department' => 'Computer Science',
                'date_of_birth' => '1997-03-07'
            ],
            [
                'firstname' => 'Simon',
                'lastname' => 'Brown',
                'department' => 'Linguistics',
                'date_of_birth' => '1999-01-23'
            ]
        ]);
    }
}