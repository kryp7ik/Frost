@extends('master')
@section('title', 'Order Details')
@section('content')
    <div class="row" id="app">
        <!-- Left Column -->
        <div class="col-md-4">
            <div class="well">
                @if($order->customer)
                    <h3><span class="glyphicon glyphicon-user"></span> {{ $order->customer->name }}</h3>
                    <div>
                        <button id="customer-phone" class="btn btn-raised btn-block btn-warning">Phone: {{ $order->customer->phone }}</button>
                        <form style="display:none" id="change-customer" class="form-inline" method="post" action="/orders/{{ $order->id }}/customer">
                            {{ csrf_field() }}
                            <input id="phone" name="phone" type="text" class="form-control" autocomplete="off"/>
                            <button type="submit" class="btn btn-success btn-raised btn-sm">Change Customer</button>
                            <button id="cancel-phone" class="btn btn-danger btn-raised btn-sm">Cancel</button>
                        </form>
                    </div>
                    <button class="btn btn-block btn-warning btn-raised" data-toggle="modal" data-target="#redeem">Points: {{ $order->customer->points }}</button>
                    <a href="/customers/{{ $order->customer->id }}/show" class="btn btn-block btn-warning btn-raised">Customer Profile</a>
                @else
                    <h3><span class="glyphicon glyphicon-user"></span>Customer Information</h3>
                    <form class="form-inline" method="post" action="/orders/{{ $order->id }}/customer">
                        {{ csrf_field() }}
                        <input name="phone" type="text" class="form-control" placeholder="Add Customer By Phone #" autocomplete="off"/>
                        <button type="submit" class="btn btn-success btn-raised">Add</button>
                    </form>
                @endif
            </div>

            <div class="well">
                <button class="btn btn-block btn-info btn-raised" data-toggle="modal" data-target="#product">
                    <span class="glyphicon glyphicon-tag"></span> Add a product
                </button>
                <button class="btn btn-block btn-info btn-raised" data-toggle="modal" data-target="#liquid">
                    <span class="glyphicon glyphicon-tint"></span> Add a liquid
                </button>
                <button class="btn btn-block btn-info btn-raised" data-toggle="modal" data-target="#discount">
                    <span class=" glyphicon glyphicon-usd"></span> Apply a discount
                </button>
            </div>
            <div class="well">
                <a class="btn btn-block btn-danger btn-raised" href="/orders/{{ $order->id }}/delete" >
                    <span class="glyphicon glyphicon-cutlery"></span>
                    <span class="glyphicon glyphicon-trash"></span>
                    <span class="glyphicon glyphicon-remove"></span>
                    <span class="glyphicon glyphicon-warning-sign"></span>
                    <span class="glyphicon glyphicon-flash"></span>
                    <span class="glyphicon glyphicon-fire"></span>
                    Delete Order
                    <span class="glyphicon glyphicon-fire"></span>
                    <span class="glyphicon glyphicon-flash"></span>
                    <span class="glyphicon glyphicon-warning-sign"></span>
                    <span class="glyphicon glyphicon-remove"></span>
                    <span class="glyphicon glyphicon-trash"></span>
                    <span class="glyphicon glyphicon-cutlery"></span>
                </a>
            </div>
        </div><!-- ./ Left Column -->

        <!-- Right Column -->
        <div class="col-md-8">
            @include('orders.partials.order-details')
            <div class="well">
                <div class="row">
                    <div class="col-md-4">
                        <button class="btn btn-success btn-lg btn-raised btn-block" data-toggle="modal" data-target="#cash">Cash</button>
                    </div>
                    <div class="col-md-4 text-center">
                        <h3>
                            Payment<br/>
                            <small>If split payment enter in cash amount first</small>
                        </h3>
                    </div>
                    <div class="col-md-4">
                        <form method="post" action="/orders/{{ $order->id }}/payment">
                            {{ csrf_field() }}
                            <input type="hidden" name="type" value="credit" />
                            <button type="submit" class="btn btn-success btn-lg btn-raised btn-block">Credit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <input type="hidden" id="order-id" value="{{ $order->id }}" />

    @include('orders.partials.redeem-modal')
    @include('orders.partials.product-modal')
    @include('orders.partials.liquid-modal')
    @include('orders.partials.discount-modal')
    @include('orders.partials.cash-modal')
    @include('orders.partials.vue-liquid-fieldset')
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.28/vue.js"></script>
        <script type="text/javascript">

            Vue.component('vue-liquid-fieldset', {
                template: '#liquid-fieldset-template',
                props: ['fsindex']
            });
            var app = new Vue({
                el: '#app',

                data: {
                    liquids: [],
                    fieldsets: [],
                    fieldsetCount: 0,
                },

                methods: {

                    addFieldset: function () {
                        this.fieldsets.push({ });
                    },

                    getLastThreeLiquids: function() {
                        var lastLiquidUrl = '/orders/' + $('#order-id').val() + '/last-liquid';
                        $.getJSON(lastLiquidUrl, function(lastLiquids) {
                            liquids = lastLiquids;
                        }.bind(this));

                    }
                },

                ready: function() {
                    this.getLastThreeLiquids();
                }
            });
            var lastLiquid = [];
            $('#customer-phone').on('click', function() {
                $('#customer-phone').hide();
                $('#change-customer').show();
                $('#phone').focus();
            });
            $('#cancel-phone').on('click', function(e) {
                e.preventDefault();
                $('#customer-phone').show();
                $('#change-customer').hide();
            });
            $('#cash').on('shown.bs.modal', function () {
                $('#amount').focus();
            });
/*            $('#liquid').on('show.bs.modal', function () {
                var lastLiquidUrl = '/orders/' + $('#order-id').val() + '/last-liquid';
                $.getJSON(lastLiquidUrl, function(liquid) {
                    if (liquid != 'fail') {
                        lastLiquid = liquid;
                        $('#last-liquid').show();
                        if (liquid.extra == 1) {
                            $('#lflavor').html(liquid.recipe + ' Extra');
                        } else {
                            $('#lflavor').html(liquid.recipe);
                        }
                        $('#lsize').html(liquid.size + 'ml');
                        $('#lnicotine').html(liquid.nicotine + 'mg');
                        $('#lmenthol').html(liquid.menthol);
                        $('#lvg').html(liquid.vg);
                    }
                }); // .bind(this)
            });*/
        </script>
    @endpush
@endsection