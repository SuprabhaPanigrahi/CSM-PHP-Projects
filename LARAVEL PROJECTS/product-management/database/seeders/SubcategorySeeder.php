<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            ['category_id' => 1, 'name' => 'Mobiles'],
            ['category_id' => 1, 'name' => 'Laptops'],
            ['category_id' => 2, 'name' => 'Men'],
            ['category_id' => 2, 'name' => 'Women'],
            ['category_id' => 3, 'name' => 'Kitchen'],
        ];
        foreach ($subcategories as $sub) {
            \App\Models\Subcategory::create($sub);
        }
    }
}
