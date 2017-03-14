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

            DB::table('users')->insert($user);
        }
    }
}
