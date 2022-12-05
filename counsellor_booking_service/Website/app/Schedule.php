<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Model class representing the weekly appointment availability of a counsellor.
 * 
 * Counsellors can specify different schedules for different times of the year using the start date and end date variables. Appointments are assumed to be 1-hour long and thus 
 * counsellors can specify their daily availability in 1-hour blocks.
 */
class Schedule extends Model
{
    /**
     * Denotes the name of the database table to use.
     * 
     * @var string
     */
    protected $table = "schedules";

    /**
     * Denotes that timestamps should not be included in the DB table.
     */
    public $timestamps = false;
    
    /**
     * Eloquent member for storing column names.
     * 
     * Eloquent uses this member to determine which columns are meant to be in the table. In this case the array contains CounsellorID, StartDate, EndDate, and ScheduleString.
     * This should not be manipulated directly.
     * 
     * @var Array
     */
    protected $fillable =[
        "CounsellorID", "StartDate","EndDate","ScheduleString"
    ];

    /**
     * Used to specify the default values of columns.
     * 
     * @var Array
     */
    protected $attributes =[
                "StartDate" => "2020-01-01",
        "EndDate" => "2021-01-01",
        "ScheduleString" => ""
    ];

    /**
     * Helper function used to get the daily availability arrays.
     * 
     * Parses the ScheduleString and returns an array containing 5 arrays each containing the hours of the day where the counsellor is available. 
     * 
     * @return Array
     */
    public function GetTimeslots(){
        $result = array();
        $dayArray = explode("/",$this->ScheduleString);
        foreach($dayArray as $day){
            $hourArray = explode(",",$day);
            if($hourArray[0] == ""){
                array_push($result,array());
            }
            else{
            array_push($result,$hourArray);
            }
        }
        return $result;
    }
}
