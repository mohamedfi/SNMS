<?php

namespace App\Http\Controllers\Api\V1\Students;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search');
        $classId = $request->input('class_id');
        $status = $request->input('status');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = Student::with(['class', 'guardian']);

        // Apply filters
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_code', 'like', "%{$search}%");
            });
        }

        if ($classId) {
            $query->where('class_id', $classId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        // Authorization: Teachers can only see their class students
        $user = $request->user();
        if ($user->hasRole('teacher')) {
            $teacherClassIds = $user->classesAsTeacher()->pluck('id');
            $query->whereIn('class_id', $teacherClassIds);
        }

        // Sort and paginate
        $query->orderBy($sortBy, $sortOrder);
        $students = $query->paginate($perPage);

        return $this->paginatedResponse(
            $students->setCollection(
                $students->getCollection()->map(fn($student) => new StudentResource($student))
            ),
            'Students retrieved successfully'
        );
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Student::class);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'arabic_name' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'birth_certificate_no' => ['nullable', 'string', 'max:50'],
            'class_id' => ['nullable', 'exists:school_classes,id'],
            'guardian_id' => ['required', 'exists:guardians,id'],
            'enrollment_date' => ['required', 'date'],
            'status' => ['sometimes', 'in:active,graduated,withdrawn,waiting'],
            'medical_notes' => ['nullable', 'string'],
            'special_needs' => ['nullable', 'string'],
            'blood_type' => ['nullable', 'string', 'max:5'],
            'has_special_diet' => ['boolean'],
            'diet_notes' => ['nullable', 'string'],
            'pickup_authorized_persons' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
        ]);

        $student = Student::create($validated);

        return $this->successResponse(
            new StudentResource($student->load(['class', 'guardian'])),
            'Student created successfully',
            201
        );
    }

    /**
     * Display the specified student.
     */
    public function show(Request $request, Student $student): JsonResponse
    {
        $this->authorize('view', $student);

        $student->load(['class', 'guardian', 'documents', 'attendance' => function ($query) {
            $query->latest()->take(30);
        }]);

        return $this->successResponse(
            new StudentResource($student),
            'Student retrieved successfully'
        );
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student): JsonResponse
    {
        $this->authorize('update', $student);

        $validated = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:100'],
            'last_name' => ['sometimes', 'string', 'max:100'],
            'arabic_name' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['sometimes', 'date', 'before:today'],
            'gender' => ['sometimes', 'in:male,female'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'national_id' => ['nullable', 'string', 'max:50'],
            'birth_certificate_no' => ['nullable', 'string', 'max:50'],
            'class_id' => ['nullable', 'exists:school_classes,id'],
            'enrollment_date' => ['sometimes', 'date'],
            'status' => ['sometimes', 'in:active,graduated,withdrawn,waiting'],
            'medical_notes' => ['nullable', 'string'],
            'special_needs' => ['nullable', 'string'],
            'blood_type' => ['nullable', 'string', 'max:5'],
            'has_special_diet' => ['boolean'],
            'diet_notes' => ['nullable', 'string'],
            'pickup_authorized_persons' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
        ]);

        $student->update($validated);

        return $this->successResponse(
            new StudentResource($student->load(['class', 'guardian'])),
            'Student updated successfully'
        );
    }

    /**
     * Remove the specified student (soft delete).
     */
    public function destroy(Request $request, Student $student): JsonResponse
    {
        $this->authorize('delete', $student);

        $student->delete();

        return $this->successResponse(
            null,
            'Student deleted successfully'
        );
    }

    /**
     * Upload student photo.
     */
    public function uploadPhoto(Request $request, Student $student): JsonResponse
    {
        $this->authorize('update', $student);

        $request->validate([
            'photo' => ['required', 'image', 'max:5120'], // 5MB max
        ]);

        // Delete old photo if exists
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        // Store new photo
        $path = $request->file('photo')->store('students/photos', 'public');

        $student->update(['photo' => $path]);

        return $this->successResponse([
            'photo_url' => Storage::disk('public')->url($path),
        ], 'Photo uploaded successfully');
    }

    /**
     * Get student attendance summary.
     */
    public function attendanceSummary(Request $request, Student $student): JsonResponse
    {
        $this->authorize('view', $student);

        $month = $request->input('month', now()->format('Y-m'));

        $attendance = $student->attendance()
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
            ->get();

        $summary = [
            'total_days' => $attendance->count(),
            'present' => $attendance->where('status', 'present')->count(),
            'absent' => $attendance->where('status', 'absent')->count(),
            'late' => $attendance->where('status', 'late')->count(),
            'excused' => $attendance->where('status', 'excused')->count(),
            'attendance_rate' => $attendance->count() > 0
                ? round(($attendance->whereIn('status', ['present', 'late'])->count() / $attendance->count()) * 100, 2) . '%'
                : '0%',
        ];

        return $this->successResponse($summary, 'Attendance summary retrieved successfully');
    }
}
