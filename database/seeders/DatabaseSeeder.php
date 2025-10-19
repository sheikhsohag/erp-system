<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use App\Models\ApprovalRule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create departments
        $hr = Department::create(['name' => 'HR']);
        $finance = Department::create(['name' => 'Finance']);
        $it = Department::create(['name' => 'IT']);

        // Create users for HR department
        $hrManager = User::create([
            'name' => 'HR Manager',
            'email' => 'hr_manager@company.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'department_id' => $hr->id,
            'is_on_leave' => false,
        ]);

        $hrHead = User::create([
            'name' => 'HR Head',
            'email' => 'hr_head@company.com',
            'password' => Hash::make('password'),
            'role' => 'head',
            'department_id' => $hr->id,
            'is_on_leave' => false,
        ]);

        $hrDirector = User::create([
            'name' => 'HR Director',
            'email' => 'hr_director@company.com',
            'password' => Hash::make('password'),
            'role' => 'director',
            'department_id' => $hr->id,
            'is_on_leave' => false,
        ]);

        // Create regular employee
        $hrEmployee = User::create([
            'name' => 'HR Employee',
            'email' => 'hr_employee@company.com',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'department_id' => $hr->id,
            'is_on_leave' => false,
        ]);

        // Create approval rules for HR
        ApprovalRule::create([
            'department_id' => $hr->id,
            'min_amount' => 0,
            'max_amount' => 10000,
            'approval_order' => ['manager', 'head']
        ]);

        ApprovalRule::create([
            'department_id' => $hr->id,
            'min_amount' => 10001,
            'max_amount' => 50000,
            'approval_order' => ['manager', 'head', 'director']
        ]);

        ApprovalRule::create([
            'department_id' => $hr->id,
            'min_amount' => 50001,
            'max_amount' => 100000,
            'approval_order' => ['head', 'director']
        ]);
    }
}
