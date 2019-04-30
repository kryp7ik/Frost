@extends('master')
@section('title', 'Order Details')
@section('content')
    <div id="app">
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-4">
                <div class="well">
                    @if($order->customer)
                        <h3><span class="fa fa-user"></span> {{ $order->customer->name }}</h3>
                        <div>
                            <button id="customer-phone" class="btn btn-raised btn-block btn-warning animated flipInX">Phone: {{ $order->customer->phone }}</button>
                            <form style="display:none" id="change-customer" class="form-inline" method="post" action="/orders/{{ $order->id }}/customer">
                                {{ csrf_field() }}
                                <input id="phone" name="phone" type="text" class="form-control" autocomplete="off"/>
                                <button type="submit" class="btn btn-success btn-raised btn-sm">Change Customer</button>
                                <button id="cancel-phone" class="btn btn-danger btn-raised btn-sm">Cancel</button>
                            </form>
                        </div>
                        <button class="btn btn-block btn-warning btn-raised animated flipInX" data-toggle="modal" data-target="#redeem">Points: {{ $order->customer->points }}</button>
                        <a href="/customers/{{ $order->customer->id }}/show" class="btn btn-block btn-warning btn-raised animated flipInX">Customer Profile</a>
                    @else
                        <h3><span class="glyphicon glyphicon-user"></span>Customer Information</h3>
                        <form class="form-inline" method="post" action="/orders/{{ $order->id }}/customer">
                            {{ csrf_field() }}
                            <input name="phone" type="text" class="form-control" placeholder="Add Customer By Phone #" autocomplete="off"/>
                            <button type="submit" class="btn btn-success btn-raised">
                                Add <i class="fa fa-plus-square" aria-hidden="true"></i>
                            </button>
                        </form>
                    @endif
                </div>

                <div class="well">
                    <button class="btn btn-block btn-info btn-raised animated flipInX" data-toggle="modal" data-target="#product">
                        <i class="fa fa-tag" aria-hidden="true"></i> Add a product
                    </button>
                    <button class="btn btn-block btn-info btn-raised animated flipInX" data-toggle="modal" data-target="#liquid">
                        <i class="fa fa-tint" aria-hidden="true"></i> Add a liquid
                    </button>
                    <button class="btn btn-block btn-info btn-raised animated flipInX" data-toggle="modal" data-target="#discount">
                        </i> <i class="fa fa-usd" aria-hidden="true"></i>
                        Apply a discount
                    </button>
                </div>
                <div class="well">
                    <a class="btn btn-block btn-danger btn-raised animated flipInX" href="/orders/{{ $order->id }}/delete" >
                        <i class="fa fa-trash" aria-hidden="true"></i> Delete Order
                    </a>
                </div>
            </div><!-- ./ Left Column -->

            <!-- Right Column -->
            <div class="col-md-8">
                @include('orders.partials.order-details')
                <div class="well">
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-success btn-lg btn-raised btn-block animated flipInX" data-toggle="modal" data-target="#cash" style="height:88px">
                                <i class="fa fa-money" aria-hidden="true"></i> Cash
                            </button>
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
                                <button type="submit" class="btn btn-success btn-lg btn-raised btn-block animated flipInX" style="height:88px">
                                    <i class="fa fa-credit-card" aria-hidden="true"></i> Credit
                                </button>
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
            $.fn.extend({
                animateCss: function (animationName) {
                    var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
                    this.addClass('animated ' + animationName).one(animationEnd, function() {
                        $(this).removeClass('animated ' + animationName);
                    });
                }
            });
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
                newEntry.find('.l-salt').attr('name', 'liquids[' + currentCount + '][salt]');
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
                $('#customer-phone').animateCss('hinge');
                $('#change-customer').fadeIn();
                setTimeout(function() {
                    $('#customer-phone').fadeOut();
                }, 2000);
                $('#phone').focus();
            });
            $('#cancel-phone').on('click', function(e) {
                e.preventDefault();
                $('#customer-phone').fadeIn();
                $('#change-customer').fadeOut();
            });
            $('#cash').on('shown.bs.modal', function () {
                $('#amount').focus();
            });
        </script>
    @endpush
@endsection