@extends('master')
@section('title', 'Product View')
@section('content')
    <div class="container col-md-6 col-md-offset-3">
        <div class="well well bs-component">
            <form class="form-horizontal" method="post">
                @foreach ($errors->all() as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                @endforeach
                {!! csrf_field() !!}
                <fieldset>
                    <legend>Edit Product Instance</legend>
                    <div class="form-group">
                        <label for="price" class="col-lg-2 control-label">Price</label>
                        <div class="col-lg-10 input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control" id="price" name="price" value="{!! $instance->price !!}">
                            <span class="help-block">The retail price of this product at your store.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="stock" class="col-lg-2 control-label">Stock</label>
                        <div class="col-lg-10 input-group">
                            <span class="input-group-addon">#</span>
                            <input type="text" class="form-control" id="stock" name="stock" value="{!! $instance->stock !!}">
                            <span class="help-block">The current number of stock in your stores inventory.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="redline" class="col-lg-2 control-label">Red Line</label>
                        <div class="col-lg-10 input-group">
                            <span class="input-group-addon">#</span>
                            <input type="text" class="form-control" id="redline" name="redline" value="{!! $instance->redline !!}">
                            <span class="help-block">You will be alerted when your stock hits this point or lower.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="active" class="col-lg-2 control-label">Active?</label>
                        <div class="togglebutton col-lg-10">
                            <label>
                                <input type="checkbox" id="active" name="active" @if ($instance->active) checked @endif >
                                <span class="help-block">If activated this product will show up in the add to order list.</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <a href="/admin/store/products/{{ $instance->product_id }}/show" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>



@endsection