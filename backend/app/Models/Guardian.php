<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guardian extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'father_name',
        'father_phone',
        'father_email',
        'father_occupation',
        'father_national_id',
        'mother_name',
        'mother_phone',
        'mother_email',
        'mother_occupation',
        'mother_national_id',
        'address',
        'city',
        'district',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
    ];

    /**
     * Get the user associated with the guardian.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the students (children) for the guardian.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the invoices for this guardian.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get primary contact name (father or mother).
     */
    public function getPrimaryContactAttribute(): string
    {
        return $this->father_name ?: $this->mother_name ?: 'N/A';
    }

    /**
     * Get primary contact phone.
     */
    public function getPrimaryPhoneAttribute(): ?string
    {
        return $this->father_phone ?: $this->mother_phone;
    }

    /**
     * Get primary contact email.
     */
    public function getPrimaryEmailAttribute(): ?string
    {
        return $this->father_email ?: $this->mother_email;
    }
}
