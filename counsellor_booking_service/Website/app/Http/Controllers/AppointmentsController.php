<?php

namespace App\Http\Controllers;

use App\Appointment;
use App\Http\Controllers\Controller;
use App\Schedule;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/**
 * Controller class for handing appointment related tasks.
 * 
 * Provides endpoints for retrieving appointment booking views and creating new appointments.
 */
class AppointmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    /**
     * GET endpoint for retrieving the new appointment page.
     * 
     * Returns the new appointment view populated with all counsellors.
     * 
     * @return View
     */
    public function create()
    {
        $counsellors = User::where('role', 'Counsellor')->get();

        // return $counsellors;
        return view("appointments.edit", ['counsellors' => $counsellors, "appointment" => null]);
    }

    /**
     * GET endpoint for retrieving the all appointments page.
     * 
     * Returns a view displaying all appointments for a user. If the user is a client, it displays all appointments made by that client. If the user is a counsellor, 
     * it displays all appointments for that counsellor.
     * 
     * @return View
     */
    public function all()
    {
        if (Auth::user()->role == 'Client') {
            // get the appointments for the client
            $appointments = Appointment::where('client_id', auth()->user()->id)->get();
        } elseif (Auth::user()->role == 'Counsellor') {
            // get the appointments for the counsellor
            $appointments = Appointment::where('counsellor_id', auth()->user()->id)->get();
        } else {
            $appointments = null;
        }

        // return view
        return view('appointments.all', compact('appointments'));
    }

    /**
     * POST endpoint for storing a new appointment in the database.
     * 
     * This function creates or updates an appointment depending on what data is submitted. If no ID is specified in the request form, a new appointment will be created, 
     * otherwise the existing appointment will be updated.
     * 
     * @param Request $request The framework provided HTTP request.
     * 
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
        "counsellor_id" => "required|integer",
        "date" => "required|date|after_or_equal:today",
        "time" => "required|integer"
        ]);


        // check the appointment does not exist already
        if (count(Appointment::where([
            ["counsellor_id", "=", $request->input("counsellor_id")],
            ["date", "=", $request->input("date")],
            ["time", "=", $request->input("time")],
        ])->get()) != 0) {
            // return errors
            return $this->create()->withErrors
            (
                ["existing_appointment" => "An appointment already exists for this timeslot."]
            );
        } else {
            if ($request->input("id") == -1) {
                // create appointment
                $appointment = Appointment::create($this->validateRequest());

                // send an email
                $this->sendEmail(
                    $appointment->client->email,
                    $appointment->client->name,
                    'emails.confirmed',
                    [
                        'name' => $appointment->client->name,
                        'date' => $appointment->date,
                        'time' => $appointment->time,
                        'counsellor' => $appointment->counsellor->name,
                    ],
                    'Appointment Confirmed'
                );

                return view('appointments.confirmed', compact('appointment'));
            } else {
                // update the appointment
                $appointment = Appointment::find($this->validateRequest()["id"]);
                $appointment->update($this->validateRequest());

                // send an email
                $this->sendEmail(
                    $appointment->client->email,
                    $appointment->client->name,
                    'emails.changed',
                    [
                        'name' => $appointment->client->name,
                        'date' => $appointment->date,
                        'time' => $appointment->time,
                        'counsellor' => $appointment->counsellor->name,
                    ],
                    'Appointment Changed'
                );

                return view('appointments.changed', compact('appointment'));
            }
        }
    }

    /**
     * DELETE endpoint for removing an appointment from the database
     * 
     * Deletes an existing appointment from the database and sends an email notification to the client notifying them of the cancellation.
     * 
     * @param Appointment $appointment Framework-generated model binding representing the appointment to delete.
     * 
     * @return void
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        // send an email
        $this->sendEmail(
            $appointment->client->email,
            $appointment->client->name,
            'emails.cancelled',
            [
                'name' => $appointment->client->name,
                'date' => $appointment->date,
                'time' => $appointment->time,
                'counsellor' => $appointment->counsellor->name,
            ],
            'Appointment Cancelled'
        );

        return view('appointments.cancelled', compact('appointment'));
    }

    /**
     * GET endpoint for retrieving the edit appointment page.
     * 
     * Returns the edit appointment page populated with the existing appointment and all counsellors.
     * 
     * @param Appointment $appointment Framework-generated model binding representing the appointment to edit.
     * 
     * @return void
     */
    public function edit(Appointment $appointment)
    {
        // find details in DB
        $counsellors = User::where('role', 'Counsellor')->get();

        // return view and pass information - appointment passed in by the route
        return view('appointments.edit')->with(
            compact('appointment', 'counsellors')
        );
    }

    /**
     * GET endpoint for retrieving available timeslots for a specified counsellor and date.
     * 
     * Returns a JSON encoded array of integers representing available appointment times for a given day and time. Returns an empty response if there are no times available. 
     * Designed to be consumed by an AJAX request.
     * 
     * @param Request $request HTTP request containing the counsellor ID and date to search for.
     * 
     * @return Array
     */
    public function GetAvailableTimeslots(Request $request)
    {
        $request->validate([
            "CounsellorID" => "required|integer",
            "Date" => "required|date",
        ]);

        $CounsellorID = $request->input("CounsellorID");
        $Date = new DateTime($request->input("Date"));
        $dayIndex = $Date->format("N") - 1;

        // get the counsellors schedule for this date
        $schedule = Schedule::where([
            ["CounsellorID", "=", $CounsellorID],
            ["StartDate", "<=", $Date],
            ["EndDate", ">=", $Date],
        ])->first();

        if ($schedule == null) {
            return;
        }

        // get the available timeslots for this day of the week
        $hourArray = $schedule->GetTimeslots()[$dayIndex];
        if (count($hourArray) == 0) {
            return;
        }

        $existingAppointmentTimes = Appointment::where([
            ["counsellor_id", "=", $CounsellorID],
            ["date", "=", $Date],
        ])->pluck("time")->toArray();

        $availableTimes = array_diff($hourArray, $existingAppointmentTimes);

        return response()->json($availableTimes);
    }

    /**
     * Internal function to validate that required request parameters are present
     * 
     * Returns an array containing the valid paramaters. If any paramaters fail validation, the previous page is autmatically returned containing the validation errors.
     * 
     * @return Array
     */
    protected function validateRequest()
    {
        return request()->validate([
            'id' => '',
            'counsellor_id' => 'required',
            'client_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'notes' => '',
        ]);
    }

    /**
     * Helper function used to send email using the default mail provider.
     * 
     * @param string $to_email The email address of the recipient
     * @param string $to_name The name of the recipient
     * @param View $view The blade view to be displayed in the body of the message
     * @param Array $data Any data required by the view
     * @param string $subject The text to be sent in the subject line of the email
     * 
     * @return void
     */
    protected function sendEmail($to_email, $to_name, $view, $data, $subject)
    {
        Mail::send($view, $data, function ($message) use ($to_email, $to_name, $subject) {
            $message->to($to_email, $to_name);
            $message->subject($subject);
            $message->from('noreply.7day.bookings@gmail.com', '7Day Psychology Bookings');
        });
    }
}
