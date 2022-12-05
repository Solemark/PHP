@extends('layouts.app') @section('content') @php $color = count($users) == 0 ? "lime" : "red"; @endphp

<script src="/js/admin/verify.js"></script>

<div class="container">
    <h1>Verification Requests
        <span id="UserCount" style="color:{{$color}}; font-weight:bold">{{count($users)}}</span>
    </h1>

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Approve</th>
            <th>Deny</th>
        </tr>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td><button class="btn btn-primary" onclick="approve_clicked(this);" data-id="{{$user->id}}">Approve</button></td>
                    <td><button class="btn btn-danger" onclick="deny_clicked(this);" data-id="{{$user->id}}">Deny</button></td>
                </tr>
            @endforeach
        </tbody>
    </thead>

</table>
</div>

@endsection