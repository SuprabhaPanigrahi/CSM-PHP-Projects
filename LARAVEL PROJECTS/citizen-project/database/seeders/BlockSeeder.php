<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Block;
use App\Models\State;

class BlockSeeder extends Seeder
{
    public function run()
    {
        $states = State::all();
        
        $blocks = [
            'Maharashtra' => [
                ['BlockName' => 'Pune Block'],
                ['BlockName' => 'Mumbai Block'],
                ['BlockName' => 'Nagpur Block'],
                ['BlockName' => 'Nashik Block'],
            ],
            'Karnataka' => [
                ['BlockName' => 'Bangalore Block'],
                ['BlockName' => 'Mysore Block'],
                ['BlockName' => 'Hubli Block'],
            ],
            'Tamil Nadu' => [
                ['BlockName' => 'Chennai Block'],
                ['BlockName' => 'Coimbatore Block'],
                ['BlockName' => 'Madurai Block'],
            ],
            'Uttar Pradesh' => [
                ['BlockName' => 'Lucknow Block'],
                ['BlockName' => 'Kanpur Block'],
                ['BlockName' => 'Varanasi Block'],
            ],
            'West Bengal' => [
                ['BlockName' => 'Kolkata Block'],
                ['BlockName' => 'Howrah Block'],
                ['BlockName' => 'Darjeeling Block'],
            ],
        ];

        foreach ($states as $state) {
            if (isset($blocks[$state->StateName])) {
                foreach ($blocks[$state->StateName] as $block) {
                    Block::create([
                        'StateId' => $state->StateId,
                        'BlockName' => $block['BlockName']
                    ]);
                }
            }
        }
    }
}