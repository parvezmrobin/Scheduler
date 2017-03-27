<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use carbon\carbon;

class CircleController2 extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function createCircle(Request $request)
    {
        $circle=new Circle();
        $circle->circle=$request->input('circle');
        $circle->user_id=$request->user()->id;
        $circle->save();
        return response()->json($circle);
    }
    public function editCircle(Request $request)
    {
        $circle=Circle::find($request->input("circle_id"));
        if($circle->user_id===$request->user()->id){
            $circle->circle=$request->input("edited_name");
            $circle->save();
            return response()->json($circle);
        }
        return response()->json(['status' => 'Unauthorized'], 403);
    }
    public function deleteCircle(Request $request)
    {
        $circle=Circle::find($request->input("circle_id"));
        if($circle->user_id===$request->user()->id){
            $circle->delete();
            return response()->json(['status'=>'succeeded']);
        }
        return response()->json(["status" => "Unauthorized"], 403);
    }
    public function indexCircle(Request $request)
    {
        $circles=Circle::where('user_id',$request->input("user_id"))->orderBy('circle')->get();
        return response()->json($circles);
    }
    public function addMember(Request $request)
    {
        $circle=Circle::find($request->input("circle_id"));
        if($circle->user_id===$request->user()->id){
            $id=DB::table("circle_members")->insertGetId([
                    'circle_id'=>$request->input("circle_id"),
                    'user_id'=>$request->input("user_id"),
                    'created_at'=>new Carbon(),
                    'updated_at'=>new Carbon()
            ]);
            $cm=DB::table('circle_members')->where('id',$id)->first();
            return response()->json($cm);
        }
        return response()->json(["status" => "Unauthorized"], 403);
    }
    public function deleteMember(Request $request)
    {
        $circle=Circle::find($request->input("circle_id"));
        if($circle->user_id===$request->user()->id){
            DB::table('circle_members')
            ->where('circle_id',$request->input("circle_id"))
            ->where('user_id',$request->input("user_id"))->delete();
        }
    }
    public function indexMember(Request $request)
    {
        $circle=Circle::find($request->input("circle_id"));
        if($circle->user_id===$request->user()->id){
            $members=DB::table('circle_members')
            ->join('users','user_id','users.id')
            ->where('circle_id',$request->input("circle_id"))
            ->select('users.*')->get();
            return response()->json($members);
        }
        return response()->json(["status" => "Unauthorized"], 403);
    }
}
