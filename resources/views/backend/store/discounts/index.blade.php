@extends('master')
@section('title', 'All Discounts')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    All Discounts
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-success btn-lg btn-raised pull-right" data-toggle="modal" data-target="#modal">
                        New Discount
                    </button>
                </h2>

            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @foreach ($errors->all() as $error)
                <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
            @if ($discounts->isEmpty())
                <h3> You currently do not have any discounts.</h3>
            @else
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display" id="table">
                            <thead>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Filter</th>
                            <th>Amount</th>
                            <th>Admin Approval</th>
                            </thead>
                            <tbody>
                            @foreach($discounts as $discount)
                                <tr>
                                    <td><a href="/admin/store/discounts/{{ $discount->id }}/edit">{{ $discount->name }}</a></td>
                                    <td>{{ ucfirst($discount->type) }}</td>
                                    <td>{{ ucfirst($discount->filter) }}</td>
                                    <td>{{ $discount->amount }}</td>
                                    <td>{{ ($discount->approval) ? 'Required' : 'Not Required' }}</td>
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
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="discountForm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Create new Discount</h4>
                </div>
                <form method="post" action="/admin/store/discounts/create">
                    <div class="modal-body">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="form-group">
                            <label for="name" class="col-lg-2 control-label">Name</label>
                            <div class="col-lg-10 input-group">
                                <input type="text" class="form-control" placeholder="Discount Name" id="name" name="name">
                                <span class="help-block">The name of the Discount.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type" class="col-lg-2 control-label">Type</label>
                            <div class="col-lg-10 input-group">
                                <select class="form-control" name="type" style="width:100%">
                                    @foreach($d->typeArray as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block">Choose whether the discount should be a dollar amount or a precentage.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="filter" class="col-lg-2 control-label">Filter</label>
                            <div class="col-lg-10 input-group">
                                <select class="form-control" name="filter" style="width:100%">
                                    @foreach($d->filterArray as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block">Select if the discount will apply to a certain type of product or all products.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="amount" class="col-lg-2 control-label">Amount</label>
                            <div class="col-lg-10 input-group">
                                <input type="text" class="form-control" placeholder="20" id="amount" name="amount">
                                <span class="help-block">The amount of the discount.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="active" class="col-lg-2 control-label">Admin Approval?</label>
                            <div class="togglebutton col-lg-10">
                                <label>
                                    <input type="checkbox" id="approval" name="approval">
                                    <span class="help-block">If activated this discount will require admin approval to use.</span>
                                </label>
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