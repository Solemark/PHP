<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Schedule;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(Schedule::class, function (Faker $faker) {
    return [
        "CounsellorID" => 0,
        "StartDate" => now(),
        "EndDate" => now()->add(new DateInterval("P28D")),
        "ScheduleString" => "8/8/8/8/8",
    ];
});
