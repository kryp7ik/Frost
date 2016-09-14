@extends('master')
@section('name', 'Edit a user')
@section('content')
    <div class="container col-md-6 col-md-offset-3">
        <div class="well well bs-component">
            <form class="form-horizontal" method="post" id="user-form">
                @foreach ($errors->all() as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                @endforeach
                    {!! csrf_field() !!}
                    <fieldset>
                        <legend>Edit user</legend>
                        <div class="form-group">
                            <label for="name" class="col-lg-2 control-label">Name</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="{{ $user->name }}">
                                </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-lg-2 control-label">Email</label>
                                <div class="col-lg-10">
                                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ $user->email }}">
                                </div>
                        </div>
                        <div class="form-group">
                            <label for="store" class="col-lg-2 control-label">Store</label>
                            <div class="col-lg-10">
                                <select class="form-control" name="store">
                                    @foreach(config('store.stores') as $key => $value)
                                        <option value="{{ $key }}" {{ ($user->store == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select" class="col-lg-2 control-label">Role</label>
                                <div class="col-lg-10">
                                    <select class="form-control" id="role" name="role[]" multiple>
                                        @foreach($roles as $role)
                                            <option value="{!! $role->id !!}"
                                                    @if(in_array($role->id, $selectedRoles))
                                                    selected="selected"
                                                    @endif >{!! $role->display_name !!}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-lg-2 control-label">Password</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-lg-2 control-label">Confirm password</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-8 col-lg-offset-4">
                                <a href="/admin/users" class="btn btn-raised btn-default">Cancel</a>
                                <button type="button" class="btn btn-raised btn-danger" data-toggle="modal" data-target="#modal">
                                    <span class="glyphicon glyphicon-trash"></span>
                                    Delete User
                                </button>
                                <button type="submit" class="btn btn-primary btn-raised">Submit</button>
                            </div>
                        </div>
                    </fieldset>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="confirmDelete">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
                <form method="post" action="/admin/store/ingredients/create">
                    <div class="modal-body">
                        <h3>
                            Are you sure you want to delete this user?<br/>
                            <small>Users are not permanently deleted and can be restored</small>
                        </h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <a href="/admin/users/{{ $user->id }}/delete" class="btn btn-danger">Confirm Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection