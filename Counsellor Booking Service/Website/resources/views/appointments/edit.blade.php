@extends('layouts.app')

@section('scripts')
<script src="/js/appointment/edit.js"></script>
@endsection

@section('content')

<div class="container">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(!$counsellors->isEmpty())
    <form action="/appointments" method="POST">
        <input id="appointmentID" name="id" type="hidden" value="{{$appointment == null ? -1 : $appointment->id}}" />
        <input name="client_id" type="hidden" value="{{auth()->user()->id}}" />
        <div class="col-sm-12 center-block text-center mt-5">
            <div class="col-md" style="margin: auto">
                <div class="form-group">
                    <label class="form-label text-left"><span class="fa fa-info-circle"
                            data-placement="top"></span>Select Counsellor</label>
                    <select class="form-control" name="counsellor_id" id="txtCounsellor"
                        size="{{count($counsellors) + 1}}">
                        @foreach($counsellors as $counsellor)
                        <option value="{{$counsellor->id}}">{{$counsellor->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label text-left"><span class="fa fa-info-circle"
                            data-placement="top"></span>Enter Date</label>
                    <input type="date" name="date" class="form-control" id="dateSelect"
                        onchange="appointmentDate_changed(this)"
                        value="{{$appointment == null ? '' : $appointment->date}}">
                </div>
                <div class="form-group">
                    <label class="form-label text-left"><span class="fa fa-info-circle"
                            data-placement="top"></span>Select Time</label>
                    <select class="form-control" name="time" id="selectTime"
                        data-time="{{$appointment == null ? '-1' : $appointment->time}}">

                    </select>
                    <div class="text-danger d-none" id="timeError">There are no appointments available on this date.
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label text-left"><span class="fa fa-info-circle"
                            data-placement="top"></span>Enter Notes</label>
                    <textarea name="notes" class="form-control"
                        value=""> {{$appointment == null ? '' : $appointment->notes}} </textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-sm btn-soft-success my-2 my-sm-0" type="submit" name="submit"
                        value="submit">Submit</button>
                    <p class="small mt-5">The standard consultion fee is $65.00. This is subject to change at any time.  </p>
                </div>


            </div>
        </div>
    </form>
    @else
    <p>There is an error with the system. Please contact the admin. </p>
    @endif

</div>
@endsection