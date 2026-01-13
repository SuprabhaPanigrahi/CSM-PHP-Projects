<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks (optional but safe)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('tests')->truncate();
        DB::table('categories')->truncate();
        DB::table('departments')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        /*
        |--------------------------------------------------------------------------
        | Departments
        |--------------------------------------------------------------------------
        */
        DB::table('departments')->insert([
            ['id' => 1, 'department_name' => 'Cardiology'],
            ['id' => 2, 'department_name' => 'Neurology'],
            ['id' => 3, 'department_name' => 'Orthopedics'],
            ['id' => 4, 'department_name' => 'Pathology'],
            ['id' => 5, 'department_name' => 'Radiology'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */
        DB::table('categories')->insert([
            ['id' => 1, 'department_id' => 1, 'category_name' => 'ECG Tests'],
            ['id' => 2, 'department_id' => 1, 'category_name' => 'Blood Tests'],
            ['id' => 3, 'department_id' => 2, 'category_name' => 'Imaging'],
            ['id' => 4, 'department_id' => 2, 'category_name' => 'Neuro Tests'],
            ['id' => 5, 'department_id' => 3, 'category_name' => 'Imaging'],
            ['id' => 6, 'department_id' => 3, 'category_name' => 'Physical Tests'],
            ['id' => 7, 'department_id' => 4, 'category_name' => 'Blood Tests'],
            ['id' => 8, 'department_id' => 4, 'category_name' => 'Urine Tests'],
            ['id' => 9, 'department_id' => 5, 'category_name' => 'Imaging'],
            ['id' => 10,'department_id' => 5, 'category_name' => 'Ultrasound'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Tests
        |--------------------------------------------------------------------------
        */
        DB::table('tests')->insert([
            ['id' => 1, 'category_id' => 1, 'test_name' => 'ECG'],
            ['id' => 2, 'category_id' => 1, 'test_name' => 'TMT'],
            ['id' => 3, 'category_id' => 1, 'test_name' => 'Holter Monitoring'],

            ['id' => 4, 'category_id' => 2, 'test_name' => 'Troponin'],
            ['id' => 5, 'category_id' => 2, 'test_name' => 'Lipid Profile'],
            ['id' => 6, 'category_id' => 2, 'test_name' => 'CK-MB'],

            ['id' => 7, 'category_id' => 3, 'test_name' => 'MRI Brain'],
            ['id' => 8, 'category_id' => 3, 'test_name' => 'CT Brain'],

            ['id' => 9, 'category_id' => 4, 'test_name' => 'EEG'],
            ['id' => 10,'category_id' => 4, 'test_name' => 'Nerve Conduction Study'],

            ['id' => 11,'category_id' => 5, 'test_name' => 'X-Ray Knee'],
            ['id' => 12,'category_id' => 5, 'test_name' => 'X-Ray Spine'],

            ['id' => 13,'category_id' => 6, 'test_name' => 'Bone Density Test'],
            ['id' => 14,'category_id' => 6, 'test_name' => 'Joint Mobility Test'],

            ['id' => 15,'category_id' => 7, 'test_name' => 'Complete Blood Count (CBC)'],
            ['id' => 16,'category_id' => 7, 'test_name' => 'ESR'],
            ['id' => 17,'category_id' => 7, 'test_name' => 'Blood Sugar'],

            ['id' => 18,'category_id' => 8, 'test_name' => 'Urine Routine'],
            ['id' => 19,'category_id' => 8, 'test_name' => 'Urine Culture'],

            ['id' => 20,'category_id' => 9, 'test_name' => 'CT Chest'],
            ['id' => 21,'category_id' => 9, 'test_name' => 'MRI Abdomen'],

            ['id' => 22,'category_id' => 10,'test_name' => 'USG Abdomen'],
            ['id' => 23,'category_id' => 10,'test_name' => 'USG Pelvis'],
        ]);
    }
}
