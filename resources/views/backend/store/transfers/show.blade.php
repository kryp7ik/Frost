@extends('master')
@section('title', 'View Transfer')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
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
                            <h4>From: {{ config('store.stores')[$transfer->from_store] }}</h4>
                            <h4>To: {{ config('store.stores')[$transfer->to_store] }}</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                @if($transfer->received)
                                    <h4>
                                        Received On: {{ date('m-d-Y h:ia', strtotime($transfer->updated_at)) }}
                                    </h4>
                                @else
                                    @if(Auth::user()->store == $transfer->to_store)
                                        <a href="/admin/store/transfers/{{ $transfer->id }}/receive" class="btn btn-raised btn-success">Confirm Received</a>
                                    @else
                                        <h4>Transfer Pending</h4>
                                    @endif
                                @endif
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
