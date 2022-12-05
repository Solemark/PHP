<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            "name" => "admin",
            "email" => "admin@7day.com",
            "password" => Hash::make("password"),
            "role" => "Admin"
        ]);

        DB::table("users")->insert([
            "name" => "counsellor",
            "email" => "counsellor@7day.com",
            "password" => Hash::make("password"),
            "role" => "Counsellor"
        ]);

        DB::table("users")->insert([
            "name" => "client",
            "email" => "client@7day.com",
            "password" => Hash::make("password"),
            "role" => "Client"
        ]);
    }
}
