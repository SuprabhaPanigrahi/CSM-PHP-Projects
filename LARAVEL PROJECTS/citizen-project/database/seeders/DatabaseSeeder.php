<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            StateSeeder::class,
            BlockSeeder::class,
            PanchayatSeeder::class,
            VillageSeeder::class,
            // CitizenSeeder::class, // Add this later if you want sample citizens
        ]);
    }
}