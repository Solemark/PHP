<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateClient()
    {
        $client = $this->client();
        $this->assertTrue($client->save(), "Couldn't create client.");
        $response = $this->actingAs($client)->get('/appointments/new');
        $response->assertLocation('/');        
    }

    public function testCreateCounsellor()
    {
        $counsellor = $this->counsellor();
        $this->assertTrue($counsellor->save(), "Couldn't create Counsellor.");
        $response = $this->actingAs($counsellor)->get('/schedules/show');
        $response->assertLocation('/');
    }

    public function testCreateAdmin()
    {
        $admin = $this->admin();
        $this->assertTrue($admin->save(), "Couldn't create Admin.");
        $response = $this->actingAs($admin)->get('/admin/verify');
        $response->assertLocation('/');
    }

    public function testUpdateClientDetails()
    {
        $response = $this->actingAs($this->client())->post('/users/update', $this->clientData());
        $client = User::where('id', '1')->first();
        $this->assertEquals('clientName', $client->name);
        $this->assertEquals('client@email.com', $client->email);
        $this->assertEquals('clientBiography', $client->biography);  
    }

    public function testUpdateCounsellorDetails()
    {
        $response = $this->actingAs($this->counsellor())->post('/users/update', $this->counsellorData());
        $counsellor = User::where('id', '1')->first();
        $this->assertEquals('counsellorName', $counsellor->name);
        $this->assertEquals('counsellor@email.com', $counsellor->email);
        $this->assertEquals('counsellorBiography', $counsellor->biography);
    }

    public function testClientViewAllCounsellors()
    {
        $client = $this->client();
        $response = $this->actingAs($client)->get('/users/list');
        $response->assertLocation('/');
    }

    public function testCounsellorViewAllCounsellors()
    {
        $counsellor = $this->counsellor();
        $response = $this->actingAs($counsellor)->get('/users/list');
        $response->assertLocation('/');
    }

    public function testClientViewSearchedResults()
    {
        $counsellor = $this->counsellor();
        $response = $this->actingAs($this->client())->post('/users/search', ['search' => $counsellor->name]);
        $response->assertViewHas('counsellors');
    }

    public function testCounsellorViewSearchedResults()
    {
        $counsellor = $this->counsellor();
        $response = $this->actingAs($this->counsellor())->post('/users/search', ['search' => $counsellor->name]);
        $response->assertViewHas('counsellors');
    }

    private function client()
    {
        $client = factory(User::class)->create();
        $client->role = 'Client';
        $client->save();
        return $client;
    }

    private function counsellor()
    {
        $counsellor = factory(User::class)->create();
        $counsellor->role = 'Counsellor';
        $counsellor->save();
        return $counsellor;
    }

    private function admin()
    {
        $admin = factory(User::class)->create();
        $admin->role = 'Admin';
        $admin->save();
        return $admin;
    }

    private function clientData()
    {
        return [
            'id' => '1',
            'name' => 'clientName',
            'email' => 'client@email.com',
            'password' => 'clientPassword',
            'biography' => 'clientBiography'
        ];
    }

    private function counsellorData()
    {
        return [
            'id' => '1',
            'name' => 'counsellorName',
            'email' => 'counsellor@email.com',
            'password' => 'counsellorPassword',
            'biography' => 'counsellorBiography'
        ];
    }
}