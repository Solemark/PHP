@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">
        <p>If you can not find a counsellor that matches your needs, please try using our search feature.</p>
    </div>
    <div class="row">
        <div class="form-group">
            <form method='post' action='/users/search'>
                <label class="form-label text-left"><span class="fa fa-info-circle" data-placement="left"></span>Terms</label>
                <input type="text" class="form-control" name="search" id="name" value="">
                <button class="btn btn-sm btn-soft-success mt-5" type="submit" name="Search" value="Submit">Search</button>
            </form>
        </div>
    </div>



</div>

@endsection