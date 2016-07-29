@extends('master')
@section('title', 'Edit Product')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="well well bs-component">
            <form class="form-horizontal" method="post">
                @foreach ($errors->all() as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                @endforeach
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <fieldset>
                    <legend>Edit Product</legend>
                    <div class="form-group">
                        <label for="name" class="col-lg-2 control-label">Name</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="name" placeholder="Product name" name="name" value="{{ $product->name }}">
                            <span class="help-block">The name of the product.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cost" class="col-lg-2 control-label">Cost</label>
                        <div class="col-lg-10 input-group">
                            <span class="input-group-addon">$</span>
                            <input type="text" class="form-control" placeholder="0.00" id="cost" name="cost" value="{{ $product->cost }}">
                            <span class="help-block">The wholesale price for this product. (Used for reporting only)</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sku" class="col-lg-2 control-label">SKU</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="sku" placeholder="AEF45611" name="sku" value="{{ $product->sku }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category" class="col-lg-2 control-label">Category</label>
                        <div class="col-lg-10">
                            <select class="form-control" name="category">
                                @foreach($product->categoriesArray as $key => $value)
                                    <option value="{{ $key }}" @if($product->category == $key) selected @endif >{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <a href="/admin/store/products/{{ $product->id }}/show" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection