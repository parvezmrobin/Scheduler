<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\Task;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function tasks(Request $request)
    {
        $user = $request->user();
        $page = $request->input('page');
        $rawWhere = '((associations.user_id = ' . $user->id .
        ' and is_approved IS TRUE) or tasks.user_id = ' . $user->id . ')';

        $general = Task::join('associations', 'associations.task_id', 'tasks.id')
        ->where('to', '>=', Carbon::now())
        ->whereRaw($rawWhere)
        ->select('tasks.*');

        $daily = Task::join('daily_tasks', 'task_id', 'id')
        ->join('associations', 'associations.task_id', 'tasks.id')
        ->where('tasks.to', '<', new Carbon)
        ->whereRaw($rawWhere)->select($this->selectExt('DAY'));

        $weekly = Task::join('weekly_tasks', 'task_id', 'id')
        ->join('associations', 'associations.task_id', 'tasks.id')
        ->where('tasks.to', '<', new Carbon)
        ->whereRaw($rawWhere)->select
        ($this->selectExt('WEEK'));

        $monthly = Task::join('monthly_tasks', 'task_id', 'id')
        ->join('associations', 'associations.task_id', 'tasks.id')
        ->where('tasks.to', '<', new Carbon)
        ->whereRaw($rawWhere)->select
        ($this->selectExt('MONTH'));

        $yearly = Task::join('yearly_tasks', 'task_id', 'id')
        ->join('associations', 'associations.task_id', 'tasks.id')
        ->where('tasks.to', '<', new Carbon)
        ->whereRaw($rawWhere)->select
        ($this->selectExt('YEAR'));

        $res = $general
        ->union($daily)
        ->union($weekly)
        ->union($monthly)
        ->union($yearly)
        ->oldest('from')
        ->skip($page*10)
        ->take(10)
        ->get();

        return response()->json($res);
    }

    public function task(Request $request)
    {
        $task = Task::find($request->input('task_id'));
        if($request->user()->can('view', $task)){
            return response()->json($task);
        }

        return response()->json(["status" => "Unauthorized"], 403);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function trending(Request $request)
    {
        $res = DB::table('tag_task')
            ->join('tags', 'tags.id', 'tag_id')
            ->where('tag_task.created_at', '>', Carbon::now()->subWeeks(1))
            ->groupBy('tags.id', 'tags.tag', 'tags.created_at', 'tags.updated_at')
            ->select(DB::raw('tags.*, count(tags.id) as count'))
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();
        return response()->json($res);
    }

    function selectExt($timeUnit)
    {
        $dbConfig = config('database.default');
        if ($dbConfig === 'pgsql') {
            $rawSelect = '"tasks"."id", "tasks"."user_id", "tasks"."title", '
                . '("tasks"."from" + (FLOOR((DATE_PART(\'day\', NOW() - "tasks"."from") / repetition) + 1) * repetition ) * INTERVAL \'1 ' . $timeUnit . '\') AS "from", '
                . '("tasks"."to" + (FLOOR((DATE_PART(\'day\', NOW() - "tasks"."from") / repetition) + 1) * repetition ) * INTERVAL\' 1 ' . $timeUnit . '\') AS "to", '
                . '"tasks"."availability", "tasks"."privacy", "tasks"."type", "tasks"."location", "tasks"."detail", "tasks"."created_at", "tasks"."updated_at"';
            return DB::raw($rawSelect);
        } elseif ($dbConfig === 'mysql') {
            $rawSelect = '`tasks`.`id`, `tasks`.`user_id`, `tasks`.`title`, DATE_ADD(tasks.from, INTERVAL( CEIL(DATEDIFF(NOW(), tasks.from) / repetition) * repetition ) '
                . $timeUnit
                . ') AS `from`, '
                . 'DATE_ADD(tasks.to, INTERVAL( CEIL(DATEDIFF(NOW(), tasks.from) / repetition) * repetition ) '
                . $timeUnit
                . ') AS `to`, `tasks`.`availability`, `tasks`.`privacy`, `tasks`.`type`, `tasks`.`location`, `tasks`.`detail`, `tasks`.`created_at`, `tasks`.`updated_at`';

            return DB::raw($rawSelect);
        }
    }
}
