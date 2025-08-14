<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['department_id', 'code', 'name'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function examinations()
    {
        return $this->hasMany(Examination::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_modules');
    }
}
