@extends('master')
@section('title', 'All Customers')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    All Customers
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-success btn-lg btn-raised pull-right" data-toggle="modal" data-target="#modal">
                        New Customer
                    </button>
                </h2>

            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if ($customers->isEmpty())
                <p> You currently do not have any customers.</p>
            @else
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display" id="table">
                            <thead>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>E-mail</th>
                            </thead>
                            <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td><a href="customers/{{ $customer->id }}/show">{{ $customer->name }}</a></td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->email }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="customerForm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Create new Customer</h4>
                </div>
                <form method="post" action="/customers/create">
                    <div class="modal-body">
                        @foreach ($errors->all() as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                        @endforeach
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="form-group">
                            <label for="name" class="col-lg-2 control-label">Name</label>
                            <div class="col-lg-10 input-group">
                                <input type="text" class="form-control" placeholder="John Doe" id="name" name="name">
                                <span class="help-block">The name of the Customer.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="col-lg-2 control-label">Phone</label>
                            <div class="col-lg-10 input-group">
                                <input type="text" class="form-control" placeholder="6161234567" id="phone" name="phone">
                                <span class="help-block">The customers phone number.</span>
                            </div>
                        </div>
                            <div class="form-group">
                                <label for="email" class="col-lg-2 control-label">E-mail</label>
                                <div class="col-lg-10 input-group">
                                    <input type="text" class="form-control" placeholder="johndoe@gmail.com" id="email" name="email">
                                    <span class="help-block">The customers e-mail.</span>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection