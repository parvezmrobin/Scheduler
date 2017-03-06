<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CircleController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function circles(Request $request)
    {
        $circles = DB::table('circles')
        ->where('user_id', $request->user()->id)
        ->get();

        return response()->json($circles);
    }

    public function members(Request $request)
    {
        $id = $request->input('circle_id');
        if (!$id) {
            return response()->json([]);
        }
        $circle = \App\Circle::find($id);
        if($circle->user_id !== $request->user()->id){
            return response()->json(["status" => "Unauthorized"], 403);
        }
        $users = \DB::table('circle_members')
        ->join('users', 'circle_members.user_id', 'users.id')
        ->where('circle_id', $id)
        ->select('users.*')
        ->get();

        return response()->json($users);
    }


    public function create(Request $request)
    {
        $circle = new \App\Circle;
        $circle->circle = $request->input('circle');
        $circle->user_id = $request->user()->id;
        $circle->save();
        return response()->json($circle);
    }

    public function renameCircle(Request $request)
    {
        $circleId = $request->input('circle_id');
        $circle = $request->input('circle');
        if(\App\Circle::find($circleId)->user_id !== $request->user()->id){
            return response()->json(["status" => "Unauthorized"], 403);
        }

        DB::table('circles')
        ->where('id', $circleId)
        ->update(['circle' => $circle]);

        return response()->json(\App\Circle::find($circleId));
    }

    public function addUser(Request $request)
    {
        $userId = $request->input('user_id');
        $circleId = $request->input('circle_id');
        if(\App\Circle::find($circleId)->user_id !== $request->user()->id){
            return response()->json(["status" => "Unauthorized"], 403);
        }

        $res = DB::table('circle_members')->where([
            ['user_id', $userId],
            ['circle_id', $circleId]
            ])->get();

            if($res->count() > 0){
                return response()->json($res[0]);
            }

            $id = DB::table('circle_members')->insertGetId ([
                'user_id' => $userId,
                'circle_id' => $circleId,
            ]);
            return response()->json(
                DB::table('circle_members')->where('id', $id)->get()
            );

        }

        public function deleteUser(Request $request)
        {
            $userId = $request->input('user_id');
            $circleId = $request->input('circle_id');
            if(\App\Circle::find($circleId)->user_id !== $request->user()->id){
                return response()->json(["status" => "Unauthorized"], 403);
            }

            DB::table('circle_members')->where([
                ['user_id', $userId],
                ['circle_id', $circleId]
            ]
            )->delete();

            return response()->json(['status' => "succeeded"]);
        }

        public function deleteCircle(Request $request)
        {
            $circleId = $request->input('circle_id');
            if(\App\Circle::find($circleId)->user_id !== $request->user()->id){
                return response()->json(["status" => "Unauthorized"], 403);
            }

            DB::table('circles')->where('id', $circleId)->delete();
            return response()->json(['status' => "succeeded"]);
        }
    }
