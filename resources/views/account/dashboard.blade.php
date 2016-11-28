@extends('master')
@section('title', 'Dashboard')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2><i class="fa fa-cogs" aria-hidden="true"></i> Account Information</h2>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <tr>
                            <td>Username</td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td>E-mail</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                    </table>
                    <a href="/account/edit" class="btn btn-raised btn-warning btn-block">
                        <i class="fa fa-cog" aria-hidden="true"></i> Edit Account Information
                    </a>
                </div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h2><i class="fa fa-calendar" aria-hidden="true"></i> Scheduled Shifts</h2>
                </div>
                <div class="panel-body">
                    @if($shifts)
                        <table class="table table-hover">
                            <thead>
                            <th>Date</th>
                            <th>Store</th>
                            </thead>
                            <tbody>
                            @foreach($shifts as $shift)
                                <tr>
                                    <td>
                                        {{ date('l F jS, h:ia', strtotime($shift->start)) }} -
                                        {{ date('h:i:a', strtotime($shift->end)) }}
                                    </td>
                                    <td>{{ config('store.stores')[$shift->store] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <h3>You are not on the schedule for this week.</h3>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h2>
                        <i class="fa fa-newspaper-o" aria-hidden="true"></i> Announcements
                        <a href="/announcements/create" class="btn btn-success btn-raised pull-right">
                            <i class="fa fa-plus-square" aria-hidden="true"></i> Create New Announcement
                        </a>
                    </h2>
                </div>
                <div class="panel-body">
                    @foreach($announcements['sticky'] as $announcement)
                        <div class="well">
                            <div class="media">
                                <div class="pull-left" href="#">
                                    <i class="fa fa-thumb-tack fa-2x" aria-hidden="true"></i>
                                </div>
                                <div class="media-body">
                                    <h3 class="media-heading">
                                        {{ $announcement->title }}
                                        <small class="text-right">
                                            By {{ $announcement->user->name }}
                                            @if(Auth::user()->id == $announcement->user_id)
                                                <a href="/announcements/{{ $announcement->id }}/edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            @endif
                                        </small>
                                        <small class="pull-right">
                                            <span><i class="fa fa-calendar"></i>
                                                {{ DateHelper::timeElapsed($announcement->created_at) }}
                                            </span>
                                        </small>
                                    </h3>
                                    <p>{!! $announcement->content !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @foreach($announcements['standard'] as $announcement)
                            <div class="well">
                                <div class="media">
                                    <div class="pull-left" href="#">
                                        <i class="{{ config('store.announcement_types')[$announcement->type]['icon'] }}" aria-hidden="true"></i>
                                    </div>
                                    <div class="media-body">
                                        <h3 class="media-heading">
                                            {{ $announcement->title }}
                                            <small class="text-right">
                                                By {{ $announcement->user->name }}
                                                @if(Auth::user()->id == $announcement->user_id)
                                                    <a href="/announcements/{{ $announcement->id }}/edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                @endif
                                            </small>
                                            <small class="pull-right">
                                            <span><i class="glyphicon glyphicon-calendar"></i>
                                                {{ DateHelper::timeElapsed($announcement->created_at) }}
                                            </span>
                                            </small>
                                        </h3>
                                        <p>{!! $announcement->content !!}</p>
                                    </div>
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush