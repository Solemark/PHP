<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

/**
 * Controller class for handling admin related tasks.
 * 
 * Provides endpoints for managing user account status.
 */
class AdminController extends Controller
{
    function __construct(){
        $this->middleware("auth");
        $this->middleware("roles:Admin");
    }

    /**
     * GET endpoint for retrieving the admin dashboard page,
     * 
     * Returns the index view populated with any accounts which are awaiting counsellor verification.
     * 
     * @return View
     */
    public function Index(){
        $unverifiedUsers =  User::where("requested_verification",1)->get();
        return view("admin/index", ["users"=>$unverifiedUsers]);
    }

    /**
     * GET endpoint for retrieving the user verification page,
     * 
     * Returns the user verification page populated with any accounts awaiting verification,
     * 
     * @return View
     */
    public function Verify_Get(){
        $unverifiedUsers =  User::where("requested_verification",1)->get();
        return view("admin/verify", ["users"=>$unverifiedUsers]);
    }

    /**
     * POST endpoint for verifying user accounts.
     * 
     * Sets the user's role to counsellor and clears the requested verification flag from their account.
     * 
     * @param Request $request HTTP request containing the user ID to verify.
     * 
     * @return void
     */
    public function Verify_Post(Request $request){
        $userID = $request->input("id");
        $user = User::where("id",$userID)->first();
        $user->role = "Counsellor";
        $user->requested_verification = 0;
        $user->save();
    }

    /**
     * POST endpoint for denying user verificaiton requests
     * 
     * Sets the user's role to client and clears the request verification flag from their account
     * 
     * @param Request $request HTTP request containing the user ID to deny.
     * 
     * @return void
     */
    public function Deny_Post(Request $request){
        $userID = $request->input("id");
        $user = User::where("id",$userID)->first();
        $user->role = "Client";
        $user->save();
    }
}
