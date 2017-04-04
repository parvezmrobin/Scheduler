<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ApproveController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function pending(Request $request)
    {
        $user = $request->user();
        $pendingTasks = DB::table('associations')
        ->join('tasks', 'associations.task_id', 'tasks.id')
        ->join('users', 'tasks.user_id', 'users.id')
        ->where([
            ['associations.user_id', $user->id],
            ['associations.is_approved', 0],
            ['tasks.from', '>', new \Carbon\Carbon],
        ]
        )->select('tasks.*', 'users.first_name', 'users.last_name', 'users.id')->latest()->get();

        return response()->json($pendingTasks);
    }

    public function approved(Request $request)
    {
        $user = $request->user();
        $approvedTasks = DB::table('associations')
        ->join('tasks', 'associations.task_id', 'tasks.id')
        ->join('users', 'tasks.user_id', 'users.id')
        ->where([
            ['associations.user_id', $user->id],
            ['associations.is_approved', 1],
            ['tasks.from', '>', new \Carbon\Carbon],
        ]
        )->select('tasks.*', 'users.first_name', 'users.last_name', 'users.id')->get();

        return response()->json($approvedTasks);
    }

    public function approve(Request $request)
    {
        $taskId = $request->input('task_id');
        $userId = $request->user()->id;

        DB::table('associations')->where([
            ['user_id', $userId],
            ['task_id', $taskId]
        ]
        )->update(['is_approved' => 1]);

        return response()->json(['status' => 'succeeded']);
    }

    public function removeApproval(Request $request)
    {
        $taskId = $request->input('task_id');
        $userId = $request->user()->id;

        DB::table('associations')->where([
            ['user_id', $userId],
            ['task_id', $taskId]
        ]
        )->update(['is_approved' => 0]);

        return response()->json(['status' => 'succeeded']);
    }

    public function deleteRequest(Request $request)
    {
        $taskId = $request->input('task_id');
        $userId = $request->user()->id;

        DB::table('associations')->where([
            ['user_id', $userId],
            ['task_id', $taskId]
        ]
        )->delete();

        return response()->json(['status' => 'succeeded']);
    }
}
