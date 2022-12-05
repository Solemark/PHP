<?php

use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('schedules')->insert([
            "CounsellorID" => 2,
            "StartDate" => "2020-01-01",
            "EndDate" => "2021-01-01",
            "ScheduleString" => "8,9,10,11,12,13,14,15,16/8,9,10,11,12,13,14,15,16//8,9,10,11,12,13,14,15,16/8,9,10,11,12,13,14,15,16"
        ]);
    }
}
