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
        $pendingTasks = DB::table('task_user')
        ->join('tasks', 'task_user.task_id', 'tasks.id')
        ->where([
            ['task_user.user_id', $user->id],
            ['task_user.is_approved', 0],
        ])->select('tasks.*')->get();

        return response()->json($pendingTasks);
    }

    public function approved(Request $request)
    {
        $user = $request->user();
        $approvedTasks = DB::table('task_user')
        ->join('tasks', 'task_user.task_id', 'tasks.id')
        ->where([
            ['task_user.user_id', $user->id],
            ['task_user.is_approved', 1],
        ])->select('tasks.*')->get();

        return response()->json($approvedTasks);
    }

    public function approve(Request $request)
    {
        $taskId = $request->input('task_id');
        $userId = $request->user()->id;

        DB::table('task_user')->where([
            ['user_id', $userId],
            ['task_id', $taskId]
        ])->update(['is_approved' => 1]);

        return response()->json(['status' => 'succeeded']);
    }

    public function removeApproval(Request $request)
    {
        $taskId = $request->input('task_id');
        $userId = $request->user()->id;

        DB::table('task_user')->where([
            ['user_id', $userId],
            ['task_id', $taskId]
        ])->update(['is_approved' => 0]);

        return response()->json(['status' => 'succeeded']);
    }

    public function deleteRequest(Request $request)
    {
        $taskId = $request->input('task_id');
        $userId = $request->user()->id;

        DB::table('task_user')->where([
            ['user_id', $userId],
            ['task_id', $taskId]
        ])->delete();

        return response()->json(['status' => 'succeeded']);
    }
}
