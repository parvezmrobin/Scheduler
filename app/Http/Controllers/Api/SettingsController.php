<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function get(Request $request)
    {
        $user = $request->user();
        $settings=DB::table('settings')->where ('user_id',$user->id)->first();
        return response()->json($settings);
    }

    public function set(Request $request)
    {
        $this->validate($request,[
            'privacy'=>'nullable|in:"Public","Private","Circle"',
            'availability'=>'nullable|in:"Free","Busy","Unavailable"',
            'type'=>'nullable|in:"Family","Work","Friends","null"',
        ]);
        $user=$request->user();
        $privacy = $request->input('privacy');
        $availability = $request->input('availability');
        $type = $request->input('type');
        $updates = [];
        if ($privacy) {
            $updates['privacy'] = $privacy;
        }
        if($availability){
            $updates['availability'] = $availability;
        }
        if ($type) {
            if($type === 'null'){
                $updates['type'] = null;
            } else{
                $updates['type'] = $type;
            }
        }
        DB::table('settings')->where('user_id',$user->id)->update($updates);
        $settings = DB::table('settings')->where('user_id',$user->id)->first();
        return response()->json($settings);
    }
}
