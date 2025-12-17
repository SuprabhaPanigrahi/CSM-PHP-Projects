<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Salary;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $employees = [
            [
                'emp_name' => 'John Smith',
                'emp_code' => 'EMP001',
                'emp_email' => 'john@example.com',
                'emp_phone' => '1234567890',
                'joining_date' => '2023-01-15',
                'department' => [
                    'dept_name' => 'IT Department',
                    'dept_head' => 'Michael Brown',
                    'location' => 'New York'
                ],
                'salary' => [
                    'basic_salary' => 50000,
                    'allowances' => 10000,
                    'deductions' => 5000,
                    'net_salary' => 55000,
                    'payment_date' => '2024-01-30'
                ]
            ],
            [
                'emp_name' => 'Sarah Johnson',
                'emp_code' => 'EMP002',
                'emp_email' => 'sarah@example.com',
                'emp_phone' => '2345678901',
                'joining_date' => '2023-03-20',
                'department' => [
                    'dept_name' => 'HR Department',
                    'dept_head' => 'David Wilson',
                    'location' => 'London'
                ],
                'salary' => [
                    'basic_salary' => 45000,
                    'allowances' => 8000,
                    'deductions' => 3000,
                    'net_salary' => 50000,
                    'payment_date' => '2024-01-30'
                ]
            ],
            [
                'emp_name' => 'Robert Davis',
                'emp_code' => 'EMP003',
                'emp_email' => 'robert@example.com',
                'emp_phone' => '3456789012',
                'joining_date' => '2023-06-10',
                'department' => [
                    'dept_name' => 'Finance Department',
                    'dept_head' => 'Lisa Taylor',
                    'location' => 'Tokyo'
                ],
                'salary' => [
                    'basic_salary' => 60000,
                    'allowances' => 12000,
                    'deductions' => 7000,
                    'net_salary' => 65000,
                    'payment_date' => '2024-01-30'
                ]
            ]
        ];

        foreach ($employees as $empData) {
            $employee = Employee::create([
                'emp_name' => $empData['emp_name'],
                'emp_code' => $empData['emp_code'],
                'emp_email' => $empData['emp_email'],
                'emp_phone' => $empData['emp_phone'],
                'joining_date' => $empData['joining_date']
            ]);

            Department::create([
                'emp_id' => $employee->id,
                'dept_name' => $empData['department']['dept_name'],
                'dept_head' => $empData['department']['dept_head'],
                'location' => $empData['department']['location']
            ]);

            Salary::create([
                'emp_id' => $employee->id,
                'basic_salary' => $empData['salary']['basic_salary'],
                'allowances' => $empData['salary']['allowances'],
                'deductions' => $empData['salary']['deductions'],
                'net_salary' => $empData['salary']['net_salary'],
                'payment_date' => $empData['salary']['payment_date']
            ]);
        }
    }
}