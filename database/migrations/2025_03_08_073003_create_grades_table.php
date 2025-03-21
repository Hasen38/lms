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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->cascadeOnDelete();
            $table->foreignId('subject_id')->cascadeOnDelete();
            $table->foreignId('teacher_id')->cascadeOnDelete();
            $table->integer('mark');
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'teacher_id']); // Add unique constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
