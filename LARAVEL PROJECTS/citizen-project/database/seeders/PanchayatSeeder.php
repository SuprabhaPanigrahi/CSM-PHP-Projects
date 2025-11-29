<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Panchayat;
use App\Models\Block;

class PanchayatSeeder extends Seeder
{
    public function run()
    {
        $blocks = Block::all();
        
        $panchayats = [
            'Pune Block' => [
                ['PanchayatName' => 'Pune Panchayat A'],
                ['PanchayatName' => 'Pune Panchayat B'],
                ['PanchayatName' => 'Pune Panchayat C'],
            ],
            'Mumbai Block' => [
                ['PanchayatName' => 'Mumbai Panchayat A'],
                ['PanchayatName' => 'Mumbai Panchayat B'],
            ],
            'Nagpur Block' => [
                ['PanchayatName' => 'Nagpur Panchayat A'],
                ['PanchayatName' => 'Nagpur Panchayat B'],
            ],
            'Nashik Block' => [
                ['PanchayatName' => 'Nashik Panchayat A'],
                ['PanchayatName' => 'Nashik Panchayat B'],
            ],
            'Bangalore Block' => [
                ['PanchayatName' => 'Bangalore Panchayat A'],
                ['PanchayatName' => 'Bangalore Panchayat B'],
            ],
            'Mysore Block' => [
                ['PanchayatName' => 'Mysore Panchayat A'],
                ['PanchayatName' => 'Mysore Panchayat B'],
            ],
            'Hubli Block' => [
                ['PanchayatName' => 'Hubli Panchayat A'],
                ['PanchayatName' => 'Hubli Panchayat B'],
            ],
            'Chennai Block' => [
                ['PanchayatName' => 'Chennai Panchayat A'],
                ['PanchayatName' => 'Chennai Panchayat B'],
            ],
            'Coimbatore Block' => [
                ['PanchayatName' => 'Coimbatore Panchayat A'],
                ['PanchayatName' => 'Coimbatore Panchayat B'],
            ],
            'Madurai Block' => [
                ['PanchayatName' => 'Madurai Panchayat A'],
                ['PanchayatName' => 'Madurai Panchayat B'],
            ],
            'Lucknow Block' => [
                ['PanchayatName' => 'Lucknow Panchayat A'],
                ['PanchayatName' => 'Lucknow Panchayat B'],
            ],
            'Kanpur Block' => [
                ['PanchayatName' => 'Kanpur Panchayat A'],
                ['PanchayatName' => 'Kanpur Panchayat B'],
            ],
            'Varanasi Block' => [
                ['PanchayatName' => 'Varanasi Panchayat A'],
                ['PanchayatName' => 'Varanasi Panchayat B'],
            ],
            'Kolkata Block' => [
                ['PanchayatName' => 'Kolkata Panchayat A'],
                ['PanchayatName' => 'Kolkata Panchayat B'],
            ],
            'Howrah Block' => [
                ['PanchayatName' => 'Howrah Panchayat A'],
                ['PanchayatName' => 'Howrah Panchayat B'],
            ],
            'Darjeeling Block' => [
                ['PanchayatName' => 'Darjeeling Panchayat A'],
                ['PanchayatName' => 'Darjeeling Panchayat B'],
            ],
        ];

        foreach ($blocks as $block) {
            if (isset($panchayats[$block->BlockName])) {
                foreach ($panchayats[$block->BlockName] as $panchayat) {
                    Panchayat::create([
                        'BlockId' => $block->BlockId,
                        'PanchayatName' => $panchayat['PanchayatName']
                    ]);
                }
            }
        }
    }
}