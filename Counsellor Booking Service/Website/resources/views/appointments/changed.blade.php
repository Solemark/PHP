@extends('layouts.app')

@section('content')

<div class="container">

    <div class="col-sm-12 center-block text-center">
        <h2 class="text-warning">Appointment Changed</h2>
        <p>Your appointment has been changed. Here are the new details; </p>
        <br>
        <p>Date: {{ $appointment->date }}</p>
        <p>Time: {{ date('h:i a', strtotime($appointment->time . ':00')) }}</p>
        <p>Counsellor: {{ $appointment->counsellor->name }}</p>
        
    </div>

    <div class="row">
        <div class="col-sm-6 center-block text-center mt-5">
            <a href="/" class="btn btn-sm btn-soft-success btn-block font-weight-normal ml-3 transition-3d-hover">Go Home</a>
        </div>

        <div class="col-sm-6 center-block text-center mt-5">
            <a href="/appointments/show"  class="btn btn-sm btn-soft-primary btn-block font-weight-normal ml-3 transition-3d-hover">Show Appointments</a>
        </div>
    </div>

</div>

@endsection