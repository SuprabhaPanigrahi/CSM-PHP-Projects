<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Team;
use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectAssignment;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Departments
        $dept1 = Department::create([
            'DepartmentCode' => 'DEV',
            'DepartmentName' => 'Development',
            'Status' => 'Active'
        ]);

        $dept2 = Department::create([
            'DepartmentCode' => 'DES',
            'DepartmentName' => 'Design',
            'Status' => 'Active'
        ]);

        $dept3 = Department::create([
            'DepartmentCode' => 'IT',
            'DepartmentName' => 'IT',
            'Status' => 'Active'
        ]);

        $dept4 = Department::create([
            'DepartmentCode' => 'HR',
            'DepartmentName' => 'HR',
            'Status' => 'Active'
        ]);

        // Create Teams
        $team1 = Team::create([
            'TeamCode' => 'WEB',
            'TeamName' => 'Web Development',
            'DepartmentId' => $dept1->DepartmentId,
            'Status' => 'Active'
        ]);

        $team2 = Team::create([
            'TeamCode' => 'MOB',
            'TeamName' => 'Mobile Development',
            'DepartmentId' => $dept1->DepartmentId,
            'Status' => 'Active'
        ]);

        $team3 = Team::create([
            'TeamCode' => 'UIUX',
            'TeamName' => 'UI/UX Design',
            'DepartmentId' => $dept2->DepartmentId,
            'Status' => 'Active'
        ]);

        $team4 = Team::create([
            'TeamCode' => 'NET',
            'TeamName' => 'Network Team',
            'DepartmentId' => $dept3->DepartmentId,
            'Status' => 'Active'
        ]);

        $team5 = Team::create([
            'TeamCode' => 'REC',
            'TeamName' => 'Recruitment',
            'DepartmentId' => $dept4->DepartmentId,
            'Status' => 'Active'
        ]);

        // Create Employees - More Data Added
        $employees = [
            [
                'EmployeeCode' => 'EMP001',
                'FullName' => 'Suprabha Panigrahi',
                'Email' => 'suprabha.panigrahi@company.com',
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP002',
                'FullName' => 'Rameswar Patro',
                'Email' => 'rameswar@company.com',
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP003',
                'FullName' => 'Ipsita Mohanty',
                'Email' => 'ipsita.mohanty@company.com',
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP004',
                'FullName' => 'Asha Sahu',
                'Email' => 'asha.sahu@company.com',
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP005',
                'FullName' => 'Naresh Patro',
                'Email' => 'naresh.patro@company.com',
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP006',
                'FullName' => 'Amit Behera',
                'Email' => 'amit.behera@company.com',
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP007',
                'FullName' => 'Subhakanta Panda',
                'Email' => 'subhakanta.panda@company.com',
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP008',
                'FullName' => 'Minakshi Barik',
                'Email' => 'minakshi.barik@company.com',
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP009',
                'FullName' => 'Samikshya Mishra',
                'Email' => 'samikshya.mishra@company.com',
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP010',
                'FullName' => 'Subhashree Dash',
                'Email' => 'subhashree.dash@company.com',
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP011',
                'FullName' => 'Sesdev Patro',
                'Email' => 'sesdev.patro@company.com',
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP012',
                'FullName' => 'Pritam Kumar',
                'Email' => 'pritam.kumar@company.com',
                'IsActive' => true
            ]
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }

        // Create Projects - More Data Added
        $projects = [
            [
                'ProjectCode' => 'WEB001',
                'ProjectName' => 'Company Website',
                'TeamId' => $team1->TeamId,
                'IsBillable' => true,
                'Status' => 'Active',
                'StartDate' => '2024-01-01',
                'EndDate' => '2024-12-31'
            ],
            [
                'ProjectCode' => 'WEB002',
                'ProjectName' => 'E-commerce Platform',
                'TeamId' => $team1->TeamId,
                'IsBillable' => true,
                'Status' => 'Active',
                'StartDate' => '2024-02-01',
                'EndDate' => '2024-11-30'
            ],
            [
                'ProjectCode' => 'MOB001',
                'ProjectName' => 'Mobile App',
                'TeamId' => $team2->TeamId,
                'IsBillable' => true,
                'Status' => 'Active',
                'StartDate' => '2024-02-01',
                'EndDate' => '2024-11-30'
            ],
            [
                'ProjectCode' => 'MOB002',
                'ProjectName' => 'Banking App',
                'TeamId' => $team2->TeamId,
                'IsBillable' => true,
                'Status' => 'Active',
                'StartDate' => '2024-03-01',
                'EndDate' => '2024-10-31'
            ],
            [
                'ProjectCode' => 'DES001',
                'ProjectName' => 'Design System',
                'TeamId' => $team3->TeamId,
                'IsBillable' => false, // Not billable
                'Status' => 'Active',
                'StartDate' => '2024-03-01',
                'EndDate' => '2024-10-31'
            ],
            [
                'ProjectCode' => 'DES002',
                'ProjectName' => 'Client Branding',
                'TeamId' => $team3->TeamId,
                'IsBillable' => true,
                'Status' => 'Active',
                'StartDate' => '2024-04-01',
                'EndDate' => '2024-09-30'
            ],
            [
                'ProjectCode' => 'NET001',
                'ProjectName' => 'Network Infrastructure',
                'TeamId' => $team4->TeamId,
                'IsBillable' => true,
                'Status' => 'Active',
                'StartDate' => '2024-01-15',
                'EndDate' => '2024-12-15'
            ],
            [
                'ProjectCode' => 'HR001',
                'ProjectName' => 'HR Portal',
                'TeamId' => $team5->TeamId,
                'IsBillable' => false, // Internal project
                'Status' => 'Active',
                'StartDate' => '2024-02-01',
                'EndDate' => '2024-08-31'
            ]
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        // Create some Project Assignments to test the CRUD
        $projectAssignments = [
            [
                'ProjectId' => 1, // Company Website
                'EmployeeId' => 1, // John Doe
                'RoleOnProject' => 'Developer',
                'AllocationPercent' => 80.00,
                'StartDate' => '2024-01-01',
                'EndDate' => '2024-06-30',
                'Status' => 'Active'
            ],
            [
                'ProjectId' => 2, // E-commerce Platform
                'EmployeeId' => 2, // Jane Smith
                'RoleOnProject' => 'Designer',
                'AllocationPercent' => 60.00,
                'StartDate' => '2024-02-01',
                'EndDate' => '2024-07-31',
                'Status' => 'Active'
            ],
            [
                'ProjectId' => 3, // Mobile App
                'EmployeeId' => 3, // Mike Johnson
                'RoleOnProject' => 'Developer',
                'AllocationPercent' => 100.00,
                'StartDate' => '2024-02-01',
                'EndDate' => '2024-05-31',
                'Status' => 'Active'
            ],
            [
                'ProjectId' => 4, // Banking App
                'EmployeeId' => 4, // Sarah Wilson
                'RoleOnProject' => 'Manager',
                'AllocationPercent' => 50.00,
                'StartDate' => '2024-03-01',
                'EndDate' => '2024-08-31',
                'Status' => 'Active'
            ],
            [
                'ProjectId' => 6, // Client Branding
                'EmployeeId' => 5, // David Brown
                'RoleOnProject' => 'Designer',
                'AllocationPercent' => 75.00,
                'StartDate' => '2024-04-01',
                'EndDate' => '2024-07-31',
                'Status' => 'Active'
            ]
        ];

        foreach ($projectAssignments as $assignment) {
            ProjectAssignment::create($assignment);
        }
    }
}