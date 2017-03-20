<?php

use Illuminate\Database\Seeder;

class CircleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();  //creating object of faker
        $users = App\User::all();  //retreving all rows from users table

        foreach ($users as $key => $user) {  //sequencially accesses all memebers of users array
            for ($i=0; $i < 3; $i++) {
                DB::table('circles')->insert([
                    'user_id' => $user->id,
                    'circle' => $faker->word,
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                ]);
            }
        }

        $circles = App\Circle::all(); //retreving all circles

        foreach ($circles as $key => $circle) {
            $rand_users = $users->random(3);
            $rand_users = $rand_users->diff([$circle->user]);
            foreach ($rand_users as $key => $user) {
                DB::table('circle_members')->insert([
                    'circle_id' => $circle->id,
                    'user_id' => $user->id,
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                ]);
            }
        }
    }
}
