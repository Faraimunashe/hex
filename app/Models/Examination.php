<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    protected $fillable = ['module_id', 'venue_id', 'exam_date', 'start_time', 'end_time'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function invigilations()
    {
        return $this->hasMany(Invigilation::class);
    }
}
