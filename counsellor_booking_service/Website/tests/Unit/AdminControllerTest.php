<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\User;
use App\Http\AdminController;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use DatabaseMigrations;

  
    /**
     * Creates an Admin user
     * 
     */
    public function CreateAdmin()
    {
        $result = factory(User::class)->create();
        $result->role = "Admin";
        
        $this->assertTrue($result->save(), "Couldn't create Admin member");
      
        
        return $result;
    }

    /**
     * Creates a counsellor
     */
    public function CreateCounsellor()
    {
        $result = factory(User::class)->create();
        $result->role = "Counsellor";

        $this->assertTrue($result->save(), "Couldn't create Counsellor");

        return $result;
    }

    /**
     * Creates a Client with requested Counsellor privs
     */
    public function ClientRequestCounsellor()
    {
        $result = factory(User::class)->create();
        $result->role = "Client"; //just to force in case sqlite database does anythign weird.
        $result->requested_verification = 1;
        
        $this->assertTrue($result->save(), "Couldn't create client");

        return $result;
    }

    /**
     * Test access privis for users as authentication as Admin
     * 
     */
    public function test_access_to_locations_as_admin()
    {
        $user = $this->CreateAdmin();
        //test portions of the site that is protected from Admin view (everything bar admins)
        $response = $this->actingAs($user)->get('/schedules/show');
        $response->assertLocation('/');

        $response = $this->actingAs($user)->get('/schedules/new');
        $response->assertLocation('/');

        //should be able to see even as an admin.
        $response = $this->actingAs($user)->get('/users/profile');
        $response->assertOk();

        //can't see biography data
        $response->assertDontSeeText('biography');

        

    }

    /**
     * Test access privs as a non admin to admin
     * protected locations
     */
    public function test_access_to_locations_as_not_admin()
    {
        //test as counsellor
        $userCounsellor = $this->CreateCounsellor();

        $response = $this->actingAs($userCounsellor)->get('/admin');
        $response->assertStatus(302);
        $response->assertLocation('/');

        $response = $this->actingAs($userCounsellor)->get('/admin/verify');
        $response->assertStatus(302);
        $response->assertLocation('/');

        //test as client
        $userClient = $this->ClientRequestCounsellor();
        $response = $this->actingAs($userClient)->get('/admin');
        $response->assertStatus(302);
        $response->assertLocation('/');

        $response = $this->actingAs($userClient)->get('/admin/verify');
        $response->assertStatus(302);
        $response->assertLocation('/');

        //test as nonauthenticated.
        $response = $this->get('/admin');
        $response->assertStatus(302);
        $response->assertLocation('/');
    }


    /**
     * Test whether client who has requested to be a Counsellor has been shown
     */
    public function test_approve_verify_request()
    {
        $userAdmin = $this->CreateAdmin();
        $userClient = $this->ClientRequestCounsellor();

        
        //test to see whether can navigate to /admin page
        $response = $this->actingAs($userAdmin)->get('/admin');
        $response->assertOk();

        $response = $this->actingAs($userAdmin)->get('/admin/verify');
        $response->assertOk();
        $response->assertSee('1', 'users');

        $response = $this->actingAs($userAdmin)->post('/admin/verify',
            ['id' => $userClient->id]);
        //can't test this as no return value, but can test get to see if count --
        $response = $this->actingAs($userAdmin)->get('/admin/verify');
        $response->assertSee('0', 'users');
        //$response->assertEquals($userClient->id, $response['id']);
        //$response->();

    }

    /**
     * Thest whether the admin can deny a requested counsellor
     */
    public function test_deny_verify_request()
    {
        $userAdmin = $this->CreateAdmin();
        $userClient = $this->ClientRequestCounsellor();

        $response = $this->actingAs($userAdmin)->get('/admin/verify');
        $response->assertOk();
        $response->assertSee('1', 'users');

        $response = $this->actingAs($userAdmin)->post('/admin/deny',
            ['id' => $userClient->id]);
        $response = $this->actingAs($userAdmin)->get('/admin/verify');
        $response->assertSee('0', 'users');

    }
}
