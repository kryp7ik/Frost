@extends('master')
@section('title', 'Create A New Order')
@section('content')
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-4">
            <div class="well">
                <h3><span class="glyphicon glyphicon-user"></span>Customer Information</h3>
                @if($order->customer)
                    <a class="editable"
                       id ="customer"
                       href="#"
                       data-name ="customer"
                       pk="{{ $order->id }}"
                       data-type="text"
                       data-url="/order/{{ $order->id }}/ajax"
                       data-title="customer">{{ $order->customer->phone }}</a>
                    <p>
                        <strong>Points: </strong>{{ $order->customer->points }}
                    </p>
                    <button class="btn btn-block btn-success" data-toggle="modal" data-target="#redeem">Redeem Points</button>
                    <a href="/customer/{{ $order->customer->id }}/show" class="btn btnblock btn-info">Customer Profile</a>
                @else
                    <a class="editable"
                       id ="customer"
                       href="#"
                       data-name ="customer"
                       pk="{{ $order->id }}"
                       data-type="text"
                       data-url="/order/{{ $order->id }}/ajax"
                       data-title="customer">Add a Customer</a>
                @endif
            </div>

            <div class="well">
                <button class="btn btn-block btn-info" data-toggle="modal" data-target="#product">
                    <span class="glyphicon glyphicon-tag"></span>Add a product
                </button>
                <button class="btn btn-block btn-info" data-toggle="modal" data-target="#liquid">
                    <span class="glyphicon glyphicon-tint"></span>Add a liquid
                </button>
            </div>

            <div class="well">
                <button class="btn btn-block btn-info" data-toggle="modal" data-target="#discount">
                    <span class=" glyphicon glyphicon-usd"></span>Apply a discount
                </button>

            </div>
        </div><!-- END Left Column -->

        <!-- Right Column -->
        <div class="col-md-8">
            @include('orders.partials.order-details')
            <div class="well">
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-success btn-raised pull-left">Cash</button>
                        </div>
                        <div class="col-md-4 text-center">
                            <h3>
                                Payment<br/>
                                <small>If split payment enter in cash amount first</small>
                            </h3>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success btn-raised pull-right">Credit</button>
                        </div>
                    </div>
            </div>
        </div>

    </div>

    @include('orders.partials.redeem-modal')
    @include('orders.partials.product-modal')
    @include('orders.partials.liquid-modal')
    @include('shared.editable')
@endsection