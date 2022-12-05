<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\User;

/**
 * Model class repsenting a valid appointment made by a client.
 */
class Appointment extends Model
{
    /**
     * Eloquent member for storing column names.
     * 
     * Eloquent uses this member to determine which columns are meant to be in the table. In this case the array contains client_id, counsellor_id, date, time, and notes. 
     * This should not be manipulated directly.
     * 
     * @var Array
     */
    protected $fillable = [
        //might have to add "id", "created_at", "updated_at", to beginning of table???
         "client_id", "counsellor_id", "date", "time", "notes",
    ];

    /**
     * Foreign key relationship representing the client who made the appointment.
     * 
     * @return User
     */
    public function client() 
    {
        return $this->belongsTo('App\User', 'client_id');
    }

    /**
     * Foreign key relationship representing the counsellor who the appointment is with.
     * 
     * @return User
     */
    public function counsellor()
    {
        return $this->belongsTo('App\User', 'counsellor_id');
    }
}
