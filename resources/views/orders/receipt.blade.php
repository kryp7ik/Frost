@extends('master')
@section('title', 'Order Receipt')
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h1>Order {{ $order->id }} Completed</h1>
            </div>
            <div class="panel-body">
                @if(session('change') > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="text-center">Change Due: ${{ session('change') }}</h2>
                        </div>
                    </div>
                @endif

                <div class="well">
                    <h3 class="text-center">Receipt Options</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <a href="/pdf/order/{{ $order->id }}/receipt" class="btn btn-raised btn-success btn-block" target="_blank">Print Receipt</a>
                        </div>
                        @if($order->customer)
                            @if($order->customer->email)
                                <div class="col-md-4">
                                    <a href="/mail/order/{{ $order->id }}/receipt" class="btn btn-raised btn-info btn-block">E-mail Receipt</a>
                                </div>
                            @endif
                        @endif
                        <div class="col-md-4">
                            <a href="/orders/create" class="btn btn-warning btn-raised btn-block">No Receipt</a>
                        </div>
                    </div>
                </div>
                    <div class="well">
                        <h3 class="text-center">Customer Information</h3>
                        <div class="row">
                            @if($order->customer)
                                <div class="col-md-6 text-center">
                                    <p><strong>Name: </strong>{{ $order->customer->name }}</p>
                                    <p><strong>Phone: </strong>{{ $order->customer->phone }}</p>
                                    <p><strong>Email: </strong>{{ $order->customer->email }}</p>
                                </div>
                                <div class="col-md-6 text-center">
                                    <p><strong>Points Earned: </strong>{{ $order->calculator()->getPoints() }}</p>
                                    <p><strong>Current Points: </strong>{{ $order->customer->points }}</p>
                                </div>
                            @else
                                <div class="col-md-12 text-center">
                                    <button id="customer-phone" class="btn btn-raised btn-block btn-warning">Add Customer</button>
                                    <form style="display:none" id="change-customer" class="form-inline" method="post" action="/orders/{{ $order->id }}/customer">
                                        {{ csrf_field() }}
                                        <input id="phone" name="phone" type="text" class="form-control" autocomplete="off"/>
                                        <button type="submit" class="btn btn-success btn-raised">Change</button>
                                        <button id="cancel-phone" class="btn btn-danger btn-raised">Cancel</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript">
            $('#customer-phone').on('click', function() {
                $('#customer-phone').hide();
                $('#change-customer').show();
                $('#phone').focus();
            });
            $('#cancel-phone').on('click', function(e) {
                e.preventDefault();
                $('#customer-phone').show();
                $('#change-customer').hide();
            })
        </script>
    @endpush
@endsection