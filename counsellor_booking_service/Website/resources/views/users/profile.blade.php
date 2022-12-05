@extends('layouts.app')

@section('content')

<div class="container">
    @if (!empty($success))
    <div class="alert alert-success">
        <div>Record successfully updated.</div>
    </div>
    @endif
    @if (auth()->user()->id == $user->id)
    <form action="/users/update" method="POST">
        <input name="id" type="hidden" value="{{auth()->user()->id}}" />

        <div class="form-group">
            <label class="form-label text-left"><span class="fa fa-info-circle" data-placement="top"></span>Name</label>
            <input type="text" class="form-control" name="name" id="name" value="{{$user->name}}">
        </div>

        <div class="form-group">
            <label class="form-label text-left"><span class="fa fa-info-circle" data-placement="top"></span>Email</label>
            <input type="text" class="form-control" name="email" id="email" value="{{$user->email}}">
        </div>

        <div class="form-group">
            <label class="form-label text-left"><span class="fa fa-info-circle" data-placement="top"></span>Password</label>
            <input type="password" class="form-control" name="password" id="password">
        </div>

        @if (auth()->user()->role == "Counsellor")
        <div class="form-group">
            <label class="form-label text-left"><span class="fa fa-info-circle" data-placement="top"></span>Biography</label>
            <textarea class="form-control" name="biography" id="biography">{{$user->biography}}</textarea>
        </div>

        @endif
        <div class="form-group">
            <button class="btn btn-sm btn-soft-success my-2 my-sm-0" type="Save" name="Save" value="Submit">Save</button>
        </div>
    </form>
    @else
    <div>Test</div>
    @endif
</div>

@endsection