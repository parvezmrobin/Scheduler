<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Circle extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function members()
    {
        return $this->belongsToMany('App\User', 'circle_memebers');
    }
}
