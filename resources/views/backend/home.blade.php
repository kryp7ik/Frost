@extends('master')
@section('title', 'Admin Control Panel')
@section('content')
    <div class="container">
        <div class="row banner">
            <div class="col-md-12">
                <div class="list-group">
                    <div class="list-group-item">
                        <div class="row-action-primary">
                            <i class="mdi-social-person"></i>
                        </div>
                        <div class="row-content">
                            <div class="action-secondary"><i class="mdi-social-info"></i></div>
                            <h4 class="list-group-item-heading">Manage User</h4>
                            <a href="/admin/users" class="btn btn-default btn-raised">All Users</a>
                        </div>
                    </div>
                    <div class="list-group-separator"></div>

                </div>
            </div>
        </div>
    </div>
@endsection