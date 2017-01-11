<?php

use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (array('Free', 'Busy', 'Unavailable') as $key => $availability) {
            DB::table('availabilities')->insert([
                'id' => $key + 1,
                'availability' => $availability,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
            ]);
        }
    }
}
