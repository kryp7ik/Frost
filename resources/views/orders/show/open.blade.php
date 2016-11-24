@extends('master')
@section('title', 'Order Details')
@section('content')
    <div id="app">
        <div class="row">
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
    </div>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.28/vue.js"></script>
        <script type="text/javascript">

            var app = new Vue({
                el: '#app',

                data: {
                    liquids: []
                },

                methods: {
                    getLastThreeLiquids: function() {
                        var lastLiquidUrl = '/orders/' + $('#order-id').val() + '/last-liquid';
                        $.getJSON(lastLiquidUrl, function(lastLiquids) {
                            this.liquids = lastLiquids;
                        }.bind(this));
                    }
                },

                ready: function() {
                    this.getLastThreeLiquids();
                }
            });
            $(document).on('click', '.liquid-add', function(e)
            {
                e.preventDefault();
                var controlForm = $('.liquid-group'),
                        currentEntry = $(this).parents('.liquid-group fieldset:first');
                $("select").select2("destroy");
                var newEntry = $(currentEntry.clone()).appendTo(controlForm);
                var currentCount = ($('.liquid-group fieldset').length -1);
                newEntry.find('.l-size').attr('name', 'liquids[' + currentCount + '][size]');
                newEntry.find('.l-select').attr('name', 'liquids[' + currentCount + '][recipe]');
                newEntry.find('.l-nicotine').attr('name', 'liquids[' + currentCount + '][nicotine]');
                newEntry.find('.l-nicotine').val(currentEntry.find('.l-nicotine').val());
                newEntry.find('.l-extra').attr('name', 'liquids[' + currentCount + '][extra]');
                newEntry.find('.l-menthol').attr('name', 'liquids[' + currentCount + '][menthol]');
                newEntry.find('.l-menthol').val(currentEntry.find('.l-menthol').val());
                newEntry.find('.l-vg').attr('name', 'liquids[' + currentCount + '][vg]');
                newEntry.find('.l-vg').val(currentEntry.find('.l-vg').val());
                controlForm.find('fieldset:not(:last) .liquid-add')
                        .removeClass('liquid-add').addClass('liquid-remove')
                        .removeClass('btn-success').addClass('btn-danger')
                        .html('<span class="glyphicon glyphicon-minus"></span>');
                $("select").select2();
            }).on('click', '.product-add', function(e)
            {
                e.preventDefault();
                var controlForm = $('.product-group'),
                        currentEntry = $(this).parents('.product-group fieldset:first');
                $("select").select2("destroy");
                var newEntry = $(currentEntry.clone()).appendTo(controlForm);
                var currentCount = ($('.product-group fieldset').length -1);
                newEntry.find('.p-select').attr('name', 'products[' + currentCount + '][instance]');
                newEntry.find('.p-quantity').attr('name', 'products[' + currentCount + '][quantity]');
                newEntry.find('.p-quantity').val('');
                controlForm.find('fieldset:not(:last) .product-add')
                        .removeClass('product-add').addClass('product-remove')
                        .removeClass('btn-success').addClass('btn-danger')
                        .html('<span class="glyphicon glyphicon-minus"></span>');
                $("select").select2();
            }).on('click', '.product-remove', function(e)
            {
                $(this).parents('fieldset:first').remove();
                e.preventDefault();
                return false;
            }).on('click', '.liquid-remove', function(e)
            {
                $(this).parents('fieldset:first').remove();
                e.preventDefault();
                return false;
            });
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
        </script>
    @endpush
@endsection