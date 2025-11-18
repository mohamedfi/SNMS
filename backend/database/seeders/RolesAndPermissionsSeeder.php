<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = $this->getPermissions();

        foreach ($permissions as $module => $modulePermissions) {
            foreach ($modulePermissions as $permission) {
                Permission::firstOrCreate(
                    ['name' => $permission],
                    [
                        'module' => $module,
                        'description' => ucfirst(str_replace('_', ' ', $permission))
                    ]
                );
            }
        }

        // Create roles
        $roles = $this->getRoles();

        foreach ($roles as $roleName => $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description']
                ]
            );

            // Assign permissions to role
            if ($roleName === 'super_admin') {
                // Super admin gets all permissions
                $role->permissions()->sync(Permission::all()->pluck('id'));
            } else {
                $rolePermissions = $roleData['permissions'];
                $permissionIds = Permission::whereIn('name', $rolePermissions)->pluck('id');
                $role->permissions()->sync($permissionIds);
            }
        }

        $this->command->info('Roles and permissions seeded successfully!');
    }

    /**
     * Get all permissions grouped by module.
     */
    private function getPermissions(): array
    {
        return [
            'students' => [
                'students.view_any',
                'students.view',
                'students.create',
                'students.update',
                'students.delete',
                'students.restore',
                'students.upload_photo',
                'students.upload_documents',
                'students.export',
            ],
            'guardians' => [
                'guardians.view_any',
                'guardians.view',
                'guardians.create',
                'guardians.update',
                'guardians.delete',
            ],
            'classes' => [
                'classes.view_any',
                'classes.view',
                'classes.create',
                'classes.update',
                'classes.delete',
                'classes.assign_teacher',
                'classes.assign_students',
            ],
            'attendance' => [
                'attendance.students.view_any',
                'attendance.students.view',
                'attendance.students.mark',
                'attendance.students.update',
                'attendance.students.export',
                'attendance.teachers.view_any',
                'attendance.teachers.view',
                'attendance.teachers.mark',
                'attendance.teachers.update',
                'attendance.teachers.export',
            ],
            'evaluations' => [
                'evaluations.templates.view_any',
                'evaluations.templates.create',
                'evaluations.templates.update',
                'evaluations.templates.delete',
                'evaluations.view_any',
                'evaluations.view',
                'evaluations.create',
                'evaluations.update',
                'evaluations.delete',
                'evaluations.publish',
                'evaluations.upload_media',
                'evaluations.export',
            ],
            'events' => [
                'events.view_any',
                'events.view',
                'events.create',
                'events.update',
                'events.delete',
                'events.register_student',
                'events.manage_permissions',
                'events.mark_attendance',
                'events.export',
            ],
            'admissions' => [
                'admissions.view_any',
                'admissions.view',
                'admissions.submit',
                'admissions.update_status',
                'admissions.schedule_interview',
                'admissions.approve',
                'admissions.reject',
                'admissions.convert_to_student',
                'admissions.export',
            ],
            'employees' => [
                'employees.view_any',
                'employees.view',
                'employees.create',
                'employees.update',
                'employees.delete',
                'employees.upload_documents',
                'employees.export',
            ],
            'leaves' => [
                'leaves.view_any',
                'leaves.view',
                'leaves.create',
                'leaves.update',
                'leaves.cancel',
                'leaves.approve',
                'leaves.reject',
            ],
            'payroll' => [
                'payroll.view_any',
                'payroll.view',
                'payroll.generate',
                'payroll.approve',
                'payroll.mark_paid',
                'payroll.download_payslip',
                'payroll.export',
            ],
            'invoices' => [
                'invoices.view_any',
                'invoices.view',
                'invoices.create',
                'invoices.update',
                'invoices.delete',
                'invoices.send',
                'invoices.cancel',
                'invoices.export',
            ],
            'payments' => [
                'payments.view_any',
                'payments.view',
                'payments.create',
                'payments.update',
                'payments.refund',
                'payments.export',
            ],
            'expenses' => [
                'expenses.view_any',
                'expenses.view',
                'expenses.create',
                'expenses.update',
                'expenses.delete',
                'expenses.approve',
                'expenses.export',
            ],
            'inventory' => [
                'inventory.view_any',
                'inventory.view',
                'inventory.create',
                'inventory.update',
                'inventory.delete',
                'inventory.record_movement',
                'inventory.export',
            ],
            'assets' => [
                'assets.view_any',
                'assets.view',
                'assets.create',
                'assets.update',
                'assets.delete',
                'assets.assign',
                'assets.export',
            ],
            'messages' => [
                'messages.view_any',
                'messages.view',
                'messages.create',
                'messages.delete',
            ],
            'announcements' => [
                'announcements.view_any',
                'announcements.view',
                'announcements.create',
                'announcements.update',
                'announcements.delete',
                'announcements.publish',
            ],
            'notifications' => [
                'notifications.view',
                'notifications.mark_read',
                'notifications.delete',
            ],
            'reports' => [
                'reports.dashboard',
                'reports.students',
                'reports.attendance',
                'reports.evaluations',
                'reports.financial',
                'reports.hr',
                'reports.export',
            ],
            'settings' => [
                'settings.view',
                'settings.update',
            ],
            'users' => [
                'users.view_any',
                'users.view',
                'users.create',
                'users.update',
                'users.delete',
                'users.assign_roles',
                'users.reset_password',
            ],
            'roles' => [
                'roles.view_any',
                'roles.create',
                'roles.update',
                'roles.delete',
                'permissions.view_any',
                'permissions.assign',
            ],
        ];
    }

    /**
     * Get role definitions with their permissions.
     */
    private function getRoles(): array
    {
        return [
            'super_admin' => [
                'display_name' => 'Super Administrator',
                'description' => 'Has full access to all system features',
                'permissions' => [], // Gets all permissions automatically
            ],
            'admin' => [
                'display_name' => 'Administrator',
                'description' => 'Manages daily operations',
                'permissions' => [
                    // Students
                    'students.view_any', 'students.view', 'students.create', 'students.update',
                    'students.upload_photo', 'students.upload_documents', 'students.export',
                    // Guardians
                    'guardians.view_any', 'guardians.view', 'guardians.create', 'guardians.update',
                    // Classes
                    'classes.view_any', 'classes.view', 'classes.create', 'classes.update',
                    'classes.assign_teacher', 'classes.assign_students',
                    // Attendance
                    'attendance.students.view_any', 'attendance.students.view', 'attendance.students.mark',
                    'attendance.students.update', 'attendance.students.export',
                    'attendance.teachers.view_any', 'attendance.teachers.view', 'attendance.teachers.mark',
                    // Evaluations
                    'evaluations.templates.view_any', 'evaluations.templates.create',
                    'evaluations.view_any', 'evaluations.view', 'evaluations.create', 'evaluations.update',
                    'evaluations.publish', 'evaluations.upload_media',
                    // Events, Admissions, Communication
                    'events.view_any', 'events.create', 'events.update',
                    'admissions.view_any', 'admissions.view', 'admissions.update_status', 'admissions.approve',
                    'announcements.view_any', 'announcements.create', 'announcements.publish',
                    'messages.view', 'messages.create',
                    // Reports
                    'reports.dashboard', 'reports.students', 'reports.attendance',
                    // Users
                    'users.view_any', 'users.view', 'users.create', 'users.update', 'users.assign_roles',
                ],
            ],
            'teacher' => [
                'display_name' => 'Teacher',
                'description' => 'Handles class activities, attendance, and evaluation',
                'permissions' => [
                    'students.view_any', 'students.view', 'students.export',
                    'guardians.view_any', 'guardians.view',
                    'classes.view_any', 'classes.view',
                    'attendance.students.view_any', 'attendance.students.view', 'attendance.students.mark',
                    'attendance.students.export',
                    'attendance.teachers.view', // Can view own attendance
                    'evaluations.view_any', 'evaluations.view', 'evaluations.create', 'evaluations.update',
                    'evaluations.publish', 'evaluations.upload_media',
                    'events.view_any', 'events.view', 'events.create', 'events.mark_attendance',
                    'messages.view', 'messages.create',
                    'announcements.view_any', 'announcements.view',
                    'notifications.view', 'notifications.mark_read',
                    'reports.students', 'reports.attendance',
                ],
            ],
            'reception' => [
                'display_name' => 'Receptionist',
                'description' => 'Manages admissions and parent communication',
                'permissions' => [
                    'students.view_any', 'students.view', 'students.create', 'students.update',
                    'students.upload_documents',
                    'guardians.view_any', 'guardians.view', 'guardians.create', 'guardians.update',
                    'classes.view_any', 'classes.view', 'classes.assign_students',
                    'attendance.students.view_any', 'attendance.students.view', 'attendance.students.mark',
                    'admissions.view_any', 'admissions.view', 'admissions.update_status',
                    'admissions.schedule_interview', 'admissions.convert_to_student',
                    'events.view_any', 'events.view', 'events.create',
                    'messages.view', 'messages.create',
                    'announcements.view_any', 'announcements.view', 'announcements.create',
                    'notifications.view', 'notifications.mark_read',
                ],
            ],
            'hr_officer' => [
                'display_name' => 'HR Officer',
                'description' => 'Manages employee records, leaves, and payroll',
                'permissions' => [
                    'employees.view_any', 'employees.view', 'employees.create', 'employees.update',
                    'employees.upload_documents', 'employees.export',
                    'leaves.view_any', 'leaves.view', 'leaves.approve', 'leaves.reject',
                    'attendance.teachers.view_any', 'attendance.teachers.view', 'attendance.teachers.mark',
                    'attendance.teachers.export',
                    'payroll.view_any', 'payroll.view', 'payroll.generate', 'payroll.export',
                    'reports.hr',
                    'notifications.view', 'notifications.mark_read',
                ],
            ],
            'accountant' => [
                'display_name' => 'Accountant',
                'description' => 'Manages invoices, payments, and financial reports',
                'permissions' => [
                    'students.view_any', 'students.view', // Need to see students for invoicing
                    'guardians.view_any', 'guardians.view',
                    'invoices.view_any', 'invoices.view', 'invoices.create', 'invoices.update',
                    'invoices.send', 'invoices.cancel', 'invoices.export',
                    'payments.view_any', 'payments.view', 'payments.create', 'payments.update',
                    'payments.export',
                    'expenses.view_any', 'expenses.view', 'expenses.create', 'expenses.update',
                    'expenses.export',
                    'payroll.view_any', 'payroll.view', 'payroll.approve', 'payroll.mark_paid',
                    'inventory.view_any', 'inventory.view', 'inventory.create', 'inventory.update',
                    'inventory.record_movement',
                    'assets.view_any', 'assets.view', 'assets.create', 'assets.update',
                    'reports.financial',
                    'notifications.view', 'notifications.mark_read',
                ],
            ],
            'parent' => [
                'display_name' => 'Parent',
                'description' => 'Views own children information',
                'permissions' => [
                    'students.view', // Can only view own children
                    'guardians.view', // Can view own profile
                    'guardians.update', // Can update own profile
                    'attendance.students.view', // Can view own children's attendance
                    'evaluations.view', // Can view own children's evaluations
                    'events.view_any', 'events.view', 'events.register_student', 'events.manage_permissions',
                    'invoices.view_any', 'invoices.view', // Can view own invoices
                    'payments.view', 'payments.create', // Can pay own invoices
                    'messages.view', 'messages.create',
                    'announcements.view_any', 'announcements.view',
                    'notifications.view', 'notifications.mark_read',
                    'reports.students', 'reports.attendance', // Can view own children's reports
                ],
            ],
        ];
    }
}
