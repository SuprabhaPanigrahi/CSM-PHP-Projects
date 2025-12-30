<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CompleteSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Role::truncate();
        Team::truncate();
        Task::truncate();
        \Illuminate\Support\Facades\DB::table('team_user')->truncate();
        \Illuminate\Support\Facades\DB::table('model_has_roles')->truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create roles
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'web']);
        $employeeRole = Role::create(['name' => 'employee', 'guard_name' => 'web']);

        // Create users
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@task.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        $admin->assignRole($adminRole);

        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@task.com',
            'password' => Hash::make('password'),
            'role' => 'manager'
        ]);
        $manager->assignRole($managerRole);

        // Create employees
        $employees = [];
        for ($i = 1; $i <= 3; $i++) {
            $emp = User::create([
                'name' => "Employee $i",
                'email' => "employee$i@task.com",
                'password' => Hash::make('password'),
                'role' => 'employee'
            ]);
            $emp->assignRole($employeeRole);
            $employees[] = $emp;
        }

        // Create team
        $team = Team::create([
            'name' => 'Development Team',
            'manager_id' => $manager->id
        ]);
        $team->members()->attach($employees);

        // Create tasks
        Task::create([
            'title' => 'Fix Login Bug',
            'description' => 'Users reporting login issues',
            'priority' => 'high',
            'due_date' => now()->addDays(3),
            'status' => 'todo',
            'assigned_to' => $employees[0]->id,
            'created_by' => $manager->id
        ]);

        Task::create([
            'title' => 'Update Documentation',
            'description' => 'Document new features',
            'priority' => 'medium',
            'due_date' => now()->addDays(7),
            'status' => 'in_progress',
            'assigned_to' => $employees[1]->id,
            'created_by' => $admin->id
        ]);

        echo "✅ Seeding completed successfully!\n";
        echo "👑 Admin: admin@task.com / password\n";
        echo "👔 Manager: manager@task.com / password\n";
        echo "👤 Employee: employee1@task.com / password\n";
    }
}