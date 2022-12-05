@extends('layouts.app')

@section('content')

@php 

$color = count($users) == 0 ? "lime" : "red";
@endphp

<div class="container">


    <h1>Verification Requests <span style="color:{{$color}}; font-weight:bold">{{count($users)}}</span></h1>
    <a class="btn btn-primary" href="/admin/verify">View</a>
</div>

@endsection