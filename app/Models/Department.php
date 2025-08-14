<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name'];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function lecturers()
    {
        return $this->hasMany(Lecturer::class);
    }
}
