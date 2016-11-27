@extends('master')
@section('title', 'All Orders')
@section('content')
    <div class="col-md-12 ">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>All Orders</h2>
            </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3>Filters</h3></div>
                                <div class="panel-body">
                                    <form method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="start" class="col-md-2 control-label">Date</label>
                                            <div class="input-group col-md-10">
                                                <input type="text" name="start" class="form-control" id="datepicker" value="{{ $date }}"/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                                <button type="submit" class="btn btn-raised btn-success">Apply</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h3>Order Lookup</h3></div>
                                <div class="panel-body">
                                    <div class="form-inline">
                                    <div class="form-group">
                                        <label for="order-id" class="col-md-2 control-label">Order </label>
                                        <div class="input-group col-md-6">
                                            <span class="input-group-addon">#</span>
                                            <input type="text" class="form-control" id="order-id" />
                                        </div>
                                        <a href="#" id="lookup" class="btn btn-raised btn-primary pull-right">Lookup</a>
                                    </div>
                                    </div>
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
                            <table class="table table-hover display" id="table">
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
                                        <td>{{ config('store.stores')[$order->store] }}</td>
                                        <td>
                                            @if($order->customer)
                                                <a href="/customers/{{ $order->customer->id }}/show">{{ $order->customer->phone }}</a>
                                            @else
                                                No Customer
                                            @endif
                                        </td>
                                        <td>${{ $order->total }}</td>
                                        <td>{{ ($order->complete) ? 'Completed' : 'Incomplete' }}</td>
                                        <td>{{ date('m-d-Y h:ia', strtotime($order->created_at)) }}</td>
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
    @push('scripts')
        <script type="text/javascript">
            $('#order-id').keyup(function() {
                    var orderId = this.value;
                $('#lookup').attr('href', '/orders/' + orderId + '/show');
            });
            $(document).ready(function() {
                $('#table').DataTable( {
                    "paging": false,
                    "info" : false,
                    "order" : [[ 0, "desc" ]]
                });
                $('#datepicker').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
            });
        </script>
    @endpush
@endsection