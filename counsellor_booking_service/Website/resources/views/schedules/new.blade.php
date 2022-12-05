@extends('layouts.app') 


@section('scripts')
@endsection

@section('content')
<div class="container">
<a href="/schedules/show">< Back to Schedules</a>

    <h1>New Schedule</h1>

<p>Please specify a Monday for the schedule to start on, a Friday for the schedule to end on, and the hours you are available for each day.</p>

        <form method="post" action="/schedules/create" class="m-auto d-table">
            <div>
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" min="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}" id="DateStart" name="startDate">
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" min="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}" id="DateEnd" name="endDate">
                </div>
                <input class="btn btn-primary m-auto d-table" type="submit" value="Create">
            </div>
        </form>
</div>
@endsection