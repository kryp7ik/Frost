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
            @if ($orders->isEmpty())
                <div class="row">
                    <div class="col-md-8 col-md-offset-1">
                        <h3>You currently do not have any orders.</h3>
                    </div>
                </div>
            @else
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3>Date Filter</h3></div>
                                <div class="panel-body">
                                    <form method="post" class="form-horizontal">
                                        <div class="form-group">
                                            <div class="input-group date datepicker">
                                                <label for="start">Start</label>
                                                <input type="text" name="start" class="form-control"/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group date datepicker">
                                                <label for="end">End</label>
                                                <input type="text" name="end" class="form-control"/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                                <span class="help-block">If an end date is not specified today's date will be used.</span>
                                            </div>
                                        </div>
                                        <input type="submit" class="btn btn-primary btn-raised" value="Apply"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3>Other Filters</h3></div>
                                <div class="panel-body">
                                    <a class="btn btn-raised btn-info btn-block" href="#">Incomplete Orders Only</a>
                                    <a class="btn btn-raised btn-info btn-block" href="#">Orders Without A Customer</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3>Order Lookup</h3></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="order-id">Order </label>
                                        <div class="input-group">
                                            <span class="input-group-addon">#</span>
                                            <input type="text" class="form-control" id="order-id" />
                                        </div>
                                    </div>
                                    <a href="#" id="lookup" class="btn btn-raised btn-primary">Lookup</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <table class="table table-hover display">
                                <thead>
                                    <th>ID</th>
                                    <th>Store</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td><a href="orders/{{ $order->id }}/show" class="btn btn-sm btn-raised btn-info">{{ $order->id }}</a></td>
                                        <td>{{ $order->store }}</td>
                                        <td>
                                            @if($order->customer() instanceof \App\Models\Store\Customer)
                                                <a href="/customers/{{ $order->customer()->id }}/show">{{ $order->customer()->phone }}</a>
                                            @else
                                                No Customer
                                            @endif
                                        </td>
                                        <td>${{ $order->total }}</td>
                                        <td>{{ ($order->status) ? 'Completed' : 'Uncompleted' }}</td>
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
        $(document).ready(function() {
            $('#order-id').change(function() {
                var orderId = $('#order-id').val();
                $('#lookup').attr('href', '/orders/' + orderId + '/show');
            });
        });
    </script>
@endsection