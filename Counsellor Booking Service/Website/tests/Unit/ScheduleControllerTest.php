<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use App\Schedule;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ScheduleControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function CreateCounsellor()
    {
        $result = factory(User::class)->create();
        $result->role = "Counsellor";
        $result->save();
        return $result;
    }

    public function CreateClient()
    {
        $result = factory(User::class)->create();
        $result->role = "Client";
        $result->save();
        return $result;
    }

    /**
     * Test show all schedules for logged in user
     * 
     * Endpoint: GET /schedules/show
     */
    public function test_Show()
    {
        //Ensure logged out users are redirected to the login page
        $response = $this->get("/schedules/show");
        $response->assertLocation("/login");

        //Ensure users with client role are redirected to home page
        $user = $this->CreateClient();
        $response = $this->actingAs($user)->get("/schedules/show");
        $response->assertLocation("/");

        //Ensure users with counsellor role are permitted access
        $user = $this->CreateCounsellor();
        $response = $this->actingAs($user)->get("/schedules/show");
        $response->assertOk();
    }

    /**
     * Test Create schedule for logged in user
     * 
     * Endpoint: POST /schedules/create
     */
    public function test_Create()
    {
        //Test as client
        $user = $this->CreateClient();
        $response = $this->actingAs($user)->post("/schedules/create", [
            "startDate" => "2020-4-8",
            "endDate" => "2021-4-8",
        ]);
        $response->assertLocation("/");

        //Test as counsellor with valid form
        $user = $this->CreateCounsellor();
        $response = $this->actingAs($user)->post("/schedules/create", [
            "startDate" => "2020-4-8",
            "endDate" => "2021-4-8",
        ]);
        $response->assertStatus(302);

        //Test as counsellor with bad form
        $response = $this->actingAs($user)->post("/schedules/create", [
            "stringfield" => "hello world",
            "endDate" => "2021-4-8",
        ]);
        $response->assertSessionHasErrors(["startDate"]);

    }

    /**
     * Test New schedule page
     * 
     * Endpoint: GET /schedules/new
     */
    public function test_New()
    {
        //Test as client
        $user = $this->CreateClient();
        $response = $this->actingAs($user)->get("/schedules/new");
        $response->assertLocation("/");

        //Test as valid user
        $user = $this->CreateCounsellor();
        $response = $this->actingAs($user)->get("/schedules/new");
        $response->assertOk();
        $response->assertViewIs("schedules.new");
    }

    /**
     * Test update page
     * 
     * Endpoint: GET /schedules/update
     */
    public function test_UpdateGet()
    {
        $schedule = factory(Schedule::class)->create();

        //Test as client
        $user = $this->CreateClient();
        $response = $this->actingAs($user)->get("/schedules/update?id=".
            $schedule->id);
        $response->assertLocation("/");

        //Test as counsellor
        $user = $this->CreateCounsellor();
        $schedule->CounsellorID = $user->id;
        $schedule->save();
        $response = $this->actingAs($user)->get("/schedules/update?id=".
            $schedule->id);
        $response->assertOk();
        
        //Test counsellor cannot edit another counsellors schedule
        $user2 = $this->CreateCounsellor();
        $response = $this->actingAs($user2)->get("/schedules/update?id=".
            $schedule->id);
        $response->assertStatus(403);
    }

    /**
     * Test update endpoint
     * 
     * Endpoint: POST /schedules/update
     */
    public function test_UpdatePost()
    {        
        $schedule = factory(Schedule::class)->create();

        //Test as client
        $user = $this->CreateClient();
        $response = $this->actingAs($user)->post("/schedules/update",
            [
                "id" => $schedule->id,
                "StartDate" => $schedule->EndDate,
                "EndDate" => $schedule->StartDate,
                "ScheduleString" => "10/10/10/10/10",
            ]);
        $response->assertLocation("/");

        //Test as counsellor
        $user = $this->CreateCounsellor();
        $schedule->CounsellorID = $user->id;
        $schedule->save();

        $response = $this->actingAs($user)->post("/schedules/update",
            [
                "id" => $schedule->id,
                "StartDate" => $schedule->EndDate,
                "EndDate" => $schedule->StartDate,
                "ScheduleString" => "10/10/10/10/10",
            ]);
        $response->assertLocation("/schedules/show");

        //Test invalid paramaters
        $response = $this->actingAs($user)->post("/schedules/update",
            [
                "id" => "hello world",
                "StartDate" => $schedule->EndDate,
                "EndDate" => $schedule->StartDate,
                "ScheduleString" => "hello world",
            ]);
            $response->assertSessionHasErrors(["id", "ScheduleString"]);

        //Ensure counsellors cannot edit another counsellors schedule
        $user2 = $this->CreateCounsellor();
        $response = $this->actingAs($user2)->post("/schedules/update",
            [
                "id" => $schedule->id,
                "StartDate" => $schedule->EndDate,
                "EndDate" => $schedule->StartDate,
                "ScheduleString" => "10/10/10/10/10",
            ]);
        $response->assertStatus(403);
    }

    /**
     * Test delete endpoint
     * 
     * Endpoint: POST /schedules/delete
     */
    public function test_Delete()
    {
        $schedule = factory(Schedule::class)->create();

        //Test as client
        $user = $this->CreateClient();
        $response = $this->actingAs($user)->get("/schedules/delete?id=".
            $schedule->id);
        $response->assertLocation("/");

        //Test as counsellor
        $user = $this->CreateCounsellor();
        $schedule->CounsellorID = $user->id;
        $schedule->save();
        $response = $this->actingAs($user)->get("/schedules/delete?id=".
            $schedule->id);
        $response->assertLocation("/schedules/show");

        //Test as another counsellor
        $schedule = factory(Schedule::class)->create();
        $schedule->CounsellorID = $user->id;
        $schedule->save();
        $user2 = $this->CreateCounsellor();
        $response = $this->actingAs($user2)->get("/schedules/delete?id=".
            $schedule->id);
        $response->assertStatus(403);
    }
}
