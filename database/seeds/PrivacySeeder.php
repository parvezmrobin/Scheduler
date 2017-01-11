<?php

use Illuminate\Database\Seeder;

class PrivacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (array('Private', 'Circle', 'Public') as $key => $privacy) {
            DB::table('privacies')->insert([
                'id' => $key + 1,
                'privacy' => $privacy,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
            ]);
        }
    }
}
