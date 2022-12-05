@extends('layouts.app')

@section('content')

<div class="container">
    <div class="col-sm-12 center-block text-center">
        <h2>Your Appointments</h2>
        <p>Edit or Cancel Your Appointments Here</p>

        @if(!$appointments->isEmpty())

        @switch(auth()->user()->role)

        @case('Counsellor')
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Time</th>
                    <th scope="col">Client</th>
                    <th scope="col">Notes</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr>
                    <th scope="row">{{$appointment->date}}</th>
                    <td>{{date('h:i a', strtotime($appointment->time . ':00'))}}</td>
                    <td>{{$appointment->client->name}}</td>
                    <td>{{$appointment->notes}}</td>
                    <td>
                        {{-- <button type="button" class="btn btn-small btn-primary mr-2">Change</button> --}}
                        <form action="/appointments/{{$appointment->id}}" method="POST">
                            @csrf
                            <input name="_method" type="hidden" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-soft-danger">Cancel</button>
                        </form>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
        @break

        @case('Client')
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Time</th>
                    <th scope="col">Counsellor</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr>
                    <th scope="row">{{$appointment->date}}</th>
                    <td>{{date('h:i a', strtotime($appointment->time . ':00'))}}</td>
                    <td><a href='/users/show/{{$appointment->counsellor->id}}'>{{$appointment->counsellor->name}}</a>
                    </td>
                    <td>
                        <a href="/appointments/edit/{{$appointment->id}}" class="btn btn-sm btn-soft-primary mb-2">Change</a>

                        <form action="/appointments/{{$appointment->id}}" method="POST">
                            @csrf
                            <input name="_method" type="hidden" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-soft-danger">Cancel</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @default
        {{-- Do nothing here  --}}
        @endswitch


        @else
        <p style="color: red">No Appointments To Show</p>
        @endif
    </div>
</div>
@endsection