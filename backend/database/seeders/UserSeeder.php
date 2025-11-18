<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Guardian;
use App\Models\SchoolClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@stepsnursery.com',
            'phone' => '+201234567890',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $superAdmin->assignRole('super_admin');

        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin2@stepsnursery.com',
            'phone' => '+201234567891',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create sample class for teachers
        $class1 = SchoolClass::create([
            'name' => 'Class A1',
            'academic_year' => '2024-2025',
            'capacity' => 20,
            'current_enrollment' => 15,
            'age_group' => '3-4 years',
            'room_number' => 'A1',
            'is_active' => true,
        ]);

        $class2 = SchoolClass::create([
            'name' => 'Class B2',
            'academic_year' => '2024-2025',
            'capacity' => 18,
            'current_enrollment' => 12,
            'age_group' => '4-5 years',
            'room_number' => 'B2',
            'is_active' => true,
        ]);

        // Create Teacher
        $teacher = User::create([
            'name' => 'Sarah Teacher',
            'email' => 'teacher@stepsnursery.com',
            'phone' => '+201234567892',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $teacher->assignRole('teacher');

        // Assign teacher to class
        $class1->teacher_id = $teacher->id;
        $class1->save();

        // Create Reception
        $reception = User::create([
            'name' => 'Reception Staff',
            'email' => 'reception@stepsnursery.com',
            'phone' => '+201234567893',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $reception->assignRole('reception');

        // Create HR Officer
        $hr = User::create([
            'name' => 'HR Officer',
            'email' => 'hr@stepsnursery.com',
            'phone' => '+201234567894',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $hr->assignRole('hr_officer');

        // Create Accountant
        $accountant = User::create([
            'name' => 'Accountant User',
            'email' => 'accountant@stepsnursery.com',
            'phone' => '+201234567895',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $accountant->assignRole('accountant');

        // Create Parent with Guardian
        $guardian = Guardian::create([
            'father_name' => 'Ahmed Parent',
            'father_phone' => '+201234567896',
            'father_email' => 'parent@stepsnursery.com',
            'father_occupation' => 'Engineer',
            'mother_name' => 'Fatima Parent',
            'mother_phone' => '+201234567897',
            'mother_email' => 'parent2@stepsnursery.com',
            'mother_occupation' => 'Doctor',
            'address' => '123 Cairo Street, Nasr City, Cairo',
            'city' => 'Cairo',
            'district' => 'Nasr City',
            'emergency_contact_name' => 'Grandma',
            'emergency_contact_phone' => '+201234567898',
            'emergency_contact_relation' => 'Grandmother',
        ]);

        $parent = User::create([
            'name' => 'Ahmed Parent',
            'email' => 'parent@stepsnursery.com',
            'phone' => '+201234567896',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $parent->assignRole('parent');

        // Link parent to guardian
        $guardian->user_id = $parent->id;
        $guardian->save();

        $this->command->info('✓ Created users successfully');
        $this->command->info('');
        $this->command->info('Demo Credentials:');
        $this->command->info('==================');
        $this->command->info('Super Admin: admin@stepsnursery.com / password');
        $this->command->info('Admin: admin2@stepsnursery.com / password');
        $this->command->info('Teacher: teacher@stepsnursery.com / password');
        $this->command->info('Reception: reception@stepsnursery.com / password');
        $this->command->info('HR Officer: hr@stepsnursery.com / password');
        $this->command->info('Accountant: accountant@stepsnursery.com / password');
        $this->command->info('Parent: parent@stepsnursery.com / password');
    }
}
