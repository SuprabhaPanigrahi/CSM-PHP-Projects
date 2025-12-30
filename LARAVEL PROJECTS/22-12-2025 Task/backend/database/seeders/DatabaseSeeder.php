<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. FIRST: Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

        // 2. Clear existing users (optional - prevents duplicates)
        User::truncate();
        
        // 3. Create Admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        $admin->assignRole($adminRole);

        // 4. Create Manager user
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'manager'
        ]);
        $manager->assignRole($managerRole);

        // 5. Create Employee users
        $employees = [];
        for ($i = 1; $i <= 5; $i++) {
            $employee = User::create([
                'name' => "Employee $i",
                'email' => "employee$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'employee'
            ]);
            $employee->assignRole($employeeRole);
            $employees[] = $employee;
        }

        // 6. Create Team
        Team::truncate(); // Clear existing teams
        $team = Team::create([
            'name' => 'Development Team',
            'manager_id' => $manager->id
        ]);

        // 7. Add employees to team
        $team->members()->attach(collect($employees)->pluck('id'));

        // 8. Create Tasks
        Task::truncate(); // Clear existing tasks
        Task::create([
            'title' => 'Fix login bug',
            'description' => 'Users cannot login with correct credentials',
            'priority' => 'high',
            'due_date' => now()->addDays(2),
            'status' => 'todo',
            'assigned_to' => $employees[0]->id,
            'created_by' => $manager->id
        ]);

        Task::create([
            'title' => 'Update documentation',
            'description' => 'Update API documentation',
            'priority' => 'medium',
            'due_date' => now()->addDays(5),
            'status' => 'in_progress',
            'assigned_to' => $employees[1]->id,
            'created_by' => $admin->id
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('👑 Admin: admin@example.com / password');
        $this->command->info('👔 Manager: manager@example.com / password');
        $this->command->info('👤 Employees: employee1@example.com / password (1-5)');
    }
}