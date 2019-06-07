<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public $timestamps  = false;

    protected $guarded = [];

    public function professor()
    {
        return $this->hasOne('App\Professor');
    }

    public function students()
    {
        return $this->belongsToMany('App\Student')->withPivot('chair_number');
    }
}
