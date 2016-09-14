@extends('master')
@section('title', 'View Shipment')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    View Shipment
                    <a href="/admin/store/shipments" class="btn btn-lg btn-raised btn-success pull-right">
                        <span class="glyphicon glyphicon-arrow-left"> </span>
                        Shipments Index
                    </a>
                </h2>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h3>
                        Store: {{ config('store.stores')[$shipment->store] }}
                        <span class="pull-right">
                            Received On: {{ date('m-d-Y h:ia', strtotime($shipment->created_at)) }}
                        </span>
                    </h3>

                    <table class="table table-hover display" id="table">
                        <thead>
                            <th>Product</th>
                            <th>Quantity</th>
                        </thead>
                        <tbody>
                        @foreach($shipment->productInstances as $instance)
                            <tr>
                                <td><a href="/admin/store/products/{{ $instance->product->id }}/show">{{ $instance->product->name }}</a></td>
                                <td>{{ $instance->pivot->quantity }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
