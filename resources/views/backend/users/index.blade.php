@extends('master')
@section('title', 'All users')
@section('content')
    <div class="container col-md-10 col-md-offset-1">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    All users
                    @if (!app('request')->input('trashed'))
                        <a href="/admin/users?trashed=true" class="btn btn-raised btn-info pull-right">View Deleted Users</a>
                    @endif
                    <a href="/admin/users/create" class="btn btn-raised btn-success pull-right">Create a new User</a>
                </h2>
            </div>
            @if ($users->isEmpty())
                <p> There is no user.</p>
            @else
                <table class="table table-hover clickable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Store #</th>
                        <th>Joined at</th>
                        @if (app('request')->input('trashed'))
                            <th>Deleted at</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr class="{{ ($user->trashed()) ? 'danger' : '' }}" data-id="{{ $user->id }}">
                            <td>{!! $user->id !!}</td>
                            <td>
                                <a href="{!! action('Admin\UsersController@edit', $user->id) !!}">{!! $user->name !!} </a>
                            </td>
                            <td>{!! $user->email !!}</td>
                            <td>{!! $user->store !!}</td>
                            <td>{!! $user->created_at !!}</td>
                            @if (app('request')->input('trashed'))
                                <td>{{ $user->deleted_at }}</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#table').DataTable( {
            "paging": false,
            "info" : false,
            "order" : [[ 1, "desc" ]]
        });
    });
    $('tbody').on('click', 'tr', function() {
        var url = '/admin/users/' + $(this).attr('data-id') + '/edit';
        window.location.href = url;
    });
</script>
@endpush