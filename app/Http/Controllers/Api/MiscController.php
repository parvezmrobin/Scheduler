<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class MiscController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function history(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to') ?: Carbon::now();

        $where = [
            ['user_id', $request->user()->id],
            ['to', '>', $to],
        ];
        if($from){
            array_push($where, ['from', '>=', $from]);
        }

        $tasks = \App\Task::where($where)->oldest('from')->get();
        return response()->json($tasks);
    }
}
