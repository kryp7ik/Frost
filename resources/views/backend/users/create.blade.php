@extends('master')
@section('name', 'Create a user')
@section('content')
    <div class="container col-md-6 col-md-offset-3">
        <div class="well well bs-component">
            <form class="form-horizontal" method="post">
                {!! csrf_field() !!}
                <fieldset>
                    <legend>Create user</legend>
                    <div class="form-group">
                        <label for="name" class="col-lg-2 control-label">Name</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="name" placeholder="Name" name="name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-lg-2 control-label">Email</label>
                        <div class="col-lg-10">
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="store" class="col-lg-2 control-label">Store</label>
                        <div class="col-lg-10">
                            <select class="form-control" name="store">
                                @foreach(config('store.stores') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if(Auth::user()->hasRole('admin'))
                        <div class="form-group">
                            <label for="select" class="col-lg-2 control-label">Role</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="role" name="role[]" multiple>
                                    @foreach($roles as $role)
                                        <option value="{!! $role->id !!}">{!! $role->display_name !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="form-group password">
                        <label for="password" class="col-lg-2 control-label">Password</label>
                        <div class="col-lg-10">
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>
                    <div class="form-group confirm-password">
                        <label for="password" class="col-lg-2 control-label">Confirm password</label>
                        <div class="col-lg-10">
                            <input type="password" class="form-control" name="password_confirmation">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <a href="/admin/users" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection