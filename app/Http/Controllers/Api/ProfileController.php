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
        $allTasks = \App\Task::where('user_id', $id)
            ->where('from', '>=', \Carbon\Carbon::now())
            ->get();
        $tasks = $allTasks->where('privacy', 'Public');

        $circles = \App\Circle::where('user_id', $id)->get();

        foreach ($circles as $key => $circle) {
            $member = \DB::table('circle_members')->where('circle_id', $circle->id);
            if($member->count() > 0){
                $tasks = $tasks->union($allTasks->where('privacy', 'Circle'));
                break;
            }
        }

        return response()->json($tasks->sortByDesc('from')->all());
    }
}
