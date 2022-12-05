<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * Controller class for performing account management tasks.
 */
class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware("roles:Counsellor")->except('show');
    }

    /**
     * GET endpoint for displaying the account management page.
     * 
     * Returns the index view populated with the current user.
     * 
     * @param User $user The currently logged in user.
     * 
     * @return View
     */
    public function show(User $user)
    {
        if ($user->role == 'Counsellor') {
            return view('users.view')->with('counsellor', $user);
        } else {
            return redirect('/');
        }
    }

    /**
     * GET endpoint for displaying the counsellor search page.
     * 
     * @return View
     */
    public function searchBy()
    {
        return view('users.search');
    }


    /**
     * GET endpoint for performing a keyword search.
     * 
     * Searches through all counsellors in the database and returns those whose biographies or names contain at least a partial match for the search term. 
     * Returns a view containing all counsellors that match the search.
     * 
     * @param Request $request HTTP request object containing the search term.
     * 
     * @return View.
     */
    public function searchByResults(Request $request)
    {
        $searchString = trim($request->input('search'));
        if (strlen($searchString) > 0) {    
            //concatenate wildcards to string
            $searchTerm = '%' . trim($request->input('search')) . '%';
        }
        else
        {
            return $this->showAllCounsellors(); //try to limit unintended behaviours
            
        }
        $counsellors = User::select
            ('id', 'name', 'email', DB::raw('substr(biography, 0, 20) as biography'))
            ->where('role', 'Counsellor')
            ->where('biography', 'LIKE', $searchTerm)
            ->orWhere('name', 'LIKE', $searchTerm)
            ->orderBy('id')    
            ->get();
        return view('users.list')->with('counsellors', $counsellors);
    }

    /**
     * GET endpoint for displaying the all counsellors page.
     * 
     * Returns a view populated with each counsellor and their details.
     * 
     * @return View
     */
    public function showAllCounsellors()
    {
        $counsellors = User::where('role', 'Counsellor')
            ->select('id', 'name', 'email', DB::raw('left(biography, 20) as biography'))
            ->get();
        return view('users.list')->with('counsellors', $counsellors);
    }

    /**
     * GET endpoint for displaying the edit user page.
     * 
     * @param User $user The currently logged-in user.
     * 
     * @return View
     */
    /*public function edit(User $user)
    {
        return view('users.edit')->with('counsellor', $user);
    }*/

    /**
     * GET endpoint for displaying the profile overview page.
     * 
     * Returns the main profile view which allows users to edit their details.
     * 
     * @param User $user The currently logged-in user.
     * 
     * @return View
     */
    public function profile(User $user)
    {
        $user = User::where('id', auth()->user()->id)->first();

        return view('users.profile')->with('user', $user);
    }

    /**
     * POST endpoint for updating user details.
     * 
     * Updates the user details based on the inputs provided. Redirects to the profile overview page upon completion.
     * 
     * @param Request $request HTTP request object containing the user ID, password, name, email, and biograpy paramaters.
     * 
     * @return View
     */
    public function update(Request $request)
    {
        $success = false;
        $user = User::where('id', $request->input('id'))->first();
        if (!empty($user)) {
            if ($request->input("password") != null) {
                $user->password = Hash::make($request->input("password"));
            }

            $user->name = $request->input("name");
            $user->email = $request->input("email");
            if (auth()->user()->id == $request->input('id'))
                $user->biography = $request->input("biography");

            if ($user->save())
                $success = true;
        }
        return view('users.profile')->with('user', $user)->with('success', $success);
    }
}
