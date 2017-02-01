<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $dates = ['created_at', 'updated_at', 'from', 'to'];

    public function availability()
    {
        return $this->hasOne('App\Availability');
    }

    public function privacy()
    {
        return $this->hasOne('App\Privacy');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function type()
    {
        return $this->hasOne('App\Type');
    }

    public function collaborations()
    {
        return $this->hasMany('App\Collaborator');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
