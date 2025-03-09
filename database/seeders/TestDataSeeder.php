<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'grade.view_any', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'grade.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'grade.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'grade.update', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'grade.delete', 'guard_name' => 'web']);

        $superAdminRole->givePermissionTo(Permission::all());
        $teacherRole->givePermissionTo(['grade.view_any', 'grade.view', 'grade.create', 'grade.update', 'grade.delete']);
        $studentRole->givePermissionTo(['grade.view_any', 'grade.view']);

        $superAdmin = User::create(['name' => 'Super Admin User', 'email' => 'superadmin@test.com', 'password' => bcrypt('password')]);
        $teacher = User::create(['name' => 'Teacher User', 'email' => 'teacher@test.com', 'password' => bcrypt('password')]);
        $student = User::create(['name' => 'Student User', 'email' => 'student@test.com', 'password' => bcrypt('password')]);

        $superAdmin->assignRole('super_admin');
        $teacher->assignRole('teacher');
        $student->assignRole('student');
    }
}
