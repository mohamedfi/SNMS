<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    /**
     * Determine if the user can view any students.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('students.view_any');
    }

    /**
     * Determine if the user can view the student.
     */
    public function view(User $user, Student $student): bool
    {
        // Parents can only view their own children
        if ($user->hasRole('parent')) {
            return $student->guardian_id === $user->guardian?->id;
        }

        // Teachers can view students in their classes
        if ($user->hasRole('teacher')) {
            $teacherClassIds = $user->classesAsTeacher()->pluck('id');
            return $teacherClassIds->contains($student->class_id);
        }

        return $user->hasPermission('students.view');
    }

    /**
     * Determine if the user can create students.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('students.create');
    }

    /**
     * Determine if the user can update the student.
     */
    public function update(User $user, Student $student): bool
    {
        return $user->hasPermission('students.update');
    }

    /**
     * Determine if the user can delete the student.
     */
    public function delete(User $user, Student $student): bool
    {
        return $user->hasPermission('students.delete');
    }

    /**
     * Determine if the user can restore the student.
     */
    public function restore(User $user, Student $student): bool
    {
        return $user->hasPermission('students.restore');
    }

    /**
     * Determine if the user can permanently delete the student.
     */
    public function forceDelete(User $user, Student $student): bool
    {
        return $user->hasRole('super_admin');
    }
}
