<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function upcomingTasks(Request $request)
    {
        $user = $request->user();
        $page = $request->input('page');
        $res = \App\Task::where([
            ['user_id', $user->id],
            ['from', '>=', \Carbon\Carbon::now()],
        ])->oldest('from')
        ->skip($page*10)
        ->take(10)
        ->get();
        return response()->json($res);
    }

    public function ongoingTask(Request $request)
    {
        return response()->json($request->user()->ongoing_task);
    }

    public function task(Request $request)
    {
        $task = \App\Task::find($request->input('task_id'));
        if($request->user()->can('view', $task)){
            return response()->json($task);
        }

        return response()->json(["status" => "Unauthorized"], 403);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
