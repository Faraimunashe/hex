<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invigilation extends Model
{
    protected $fillable = ['examination_id', 'user_id'];

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
