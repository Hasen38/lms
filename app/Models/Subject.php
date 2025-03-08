<?php

namespace App\Models;

use App\Models\User;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name'];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot('teacher_id')
            ->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'subject_id', 'teacher_id');
    }
}
