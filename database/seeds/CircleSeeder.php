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
        $faker = Faker\Factory::create();
        $users = App\User::all();

        foreach ($users as $key => $user) {
            for ($i=0; $i < 3; $i++) {
                DB::table('circles')->insert([
                    'user_id' => $user->id,
                    'circle' => $faker->word,
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                ]);
            }
        }

        $circles = App\Circle::all();

        foreach ($circles as $key => $circle) {
            $rand_users = $users->random(3);
            $rand_users = $rand_users->diff([$circle->user]);
            foreach ($rand_users as $key => $user) {
                DB::table('circle_memebers')->insert([
                    'circle_id' => $circle->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
