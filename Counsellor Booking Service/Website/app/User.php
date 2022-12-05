<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model class representing a logged-in (non-anonymous) user of the website.
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * Eloquent member for storing column names.
     * 
     * Eloquent uses this member to determine which columns are meant to be in the table. In this case the array contains name, email, password, requested_verification, and biography.
     * This should not be manipulated directly.
     * 
     * @var Array
     */
    protected $fillable = [
        'name', 'email', 'password', "requested_verification", 'biography'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var Array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var Array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Determines the default values to be assigned to the specified columns.
     * 
     * @var Array
     */
    protected $attributes = [
        "role" => "Client",
        "requested_verification" => 0
    ];

    /**
     * Foreign key relationship representing all appointments belonging to a user.
     * 
     * @return Array
     */
    public function appointment() 
    {
        return $this->hasMany('App\Appointment');
    }
}
