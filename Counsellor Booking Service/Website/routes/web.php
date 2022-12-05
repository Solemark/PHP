<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Home page
Route::get('/home', 'HomeController@index')->name('home');

// Scheduling routes
Route::get("/schedules/show","SchedulesController@Show");
Route::get("/schedules/update","SchedulesController@Update_Get");
Route::get("/schedules/new","SchedulesController@NewSchedule");
Route::post("/schedules/update","SchedulesController@Update_Post");
Route::post("/schedules/create", "SchedulesController@Create");
Route::get("/schedules/delete","SchedulesController@Delete");

// Appointment routes 
Route::post('/appointments', 'AppointmentsController@store');
Route::delete('appointments/{appointment}', 'AppointmentsController@destroy');
Route::get('/appointments/new', 'AppointmentsController@create');
Route::get('/appointments/show', 'AppointmentsController@all');
Route::get('/appointments/edit/{appointment}', 'AppointmentsController@edit');
Route::get("/appointments/getavailabletimeslots","AppointmentsController@GetAvailableTimeslots");


// User routes
Route::get('/users/show/{user}', 'UsersController@show');
Route::get('/users/list', 'UsersController@showAllCounsellors');
Route::get('/users/search', 'UsersController@searchBy');
Route::post('/users/search', 'UsersController@searchByResults');
Route::get('/users/profile', 'UsersController@profile');
Route::post('/users/update', 'UsersController@Update');

// Authentication Routes
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Admin pages
Route::get("/admin","AdminController@Index");
Route::get("/admin/verify","AdminController@Verify_Get");
Route::post("/admin/verify","AdminController@Verify_Post");
Route::post("/admin/deny","AdminController@Deny_Post");
