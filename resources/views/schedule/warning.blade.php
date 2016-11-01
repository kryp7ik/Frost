@extends('master')
@section('title', 'Warning: Please Clock In')
@section('content')
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h2>You are not clocked in or scheduled today!</h2>
        </div>
        <div class="panel-body">
            <h4>You have either not clocked into your shift or you are not scheduled to work today.  Please
            Speak to a manager.</h4>
        </div>
    </div>
@endsection