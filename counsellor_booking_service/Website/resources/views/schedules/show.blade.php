@extends('layouts.app') 

@section('scripts')

@endsection 

@section('content')
<div class="container">
    <h1>Schedules for {{$name}}</h1>

    <a class="btn btn-primary mb-2" href="/schedules/new">New</a>

    <table class="w-75 mx-auto">
        <thead>
            <tr class="text-center px-3" style="border:1px solid black;">
                <th class="px-3" style="border:none!important">Start Date</th>
                <th class="px-3" style="border:none!important">End Date</th>
                <th class="px-3" style="border:none!important"></th>
                <th class="px-3" style="border:none!important"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
            <tr style="border:1px solid black">
                <td class="text-center">{{$schedule->StartDate}}</td>
                <td class="text-center">{{$schedule->EndDate}}</td>
                <td class="text-center">
                    <a href="/schedules/update?id={{$schedule->id}}">Edit</a>
                </td>
                <td class="text-center">
                    <a href="/schedules/delete?id={{$schedule->id}}">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection