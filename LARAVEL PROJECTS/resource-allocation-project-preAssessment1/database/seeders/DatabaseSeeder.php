<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Clear tables first
        DB::table('employee_project_allocations')->delete();
        DB::table('employee_skills')->delete();
        DB::table('employees')->delete();
        DB::table('projects')->delete();
        DB::table('teams')->delete();
        DB::table('departments')->delete();
        DB::table('business_units')->delete();
        DB::table('technologies')->delete();
        DB::table('countries')->delete();
        DB::table('employee_status')->delete();

        // Seed Employee Status
        DB::table('employee_status')->insert([
            ['StatusName' => 'Available'],
            ['StatusName' => 'Allocated'],
            ['StatusName' => 'On Leave'],
        ]);

        // Seed Countries
        DB::table('countries')->insert([
            ['CountryName' => 'United States', 'CountryCode' => 'US'],
            ['CountryName' => 'United Kingdom', 'CountryCode' => 'UK'],
            ['CountryName' => 'India', 'CountryCode' => 'IN'],
            ['CountryName' => 'Canada', 'CountryCode' => 'CA'],
            ['CountryName' => 'Australia', 'CountryCode' => 'AU'],
        ]);

        // Seed Technologies
        DB::table('technologies')->insert([
            ['Name' => '.NET', 'Code' => 'DOTNET'],
            ['Name' => 'Java', 'Code' => 'JAVA'],
            ['Name' => 'Python', 'Code' => 'PYTHON'],
            ['Name' => 'SAP', 'Code' => 'SAP'],
            ['Name' => 'PHP', 'Code' => 'PHP'],
            ['Name' => 'JavaScript', 'Code' => 'JS'],
        ]);

        // Seed Business Units
        DB::table('business_units')->insert([
            ['Name' => 'Digital', 'Code' => 'DIG', 'IsActive' => true],
            ['Name' => 'ERP', 'Code' => 'ERP', 'IsActive' => true],
            ['Name' => 'Analytics', 'Code' => 'ANA', 'IsActive' => true],
            ['Name' => 'GIS', 'Code' => 'GIS', 'IsActive' => true],
        ]);

        // Seed Departments
        DB::table('departments')->insert([
            ['BusinessUnitId' => 1, 'Name' => 'Web Development', 'Code' => 'WEB', 'IsActive' => true],
            ['BusinessUnitId' => 1, 'Name' => 'Mobile Development', 'Code' => 'MOB', 'IsActive' => true],
            ['BusinessUnitId' => 2, 'Name' => 'SAP Implementation', 'Code' => 'SAP', 'IsActive' => true],
            ['BusinessUnitId' => 3, 'Name' => 'Data Science', 'Code' => 'DS', 'IsActive' => true],
            ['BusinessUnitId' => 4, 'Name' => 'Mapping Services', 'Code' => 'MAP', 'IsActive' => true],
        ]);

        // Seed Teams
        DB::table('teams')->insert([
            ['DepartmentId' => 1, 'Name' => 'Frontend Team', 'Code' => 'FE', 'IsActive' => true],
            ['DepartmentId' => 1, 'Name' => 'Backend Team', 'Code' => 'BE', 'IsActive' => true],
            ['DepartmentId' => 2, 'Name' => 'iOS Team', 'Code' => 'IOS', 'IsActive' => true],
            ['DepartmentId' => 2, 'Name' => 'Android Team', 'Code' => 'AND', 'IsActive' => true],
            ['DepartmentId' => 3, 'Name' => 'SAP Team A', 'Code' => 'SAPA', 'IsActive' => true],
            ['DepartmentId' => 4, 'Name' => 'Analytics Team', 'Code' => 'ANL', 'IsActive' => true],
        ]);

        // Seed Employees
        DB::table('employees')->insert([
            [
                'EmployeeCode' => 'EMP001', 
                'FullName' => 'John Smith', 
                'TeamId' => 1, 
                'EmployeeStatusId' => 1, // Available
                'YearsOfExperience' => 3.0,
                'WorkLocationCountryId' => 1, // US
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP002', 
                'FullName' => 'Jane Doe', 
                'TeamId' => 1, 
                'EmployeeStatusId' => 1,
                'YearsOfExperience' => 1.5,
                'WorkLocationCountryId' => 2, // UK
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP003', 
                'FullName' => 'Mike Johnson', 
                'TeamId' => 2, 
                'EmployeeStatusId' => 1,
                'YearsOfExperience' => 4.0,
                'WorkLocationCountryId' => 3, // India
                'IsActive' => true
            ],
            [
                'EmployeeCode' => 'EMP004', 
                'FullName' => 'Sarah Wilson', 
                'TeamId' => 2, 
                'EmployeeStatusId' => 1,
                'YearsOfExperience' => 2.5,
                'WorkLocationCountryId' => 1, // US
                'IsActive' => true
            ],
        ]);

        // Seed Employee Skills
        DB::table('employee_skills')->insert([
            ['EmployeeId' => 1, 'TechnologyId' => 1, 'IsPrimarySkill' => true, 'SkillLevel' => 'Expert'], // .NET
            ['EmployeeId' => 2, 'TechnologyId' => 2, 'IsPrimarySkill' => true, 'SkillLevel' => 'Intermediate'], // Java
            ['EmployeeId' => 3, 'TechnologyId' => 3, 'IsPrimarySkill' => true, 'SkillLevel' => 'Expert'], // Python
            ['EmployeeId' => 4, 'TechnologyId' => 1, 'IsPrimarySkill' => true, 'SkillLevel' => 'Advanced'], // .NET
        ]);

        // Seed Projects
        DB::table('projects')->insert([
            [
                'ProjectCode' => 'PROJ001',
                'ProjectName' => 'E-commerce Website',
                'ProjectType' => 'Billable',
                'Priority' => 'High',
                'LocationType' => 'Onsite',
                'LocationCountryId' => 1, // US
                'TechnologyId' => 1, // .NET
                'StartDate' => '2024-01-01',
                'EndDate' => '2024-06-30',
                'IsActive' => true
            ],
            [
                'ProjectCode' => 'PROJ002',
                'ProjectName' => 'Mobile Banking App',
                'ProjectType' => 'Billable',
                'Priority' => 'Normal',
                'LocationType' => 'Offshore',
                'LocationCountryId' => null,
                'TechnologyId' => 2, // Java
                'StartDate' => '2024-02-01',
                'EndDate' => '2024-08-31',
                'IsActive' => true
            ],
        ]);

        // Seed some allocations for testing
        DB::table('employee_project_allocations')->insert([
            [
                'EmployeeId' => 1,
                'ProjectId' => 1,
                'AllocationStartDate' => '2024-01-01',
                'AllocationEndDate' => '2024-03-31',
                'AllocationPercentage' => 100,
                'IsActive' => true
            ],
        ]);
    }
}