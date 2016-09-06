@extends('master')
@section('title', 'Edit Product')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h2>Edit Discount</h2>
            </div>
            @foreach ($errors->all() as $error)
                <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
            <form method="post" action="/admin/store/discounts/{{ $discount->id }}/edit">
                <div class="panel-body">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <div class="form-group">
                        <label for="name" class="col-lg-2 control-label">Name</label>
                        <div class="col-lg-10 input-group">
                            <input type="text" class="form-control" placeholder="Discount Name" id="name" name="name" value="{{ $discount->name }}">
                            <span class="help-block">The name of the Discount.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-lg-2 control-label">Type</label>
                        <div class="col-lg-10 input-group">
                            <select class="form-control" name="type" style="width:100%">
                                @foreach($discount->typeArray as $key => $value)
                                    <option value="{{ $key }}" @if($discount->type == $key) selected @endif >{{ $value }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">Choose whether the discount should be a dollar amount or a precentage.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="filter" class="col-lg-2 control-label">Filter</label>
                        <div class="col-lg-10 input-group">
                            <select class="form-control" name="filter" style="width:100%">
                                @foreach($discount->filterArray as $key => $value)
                                    <option value="{{ $key }}" @if($discount->filter == $key) selected @endif >{{ $value }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">Select if the discount will apply to a certain type of product or all products.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="amount" class="col-lg-2 control-label">Amount</label>
                        <div class="col-lg-10 input-group">
                            <input type="text" class="form-control" id="amount" name="amount" value="{{ $discount->amount }}">
                            <span class="help-block">The amount of the discount.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="active" class="col-lg-2 control-label">Admin Approval?</label>
                        <div class="togglebutton col-lg-10">
                            <label>
                                <input type="checkbox" id="approval" name="approval" @if($discount->approval) checked @endif >
                                <span class="help-block">If activated this discount will require admin approval to use.</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="redeemable" class="col-lg-2 control-label">Redeemable?</label>
                        <div class="togglebutton col-lg-10">
                            <label>
                                <input type="checkbox" id="redeemable" name="redeemable" @if($discount->redeemable) checked @endif >
                                <span class="help-block">Allows users to redeem reward points for this discount</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="value" class="col-lg-2 control-label">Redemption Value</label>
                        <div class="col-lg-10 input-group">
                            <input type="text" class="form-control" value="{{ $discount->value }}" name="value" />
                            <span class="help-block">The amount of reward points this discount will cost.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-4 col-lg-offset-8">
                            <a href="/admin/store/discounts" class="btn btn-default">Cancel</a>
                            <input type="submit" class="btn btn-primary" value="Save">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection