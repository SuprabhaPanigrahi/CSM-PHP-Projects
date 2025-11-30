<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Countries
        $countries = [
            ['id' => 1, 'name' => 'USA'],
            ['id' => 2, 'name' => 'India'],
            ['id' => 3, 'name' => 'UK'],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }

        // Create States
        $states = [
            // USA States
            ['name' => 'California', 'country_id' => 1],
            ['name' => 'Texas', 'country_id' => 1],
            ['name' => 'New York', 'country_id' => 1],
            
            // India States
            ['name' => 'Maharashtra', 'country_id' => 2],
            ['name' => 'Delhi', 'country_id' => 2],
            ['name' => 'Karnataka', 'country_id' => 2],
            
            // UK States
            ['name' => 'London', 'country_id' => 3],
            ['name' => 'Manchester', 'country_id' => 3],
            ['name' => 'Birmingham', 'country_id' => 3],
        ];

        foreach ($states as $state) {
            State::create($state);
        }
    }
}