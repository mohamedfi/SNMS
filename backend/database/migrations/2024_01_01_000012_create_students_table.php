<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_code', 20)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('arabic_name')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('nationality', 100)->nullable();
            $table->string('national_id', 50)->nullable();
            $table->string('birth_certificate_no', 50)->nullable();
            $table->string('photo')->nullable();

            $table->foreignId('class_id')->nullable()->constrained('school_classes')->onDelete('set null');
            $table->foreignId('guardian_id')->constrained()->onDelete('restrict');

            $table->date('enrollment_date');
            $table->enum('status', ['active', 'graduated', 'withdrawn', 'waiting'])->default('active');

            // Medical information
            $table->text('medical_notes')->nullable();
            $table->text('special_needs')->nullable();
            $table->string('blood_type', 5)->nullable();
            $table->boolean('has_special_diet')->default(false);
            $table->text('diet_notes')->nullable();

            // Pickup authorized persons (JSON array)
            $table->json('pickup_authorized_persons')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('student_code');
            $table->index('class_id');
            $table->index('guardian_id');
            $table->index('status');
            $table->index('enrollment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
