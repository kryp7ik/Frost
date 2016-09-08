@extends('master')
@section('title', 'Order Details')
@section('content')
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-4">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h2>Order Information</h2>
                </div>
                <div class="panel-body">
                    <table class="table table-hover text-center">
                        <tbody>
                            <tr>
                                <td><strong>Order #</strong></td>
                                <td>{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Date</strong></td>
                                <td>{{ date('m-d-Y h:ia', strtotime($order->created_at)) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Store</strong></td>
                                <td>{{ $order->store }}</td>
                            </tr>
                            <tr>
                                <td><strong>User</strong></td>
                                <td>{{ $order->user->email }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="well well-sm">
                        <h3 class="text-center">Customer</h3>
                        @if($order->customer)
                            <a class="btn btn-info btn-raised btn-block" href="/customers/{{ $order->customer->id }}/show">{{ $order->customer->phone }}</a>
                        @else
                            <button id="customer-phone" class="btn btn-raised btn-block btn-warning">Add Customer</button>
                            <form style="display:none" id="change-customer" class="form-inline" method="post" action="/orders/{{ $order->id }}/customer">
                                {{ csrf_field() }}
                                <input id="phone" name="phone" type="text" class="form-control" autocomplete="off"/>
                                <button type="submit" class="btn btn-success btn-raised">Change</button>
                                <button id="cancel-phone" class="btn btn-danger btn-raised">Cancel</button>
                            </form>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        <!-- End Left Column -->

        <!-- Right Column -->
        <div class="col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>Order Details</h2>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <tr class="active">
                            <td colspan="4"><strong>Name</strong></td>
                            <td><strong>Quantity</strong></td>
                            <td><strong>Price</strong></td>
                        </tr>
                        @foreach($order->productInstances as $instance)
                            <tr>
                                <td colspan="4">{{ $instance->product->name }}</td>
                                <td>{{ $instance->pivot->quantity }}</td>
                                <td>${{ $instance->price * $instance->pivot->quantity }}</td>
                            </tr>
                        @endforeach
                        <tr class="active">
                            <td><strong>Flavor</strong></td>
                            <td><strong>Size</strong></td>
                            <td><strong>Nicotine</strong></td>
                            <td><strong>VG%</strong></td>
                            <td><strong>Menthol</strong></td>
                            <td><strong>Price</strong></td>
                        </tr>
                        @foreach($order->liquidProducts as $liquid)
                            <tr>
                                <td>{{ $liquid->recipe->name }} {{ ($liquid->extra) ? 'XTRA' : '' }}</td>
                                <td>{{ $liquid->size }}ml</td>
                                <td>{{ $liquid->nicotine }}mg</td>
                                <td>{{ config('store.vg_levels')[$liquid->vg] }}</td>
                                <td>{{ config('store.menthol_levels')[$liquid->menthol] }}</td>
                                <td>${{ $liquid->getPrice() }}</td>
                            </tr>
                        @endforeach
                        @if(count($order->discounts) > 0)
                            <tr class="active">
                                <td colspan="5"><strong>Name</strong></td>
                                <td><strong>Amount</strong></td>
                            </tr>
                            @foreach($order->discounts as $discount)
                                <tr class="danger">
                                    <td>{{ $discount->name }}</td>
                                    <td colspan="4"></td>
                                    <td><strong>-${{ $discount->pivot->applied }}</strong></td>
                                </tr>
                            @endforeach
                        @endif


                        <tr class="warning">
                            <td colspan="5"><strong class="pull-right">Subtotal</strong></td>
                            <td>${{ $order->subtotal }}</td>
                        </tr>
                        <tr class="info">
                            <td colspan="5"><strong class="pull-right">Tax</strong></td>
                            <td>${{ number_format(($order->subtotal * .06),2) }}</td>
                        </tr>
                        <tr class="success">
                            <td colspan="5"><strong class="pull-right">Total</strong></td>
                            <td>${{ $order->total }}</td>
                        </tr>
                        @if(count($order->payments) > 0)
                            <tr class="active">
                                <td><strong>Payments</strong></td>
                                <td colspan="4"><strong>Type</strong></td>
                                <td><strong>Amount</strong></td>
                            </tr>
                            @foreach($order->payments as $payment)
                                <tr class="success">
                                    <td></td>
                                    <td colspan="4">{{ ucfirst($payment->type) }}</td>
                                    <td>${{ number_format($payment->amount,2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <!-- End Right Column -->
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