@extends('layouts.app')

@section('content')

<div class="container">

    <div class="col-sm-12 center-block text-center">

        <h2>{{$counsellor->name ?? ''}}</h2>
        <br>
        <p>Email: <a href="mailto:{{$counsellor->email}}">{{$counsellor->email}}</a></p>
        <hr class="mt-10 my-0">
        <h5 class="h5 mt-5 mb-5">Biography</h5>
        <p>{{$counsellor->biography}}</p>


    </div>

</div>

@endsection