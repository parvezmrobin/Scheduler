<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class HomeController extends Controller
{
    public function upcomingTasks(Request $request)
    {
        $user = $request->user();
        return response()->json($user->upcomingTasks());;
    }

    public function ongoingTask(Request $request)
    {
        return response()->json($request->user()->ongoingTask());
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
