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
                    @include('account.partials.announcement-list')

                </div>
            </div>
        </div>
    </div>
    @include('account.partials.announcement-modal')
@endsection

@push('scripts')
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector:'textarea',
            theme: 'modern',
            menubar: false,
            statusbar: false,
            plugins: [
                'advlist autolink lists link image',
                'media',
                'emoticons textcolor colorpicker textpattern imagetools codesample toc'
            ],
            toolbar1: 'undo redo | insert | forecolor backcolor emoticons | bold italic | bullist numlist outdent indent | link image',
            image_advtab: true,
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });


        $('.read-more').on('click', function () {
            var id = $(this).attr('data-id');
            $.getJSON('/announcements/' + id + '/show', function(data) {
                var anncmnt = data;
                $('#title').html(anncmnt.title);
                $('#user-name').html(anncmnt.user);
                $('#announcement-content').html(anncmnt.content);
                $('#created-at').html(anncmnt.created);
                $('#announcement-id').val(id);
                $.each(anncmnt.comments, function(key, value) {
                    $('#comments').append('<div class="well"><p>' + value.content + '</p><h6>-' + value.user + '</h6></div>')
                })
            });
            $('#announcement-modal').modal('show');
        });
        $('#post-reply').on('click', function() {
            tinyMCE.triggerSave();
            var replyContent = $('textarea#reply').val();
            $.ajax({
                url: '/announcements/' + $('#announcement-id').val() + '/add-comment',
                type: "POST",
                dataType: "json",
                data: {
                    'content' : replyContent
                }
            }).done(function( json ) {
                if(json == 'success') {
                    $('#comments').append('<div class="well">' + replyContent + '</div>');
                }
            });
        })
    </script>
@endpush

@push('css')
    <style>
        .modal-dialog {
            width: 70%;
        }
    </style>
@endpush