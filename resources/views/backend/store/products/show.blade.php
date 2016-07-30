@extends('master')
@section('title', 'Product View')
@section('content')
    <div class="row">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="container col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>
                        Product Info
                        <a href="/admin/store/products/{!! $product->id !!}/edit" class="btn btn-lg btn-raised btn-warning pull-right">Edit Product</a>
                    </h2>
                </div>
                <table class="table table-hover text-center">
                    <tbody>
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $product->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>
                                <a class="editable"
                                   id ="name"
                                   href="#"
                                   data-name ="name"
                                   pk="{{ $product->id }}"
                                   data-type="text"
                                   data-url="/admin/store/products/{{ $product->id }}/editable"
                                   data-title="Product Name">{{ $product->name }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Cost:</strong></td>
                            <td>$
                                <a class="editable"
                                   id ="cost"
                                   href="#"
                                   data-name ="cost"
                                   pk="{{ $product->id }}"
                                   data-type="text"
                                   data-url="/admin/store/products/{{ $product->id }}/editable"
                                   data-title="Cost">{{ $product->cost }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>SKU:</strong></td>
                            <td>
                                <a class="editable"
                                   id ="sku"
                                   href="#"
                                   data-name ="sku"
                                   pk="{{ $product->id }}"
                                   data-type="text"
                                   data-url="/admin/store/products/{{ $product->id }}/editable"
                                   data-title="SKU">{{ $product->sku }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Category:</strong></td>
                            <td>{{ $product->categoriesArray[$product->category] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>
                        Product Instances
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success btn-lg btn-raised pull-right" data-toggle="modal" data-target="#piform">
                            New Product Instance
                        </button>
                    </h2>
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display" id="table">
                            <thead>
                                <th>Store</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Red Line</th>
                                <th>Active</th>
                                <th>Edit</th>
                            </thead>
                            <tbody>
                                @foreach($product->productInstances as $instance)
                                    <tr>
                                        <td>{{ $instance->store }}</td>
                                        <td>${{ $instance->price }}</td>
                                        <td>{{ $instance->stock }}</td>
                                        <td>{{ $instance->redline }}</td>
                                        <td>{{ ($instance->active) ? 'Yes' : 'No' }}</td>
                                        <td><a href="/admin/store/products/instance/{!! $instance->id !!}/edit" class="btn btn-raised btn-warning btn-xs">Edit</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="piform" tabindex="-1" role="dialog" aria-labelledby="productInstanceForm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Create new Product Instance</h4>
                </div>
                <form method="post" action="/admin/store/products/{!! $product->id !!}/instance">
                    <div class="modal-body">
                            @foreach ($errors->all() as $error)
                                <p class="alert alert-danger">{{ $error }}</p>
                            @endforeach
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="form-group">
                                <label for="price" class="col-lg-2 control-label">Price</label>
                                <div class="col-lg-10 input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" placeholder="0.00" id="price" name="price">
                                    <span class="help-block">The retail price of this product at your store.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="stock" class="col-lg-2 control-label">Stock</label>
                                <div class="col-lg-10 input-group">
                                    <span class="input-group-addon">#</span>
                                    <input type="text" class="form-control" placeholder="0" id="stock" name="stock">
                                    <span class="help-block">The current number of stock in your stores inventory.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="redline" class="col-lg-2 control-label">Red Line</label>
                                <div class="col-lg-10 input-group">
                                    <span class="input-group-addon">#</span>
                                    <input type="text" class="form-control" placeholder="1" id="redline" name="redline">
                                    <span class="help-block">You will be alerted when your stock hits this point or lower.</span>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save Instance">
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('shared.editable')
@endsection