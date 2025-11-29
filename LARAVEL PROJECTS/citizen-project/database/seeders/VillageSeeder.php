<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Village;
use App\Models\Panchayat;

class VillageSeeder extends Seeder
{
    public function run()
    {
        $panchayats = Panchayat::all();
        
        $villageNames = [
            'North', 'South', 'East', 'West', 'Central', 
            'Old', 'New', 'Upper', 'Lower', 'Main',
            'Rural', 'Urban', 'Hill', 'Valley', 'Riverside'
        ];

        foreach ($panchayats as $panchayat) {
            // Create at least 2 villages for every panchayat
            $numberOfVillages = rand(2, 4); // 2 to 4 villages per panchayat
            
            for ($i = 1; $i <= $numberOfVillages; $i++) {
                $villageName = $panchayat->PanchayatName . ' ' . $villageNames[array_rand($villageNames)] . ' Village';
                
                // Ensure unique village names within panchayat
                $villageName = $this->makeUniqueVillageName($panchayat, $villageName, $i);
                
                Village::create([
                    'PanchayatId' => $panchayat->PanchayatId,
                    'VillageName' => $villageName
                ]);
            }
        }
    }

    private function makeUniqueVillageName($panchayat, $baseName, $index)
    {
        $suffixes = ['', ' I', ' II', ' III', ' A', ' B', ' C'];
        $attempt = 0;
        
        while ($attempt < count($suffixes)) {
            $villageName = $baseName . $suffixes[$attempt];
            
            $exists = Village::where('PanchayatId', $panchayat->PanchayatId)
                           ->where('VillageName', $villageName)
                           ->exists();
            
            if (!$exists) {
                return $villageName;
            }
            
            $attempt++;
        }
        
        // If all attempts fail, add a random number
        return $baseName . ' ' . rand(100, 999);
    }
}