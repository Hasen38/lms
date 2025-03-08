<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['name', 'email', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'enrollments', 'teacher_id', 'subject_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
