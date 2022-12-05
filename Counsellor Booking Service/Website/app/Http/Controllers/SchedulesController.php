<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Schedule;
use Illuminate\Http\Request;

/**
 * Controller class for handling schedule related tasks.
 *
 * Allows counsellors to specify their appointment availability.
 */
class SchedulesController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        $this->middleware("roles:Counsellor"); //Restrict actions to counsellors
    }

    /**
     * GET endpoint for displaying all schedules for a counsellor.
     *
     * Returns the show schedule page populated with all schedules for a specified counsellor.
     *
     * @param Request $request HTTP request object containing the ID of the user to display schedules for.
     *
     * @return View
     */
    public function Show(Request $request)
    {
        $counsellorID = $request->user()->id;
        $counsellorName = $request->user()->name;
        $schedules = Schedule::where("CounsellorID", $counsellorID)->get();
        return view("schedules.show",
        [
            "schedules" => $schedules,
            "name" => $counsellorName
        ]);
    }

    /**
     * GET endpoint for displaying the new schedule page.
     *
     * Returns the new schedule view.
     *
     * @param Request $request HTTP request object.
     *
     * @return View
     */
    public function NewSchedule(Request $request)
    {
        return view("schedules.new");
    }

    /**
     * POST endpoint for creating a new schedule.
     *
     * Creates a new schedule with the specified counsellor ID, start date, and end date. Redirects to the edit schedule page after completion.
     *
     * @param Request $request HTTP request object containing the counsellor ID, start date, and end date.
     *
     * @return View
     */
    public function Create(Request $request)
    {
        $request->validate([
            "startDate" => "required|date",
            "endDate" => "required|date",
        ]);

        $counsellorID = $request->user()->id;
        $startDate = $request->input("startDate");
        $endDate = $request->input("endDate");
        $result = Schedule::create(
            [
                "CounsellorID" => $counsellorID,
                "StartDate" => $startDate,
                "EndDate" => $endDate
            ]);
        return redirect("/schedules/update?id=" . $result->id);
    }

    /**
     * GET endpoint for retrieving the update schedule page.
     *
     * Returns the update view for the specified schedule.
     *
     * @param Request $request HTTP request object containing the ID of the schedule to be updated.
     *
     * @return View
     */
    public function Update_Get(Request $request)
    {
        $id = $request->input("id");
        $result = Schedule::where("id", $id)->first();
        if ($request->user()->id != $result->CounsellorID) {
            abort(403);
        }
        return view("schedules.update", ["schedule" => $result]);
    }

    /**
     * POST endpoint for updating a schedule.
     *
     * Updates the specified schedule with the new details in the request. Redirects to the all schedules page upon completion.
     *
     * @param Request $request HTTP request containing the schedule ID, start date, end date, and specially formatted schedule string.
     *
     * @return View
     */
    public function Update_Post(Request $request)
    {
        $request->validate([
            "id" => "required|integer",
            "StartDate" => "required|date",
            "EndDate" => "required|date",
            "ScheduleString" => [
                "required", "string", 
                "regex:/(\d+,|\d+)*\/(\d+,|\d+)*\/(\d+,|\d+)*\/(\d+,|\d+)*\/(\d+,|\d+)*/"
            ],
        ]);

        $schedule = Schedule::where("id", $request->input("id"))->first();
        if ($request->user()->id != $schedule->CounsellorID) {
            abort(403);
        }
        $schedule->StartDate = $request->input("StartDate");
        $schedule->EndDate = $request->input("EndDate");
        $schedule->ScheduleString = $request->input("ScheduleString");
        $schedule->save();
        return redirect("/schedules/show");
    }

    /**
     * DELETE endpoint for deleting a specified schedule.
     *
     * Deletes the schedule from the database specified by the schedule ID. Redirects to the all schedules page upon completion.
     *
     * @param Request $request HTTP request object containing the ID of the schedule to be deleted.
     *
     * @return View
     */
    public function Delete(Request $request)
    {
        $request->validate([
            "id" => "required|integer",
        ]);

        $scheduleID = $request->input("id");
        $result = Schedule::where("id", $scheduleID)->first();
        if ($result == null) {
            return redirect("/schedules/show");
        }

        if ($request->user()->id != $result->CounsellorID) {
            abort(403);
        }
        $result->delete();
        return redirect("/schedules/show");
    }

}
