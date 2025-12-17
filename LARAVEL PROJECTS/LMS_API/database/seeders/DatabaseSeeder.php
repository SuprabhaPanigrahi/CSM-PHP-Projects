<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create employees
        $employee1 = Employee::create([
            'firstname' => 'John',
            'lastname' => 'Smith',
            'department' => 'Computer Science',
            'date_of_birth' => '1997-03-07'
        ]);
        
        $employee2 = Employee::create([
            'firstname' => 'Simon',
            'lastname' => 'Brown',
            'department' => 'Mathematics',
            'date_of_birth' => '1999-01-23'
        ]);
        
        // Create leaves
        Leave::create([
            'employee_id' => $employee1->id,
            'description' => 'Sick leave',
            'start_date' => '2023-04-10',
            'end_date' => '2023-04-12',
            'approved' => false
        ]);
        
        Leave::create([
            'employee_id' => $employee1->id,
            'description' => 'Vacation',
            'start_date' => '2023-05-01',
            'end_date' => '2023-05-10',
            'approved' => true
        ]);
    }
}