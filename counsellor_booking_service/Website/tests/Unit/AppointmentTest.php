<?php

namespace Tests\Unit;

use App\Appointment;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{

    // will setup and tear down database each time test is ran
    use RefreshDatabase;

    public function test_an_appointment_can_be_created()
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs($this->client())->post('/appointments',
            $this->data());

        $this->assertCount(1, Appointment::all());

        $response->assertViewIs('appointments.confirmed');
    }

    public function test_an_appointment_must_have_a_client()
    {
        $response = $this->actingAs($this->client())->post('/appointments',
            array_merge($this->data(), ['client_id' => '']));

        $response->assertSessionHasErrors('client_id');
    }

    public function test_an_appointment_must_have_a_counsellor()
    {
        $response = $this->actingAs($this->client())->post('/appointments',
            array_merge($this->data(), ['counsellor_id' => '']));

        $response->assertSessionHasErrors('counsellor_id');
    }

    public function test_an_appointment_must_have_a_date()
    {
        $response = $this->actingAs($this->client())->post('/appointments',
            array_merge($this->data(), ['date' => '']));

        $response->assertSessionHasErrors('date');
    }

    public function test_an_appointment_must_have_a_time()
    {
        $response = $this->actingAs($this->client())->post('/appointments',
            array_merge($this->data(), ['time' => '']));

        $response->assertSessionHasErrors('time');
    }

    public function test_an_appointment_can_be_deleted()
    {
        $this->actingAs($this->client())->post('/appointments', $this->data());

        $appointment = Appointment::first();
        $this->assertCount(1, Appointment::all());

        $response = $this->delete('/appointments' . '/' . $appointment->id);

        $this->assertCount(0, Appointment::all());
        $response->assertViewIs('appointments.cancelled');
    }

    public function test_an_appointment_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->client())->post('/appointments', $this->data());

        $appointment = Appointment::first();

        $response = $this->post('/appointments', [
            'id' => $appointment->id,
            'counsellor_id' => '1',
            'client_id' => '1',
            'date' => date("Y-m-d",strtotime("next Monday")),
            'time' => '12'
        ]);

        $this->assertEquals('12', Appointment::first()->time);
        $response->assertViewIs('appointments.changed');
    }

    public function test_fail_when_user_not_logged_in_tries_to_create_appointment()
    {
        $response = $this->post('/appointments', $this->data());

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_can_get_clients_appointments_and_no_one_elses()
    {
        $client = $this->client();

        $counsellor = $this->counsellor();

        $this->actingAs($counsellor)->post('/appointments', array_merge($this->data(),
            ['client_id' => $client->id]));
        $this->actingAs($counsellor)->post('/appointments', array_merge($this->data(),
            ['client_id' => $client->id, 'time' => '11']));
        $this->actingAs($counsellor)->post('/appointments', array_merge($this->data(),
            ['client_id' => 5, 'time' => '06']));
        $this->actingAs($counsellor)->post('/appointments', array_merge($this->data(),
            ['client_id' => 6, 'time' => '07']));

        $this->assertCount(2, Appointment::all());

        $response = $this->actingAs($client)->get('/appointments/show');

        $appointments = $this->getResponseData($response, 'appointments');

        $this->assertCount(2, $appointments);   
    }

    public function test_notes_can_be_null()
    {
        $this->actingAs($this->client())->post('/appointments', array_merge($this->data(),
            ['notes' => '']));

        $this->assertCount(1, Appointment::all());

        $appointment = Appointment::first();

        $this->assertEquals(null, $appointment->notes);
    }  

    // FAILS *******************

    public function test_a_counsellor_cannot_access_an_appointment_that_does_not_belong_to_them()
    {
        $client = $this->client(); 
        $counsellor = $this->counsellor();

        $this->actingAs($client)->post('/appointments', array_merge($this->data(),
            ['counesllor_id' => $counsellor->id]));

        $this->actingAs($client)->post('/appointments', array_merge($this->data(),
            ['counesllor_id' => 3, 'time' => 5]));

        $this->assertCount(2, Appointment::all());

        $response = $this->actingAs($counsellor)->get('/appointments/edit/'.
            Appointment::where('id', 1)->first()->id);
        $response->assertOk();

        $response = $this->actingAs($counsellor)->get('/appointments/edit/'.
            Appointment::where('id', 2)->first()->id);
        
        $response->assertStatus(200);
    }

    public function test_a_client_cannot_access_an_appointment_that_does_not_belong_to_them()
    {
        $client = $this->client();

        $counsellor = $this->counsellor();

        $this->actingAs($counsellor)->post('/appointments', array_merge($this->data(),
            ['client_id' => $client->id]));

        $this->actingAs($counsellor)->post('/appointments', array_merge($this->data(),
            ['client_id' => 3, 'time' => 1]));

        $this->assertCount(2, Appointment::all());

        $response = $this->actingAs($client)->get('/appointments/edit/'.
            Appointment::where('id', 1)->first()->id);
        $response->assertOk();

        $response = $this->actingAs($client)->get('/appointments/edit/'.
            Appointment::where('id', 2)->first()->id);
        
        $response->assertStatus(200);
    }    

    public function test_a_date_cannot_be_less_than_today_when_creating()
    {
        $response = $this->actingAs($this->client())->post('/appointments',
            array_merge($this->data(), ['date' => date("Y-m-d",strtotime("yesterday"))]));

        $this->assertCount(0, Appointment::all());
        $response->assertSessionHasErrors('date');
    }

    private function data()
    {
        return [
            'id' => '-1',
            'counsellor_id' => '1',
            'client_id' => '1',
            'date' => date("Y-m-d",strtotime("tomorrow")),
            'time' => '10',
            'notes' => 'Test',
        ];
    }

    private function counsellor()
    {
        $result = factory(User::class)->create();
        $result->role = 'Counsellor';
        $result->save();
        return $result;
    }

    private function client()
    {
        $user = factory(User::class)->create();
        return $user;
    }

    private function getResponseData($response, $key)
    {
        $content = $response->getOriginalContent();

        $content = $content->getData();

        return $content[$key]->all();
    }
}
