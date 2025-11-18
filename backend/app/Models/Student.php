<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_code',
        'first_name',
        'last_name',
        'arabic_name',
        'date_of_birth',
        'gender',
        'nationality',
        'national_id',
        'birth_certificate_no',
        'photo',
        'class_id',
        'guardian_id',
        'enrollment_date',
        'status',
        'medical_notes',
        'special_needs',
        'blood_type',
        'has_special_diet',
        'diet_notes',
        'pickup_authorized_persons',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'enrollment_date' => 'date',
            'has_special_diet' => 'boolean',
            'pickup_authorized_persons' => 'array',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($student) {
            if (empty($student->student_code)) {
                $student->student_code = static::generateStudentCode();
            }
        });
    }

    /**
     * Generate a unique student code.
     */
    public static function generateStudentCode(): string
    {
        $year = date('Y');
        $lastStudent = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastStudent
            ? intval(substr($lastStudent->student_code, -3)) + 1
            : 1;

        return 'STU' . $year . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get the class that the student belongs to.
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the guardian for the student.
     */
    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class);
    }

    /**
     * Get the documents for the student.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(StudentDocument::class);
    }

    /**
     * Get the attendance records for the student.
     */
    public function attendance(): HasMany
    {
        return $this->hasMany(StudentAttendance::class);
    }

    /**
     * Get the invoices for the student.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Scope a query to only include active students.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to filter by class.
     */
    public function scopeInClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get age attribute.
     */
    public function getAgeAttribute(): string
    {
        $years = $this->date_of_birth->diffInYears(now());
        $months = $this->date_of_birth->diffInMonths(now()) % 12;

        return "{$years} years {$months} months";
    }

    /**
     * Get photo URL attribute.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? storage_url($this->photo) : null;
    }
}
