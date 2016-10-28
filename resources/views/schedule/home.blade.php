@extends('master')
@section('title', 'Schedule')
@section('content')

    <div id='wrap'>
        <div id='external-events'>
            @if (Auth::check())
                @if (Auth::user()->hasRole('manager'))
                    <h3 class="text-center">Create Shifts</h3>
                    <h4 class="text-center">Store:</h4>
                    <select id="store-select" style="width:100%">
                        @foreach(config('store.stores') as $key => $store)
                            <option value="{{ $key }}">{{ $store }}</option>
                        @endforeach
                    </select>
                    <h4 class="text-center">Employees</h4>
                    <div id='external-events-listing'>
                        @foreach($users as $user)
                            <div
                                    class="fc-event"
                                    data-duration="04:30"
                                    storeid="1"
                                    color="{{ config('store.colors')[$user->id] }}"
                                    user="{{ $user->id }}"
                                    style="background-color:{{ config('store.colors')[$user->id] }}">
                                {{ $user->name }}
                            </div>
                        @endforeach
                    </div>
                    <h4 class="text-center">Clocked Hours</h4>
                    <ul id="clocked">

                    </ul>
                @endif
            @endif
            <button id="clock" class="btn btn-raised btn-info btn-sm">Clock In/Out</button>
            <div id="clock-status">

            </div>
        </div>

        <div id='calendar'></div>

        <div style='clear:both'></div>

    </div>



    <div id="event-actions">
        <div class="panel panel-info">
            <div class="panel-heading">
                Edit Shift
            </div>
            <div class="panel-body">
                <h3>
                    <span id="name"></span>
                    <span class="pull-right" id="shift"></span>
                </h3>
                <form class="form-horizontal" method="post">
                    <input id="shiftid" type="hidden" value="0" />
                    <div class="form-group">
                        <label for="in" class="col-lg-2 control-label">Clock In</label>
                        <div class="col-lg-10">
                            <input class="form-control" type="text" id="in">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="in" class="col-lg-2 control-label">Clock Out</label>
                        <div class="col-lg-10">
                            <input class="form-control" type="text" id="out">
                        </div>
                    </div>
                </form>

                <div class="pull-right">
                    <a id="del" href="" class="btn btn-danger btn-raised">Delete Shift</a>
                    <button id="save" class="btn btn-success btn-raised">Save</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('css')
<link href="/calendar/fullcalendar.min.css" rel="stylesheet" />
<link href="/css/schedule.css" rel="stylesheet" />
@endpush
@push('scripts')
<script src="/calendar/fullcalendar.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script src="/js/schedule.js"></script>
<script src="/js/jquery.number.min.js"></script>
@endpush