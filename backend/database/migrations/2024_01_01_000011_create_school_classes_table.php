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
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('age_group', 50)->nullable();
            $table->unsignedInteger('capacity');
            $table->unsignedInteger('current_enrollment')->default(0);
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assistant_teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('room_number', 50)->nullable();
            $table->string('academic_year', 10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('teacher_id');
            $table->index('assistant_teacher_id');
            $table->index('academic_year');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};
