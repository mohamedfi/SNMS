<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_code' => $this->student_code,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'arabic_name' => $this->arabic_name,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'age' => $this->age,
            'gender' => $this->gender,
            'nationality' => $this->nationality,
            'national_id' => $this->national_id,
            'birth_certificate_no' => $this->birth_certificate_no,
            'photo' => $this->photo_url,
            'class' => $this->whenLoaded('class', function () {
                return [
                    'id' => $this->class->id,
                    'name' => $this->class->name,
                    'age_group' => $this->class->age_group,
                    'teacher' => $this->class->teacher?->name,
                ];
            }),
            'guardian' => $this->whenLoaded('guardian', function () {
                return [
                    'id' => $this->guardian->id,
                    'father_name' => $this->guardian->father_name,
                    'father_phone' => $this->guardian->father_phone,
                    'mother_name' => $this->guardian->mother_name,
                    'mother_phone' => $this->guardian->mother_phone,
                    'primary_contact' => $this->guardian->primary_contact,
                    'primary_phone' => $this->guardian->primary_phone,
                ];
            }),
            'enrollment_date' => $this->enrollment_date?->toDateString(),
            'status' => $this->status,
            'medical_notes' => $this->medical_notes,
            'special_needs' => $this->special_needs,
            'blood_type' => $this->blood_type,
            'has_special_diet' => $this->has_special_diet,
            'diet_notes' => $this->diet_notes,
            'pickup_authorized_persons' => $this->pickup_authorized_persons,
            'notes' => $this->notes,
            'documents' => $this->whenLoaded('documents', function () {
                return $this->documents->map(function ($doc) {
                    return [
                        'id' => $doc->id,
                        'type' => $doc->document_type,
                        'file_name' => $doc->file_name,
                        'file_url' => $doc->file_url,
                        'uploaded_at' => $doc->created_at->toISOString(),
                    ];
                });
            }),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
