<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $guarded = [];

    public function courses()
    {
        return $this->belongsToMany('App\Course');
    }
}
