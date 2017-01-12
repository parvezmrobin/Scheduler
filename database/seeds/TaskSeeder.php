<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $faker = Faker\Factory::create();
        for($i = 0; $i<100; $i++){
            $task = [
                'user_id' => App\User::all()->random()->id,
                'title' => $faker->sentence,
                'from' => $faker->dateTimeBetween(new Carbon('last friday'), Carbon::today()),
                'to' => $faker->dateTimeBetween(Carbon::today(), new Carbon('next friday')),
                'availability_id' => rand(1, 3),
                'privacy_id' => rand(1, 3),
                'type_id' => rand(1, 4),
                'location' => $faker->address,
                'detail' => $faker->paragraph,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            //var_dump($task);
            DB::table('tasks')->insert($task);
        }

        $tasks = App\Task::all();
        $users = App\User::all();
        $tags = App\Tag::all();
        foreach ($tasks as $key => $task) {

            $rand_users = $users->random(3);
            $rand_users = $rand_users->diff(collect([$task->user]));

            foreach ($rand_users as $key => $value) {
                DB::table('task_user')->insert([
                    'task_id' => $task->id,
                    'user_id' => $value->id,
                    'is_approved' => rand(0, 1),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $rand_tags = $tags->random(3);

            foreach ($rand_tags as $key => $tag) {
                DB::table('tag_task')->insert([
                    'tag_id' => $tag->id,
                    'task_id' => $task->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
