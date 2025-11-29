<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    public function run()
    {
        $states = [
            ['StateName' => 'Maharashtra'],
            ['StateName' => 'Karnataka'],
            ['StateName' => 'Tamil Nadu'],
            ['StateName' => 'Uttar Pradesh'],
            ['StateName' => 'West Bengal'],
        ];

        foreach ($states as $state) {
            State::create($state);
        }
    }
}