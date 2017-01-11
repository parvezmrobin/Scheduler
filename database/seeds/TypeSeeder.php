<?php

use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (array('Meeting', 'Travel', 'Sports', 'Family') as $key => $type) {
            DB::table('types')->insert([
                'id' => $key + 1,
                'type' => $type,
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
            ]);
        }
    }
}
