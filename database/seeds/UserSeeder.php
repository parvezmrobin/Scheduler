<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $faker = Faker\Factory::create();
        $sexes = ['Male', 'Female', 'Other'];
        $availability = ['Free', 'Busy', 'Unavailable'];
        $privacy = ['Private', 'Circle', 'Public'];
        $type = ['Family', 'Friends', 'Work'];

        for($i = 0; $i<5; $i++){
            $user = [
                'user_name' => $faker->unique()->userName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('secret'),
                'sex' => $sexes[array_rand($sexes)],
                'remember_token' => str_random(10),
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
            ];

            if($user['sex'] == 'Male'){
                $user = array_merge($user,[
                    'first_name' => $faker->firstNameMale
                ]);
            }elseif ($user['sex'] == 'Female') {
                $user = array_merge($user,[
                    'first_name' => $faker->firstNameFemale
                ]);
            }else {
                $user = array_merge($user,[
                    'first_name' => $faker->firstName
                ]);
            }

            $id = DB::table('users')->insertGetId($user);
            DB::table('settings')->insert([
                'user_id' => $id,
                'availability' => $availability[array_rand($availability)],
                'privacy' => $privacy[array_rand($privacy)],
                'type' => $type[array_rand($type)],
                'created_at' => new Carbon\Carbon,
                'updated_at' => new Carbon\Carbon,
            ]);
        }
    }
}
