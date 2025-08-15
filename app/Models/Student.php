<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'department_id', 'regnum', 'firstnames', 'surname', 'gender'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'student_modules');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
