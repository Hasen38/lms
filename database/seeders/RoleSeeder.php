<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Define required permissions
        $permissions = [
            'grade.view_any',
            'grade.view',
            'grade.create',
            'grade.update',
            'grade.delete',
            'enrollment.view_any',
            'enrollment.view',
            'enrollment.create',
            'enrollment.update',
            'enrollment.delete',
            'user.view_any',
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'student.view_any',
            'student.view',
            'student.create',
            'student.update',
            'student.delete',
            'subject.view_any',
            'subject.view',
            'subject.create',
            'subject.update',
            'subject.delete',
            'teacher.view_any',
            'teacher.view',
            'teacher.create',
            'teacher.update',
            'teacher.delete',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Create roles

        $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // Assign permissions


        if (!$superAdmin->permissions()->exists()) {
            $superAdmin->givePermissionTo(Permission::all());
        }

        $teacherPermissions = [
            'grade.view_any',
            'grade.view',
            'grade.create',
            'grade.update',
            'grade.delete',
            'enrollment.view_any',
            'enrollment.view',
        ];
        if (!$teacher->permissions()->whereIn('name', $teacherPermissions)->exists()) {
            $teacher->givePermissionTo($teacherPermissions);
        }

        $studentPermissions = ['grade.view_any', 'grade.view'];
        if (!$student->permissions()->whereIn('name', $studentPermissions)->exists()) {
            $student->givePermissionTo($studentPermissions);
        }
    }
}
