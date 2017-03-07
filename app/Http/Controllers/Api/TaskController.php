<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Task;
use Carbon\Carbon;
use App\User;
use DB;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function show(Request $request)
    {
        $task = Task::find($request->input('id'));
        $user = $request->user();
        if (true) {
            return response()->json($task);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function tagSearch(Request $request)
    {
        $keyword = $request->input('tag');
        $tags = DB::table('tags')
        ->where('tag', 'like', $keyword . '%')
        ->get();
        if($tags->count() < 5){
            $tag2 = DB::table('tags')
            ->where('tag', 'like', '%' . $keyword . '%')
            ->get();
            $tags = $tags->union($tag2);
        }
        return response()->json($tags->unique('id')->values()->take(10)->all());
    }

    public function userSearch(Request $request)
    {
        $keyword = $request->input('user');

        $users = DB::table('users')
        ->where('user_name', 'like', $keyword . '%')
        ->get();

        if($users->count() < 5){
            $nusers = DB::table('users')
            ->where('first_name', 'like', $keyword . '%')
            ->get();
            $users = $users->union($nusers);
        }

        if($users->count() < 5){
            $nusers = DB::table('users')
            ->where('last_name', 'like', $keyword . '%')
            ->get();
            $users = $users->union($nusers);
        }

        if($users->count() < 5){
            $nusers = DB::table('users')
            ->where('first_name', 'like', '%' . $keyword . '%')
            ->get();
            $users = $users->union($nusers);
        }

        if($users->count() < 5){
            $nusers = DB::table('users')
            ->where('last_name', 'like', '%' . $keyword . '%')
            ->get();
            $users = $users->union($nusers);
        }

        if($users->count() < 5){
            $nusers = DB::table('users')
            ->where('user_name', 'like', '%' . $keyword . '%')
            ->get();
            $users = $users->union($nusers);
        }

        return response()->json($users->unique('id')->values()->take(10)->all());
    }

    public function create(Request $request)
    {
        $task = new Task();
        $task->user_id = $request->user()->id;
        $task->title = $request->input('title');
        $task->from = $request->input('from');
        $task->to = $request->input('to');
        $task->location = $request->input('location');
        $task->detail = $request->input('detail');
        $task->privacy = $request->input('privacy');
        $task->availability = $request->input('availability');
        $task->type = $request->input('type');

        $task->save();

        $tags = $request->input('tags');
        if($tags){
            foreach ($tags as $key => $tag) {
                DB::table('tag_task')->insert([
                    'tag_id' => $tag,
                    'task_id' => $task->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        $users = $request->input('users');
        if($users){
            foreach ($users as $key => $user) {
                DB::table('associations')->insert([
                    'user_id' => $user,
                    'task_id' => $task->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'is_approved' => 0,
                ]);
            }
        }

        return response()->json($task);
    }

    public function update(Request $request)
    {
        $task = Task::find($request->input('id'));
        $user = $request->user();
        if($user->can('update', $task)){
            $task->user_id = $user->id;
            $task->title = $request->input('title');
            $task->from = $request->input('from');
            $task->to = $request->input('to');
            $task->location = $request->input('location');
            $task->detail = $request->input('detail');
            $task->privacy = $request->input('privacy');
            $task->availability = $request->input('availability');
            $task->type = $request->input('type');

            $task->save();

            DB::table('tag_task')->where('task_id', $task->id)->delete();
            $tags = $request->input('tags');
            if($tags){
                foreach ($tags as $key => $tag) {
                    DB::table('tag_task')->insert([
                        'tag_id' => $tag,
                        'task_id' => $task->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }

            return response()->json($task);
        }

        return response()->json(["status" => "Unauthorized"], 403);
    }

    public function delete(Request $request)
    {
        $task = Task::find($request->input('task_id'));
        if($request->user()->can('delete', $task)){
            Task::where('id', $task->id)->delete();
            return response()->json(["status" => "succeeded"]);
        }

        return response()->json(["status" => "Unauthorized"], 403);
    }

    function canView(User $user, Task $task)
    {
        if($task->privacy === 3){
            return true;
        }

        if($task->privacy === 1 && $task->user_id === $user->id){
            return true;
        }

        if($task->privacy === 2){
            if ($task->user_id === $user->id) {
                return true;
            }
            $creator = $task->user;
            foreach ($creator->circles as $key => $circle) {
                if($circle->members->contains($user)){
                    return true;
                }
            }
        }

        return false;
    }
}
