<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function user(Request $request)
    {
        $id = $request->input('user_id');
        return response()->json(\App\User::find($id));
    }

    public function tasks(Request $request)
    {
        $id = $request->input('user_id');
        $user = $request->user();
        $profile = \App\User::find($id);
        $allTasks = $profile->upcomingTasks();
        $tasks = $allTasks->where('privacy_id', 3);

        foreach ($profile->circles as $key => $circle) {
            $member = $circle->members->contains($user);
            if($member){
                $tasks = $tasks->union($allTasks->where('privacy_id', 2));
                break;
            }
        }

        return response()->json($tasks->sortBy('from')->all());
    }
}
