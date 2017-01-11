<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SexSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CircleSeeder::class);

        $this->call(AvailabilitySeeder::class);
        $this->call(PrivacySeeder::class);
        $this->call(TagSeeder::class);
        $this->call(TypeSeeder::class);
        $this->call(TaskSeeder::class);
    }
}
