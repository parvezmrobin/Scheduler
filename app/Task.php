<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function availability()
    {
        return $this->hasOne('App\Availability');
    }

    public function circles()
    {
        return $this->hasMany('App\Circle');
    }

    public function privacy()
    {
        return $this->hasOne('App\Privacy');
    }

    public function tags()
    {
        return $this->hasMany('App\Tag');
    }

    public function type()
    {
        return $this->hasOne('App\Type');
    }

    public function collaborators()
    {
        return $this->belongsToMany('App\User');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
