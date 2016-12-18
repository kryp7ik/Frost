@extends('master')
@section('title', 'View Transfer')
@section('content')
    <div class="container col-md-6 col-md-offset-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    View Transfer
                    <a href="/admin/store/transfers" class="btn btn-lg btn-raised btn-success pull-right">
                        <span class="glyphicon glyphicon-arrow-left"> </span>
                        Transfers Index
                    </a>
                </h2>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>
                                {{ config('store.stores')[$transfer->from_store] }}
                                <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                                {{ config('store.stores')[$transfer->to_store] }}
                            </h4>
                            @if($transfer->received)
                                <span class="text-success">
                                    Received On: {{ date('m-d-Y h:ia', strtotime($transfer->updated_at)) }}
                                </span>
                            @else
                                @if(Auth::user()->store == $transfer->to_store)
                                    <a href="/admin/store/transfers/{{ $transfer->id }}/receive" class="btn btn-raised btn-success">Confirm Received</a>
                                @else
                                    <span class="text-danger">Transfer Pending...</span>
                                @endif
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">

                            </div>
                        </div>
                    </div>
                    <h3>


                    </h3>

                    <table class="table table-hover display" id="table">
                        <thead>
                        <th>Product</th>
                        <th>Quantity</th>
                        </thead>
                        <tbody>
                        @foreach($transfer->productInstances as $instance)
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
