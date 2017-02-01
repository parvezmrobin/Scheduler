<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collaboration extends Model
{
    protected $table = "task_user";

    protected $casts = ['is_approved' => 'boolean'];

    public function user()
    {
        $this->belongsTo('App\User');
    }

    public function task()
    {
        $this->belongsTo('App\Task');
    }
}
