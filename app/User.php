<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
        'associated_tasks', 'upcoming_tasks', 'ongoing_task'
    ];

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function getAssociatedTasksAttibute()
    {
        return $this->belongsToMany('App\Task');
    }

    public function getUpcomingTasksAttribute()
    {
        return DB::table('tasks')
            ->where([
                ['user_id', $this->id],
                ['from', '>', Carbon::now()],
            ])->get();
    }

    public function getOngoingTaskAttribute()
    {
        return DB::table('tasks')
            ->where([
                ['user_id', $this->is],
                ['from', '<=', Carbon::now()],
                ['to', '>=', Carbon::now()],
            ])->get();
    }

    public function circles()
    {
        return $this->hasMany('App\Circle');
    }
}
