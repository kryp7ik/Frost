@extends('master')
@section('title', 'All Orders')
@section('content')
    <div class="col-md-12 ">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>All Orders</h2>
            </div>
            @if (session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
            @endif
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3>Filters</h3></div>
                                <div class="panel-body">
                                    <form method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="start" class="col-md-2 control-label">Date</label>
                                                <div class="input-group col-md-10">
                                                    <input type="text" name="start" class="form-control" id="datepicker" value="{{ $date }}"/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <div class="form-group">
                                                <label for="customer" class="control-label">Orders Without A Customer?</label>
                                                <div class="togglebutton">
                                                    <label>
                                                        <input type="checkbox" id="customer" name="customer">
                                                    </label>
                                                </div>
                                            </div>
                                            <input type="submit" class="btn btn-primary btn-raised pull-right" value="Apply"/>
                                         </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3>Order Lookup</h3></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="order-id" class="col-md-2 control-label">Order </label>
                                        <div class="input-group col-md-10">
                                            <span class="input-group-addon">#</span>
                                            <input type="text" class="form-control" id="order-id" />
                                        </div>
                                    </div>
                                    <a href="#" id="lookup" class="btn btn-raised btn-primary pull-right">Lookup</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($orders->isEmpty())
                        <div class="row">
                            <div class="col-md-8 col-md-offset-1">
                                <h3>You currently do not have any orders.</h3>
                            </div>
                        </div>
                    @else
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <table class="table table-hover display">
                                <thead>
                                    <th>ID</th>
                                    <th>Store</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td><a href="orders/{{ $order->id }}/show" class="btn btn-sm btn-raised btn-info">{{ $order->id }}</a></td>
                                        <td>{{ $order->store }}</td>
                                        <td>
                                            @if($order->customer)
                                                <a href="/customers/{{ $order->customer->id }}/show">{{ $order->customer->phone }}</a>
                                            @else
                                                No Customer
                                            @endif
                                        </td>
                                        <td>${{ $order->total }}</td>
                                        <td>{{ ($order->status) ? 'Completed' : 'Incomplete' }}</td>
                                        <td>{{ $order->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#order-id').keyup(function() {
                var orderId = this.value;
            $('#lookup').attr('href', '/orders/' + orderId + '/show');
        });
        $(function () {

        });
    </script>
@endsection