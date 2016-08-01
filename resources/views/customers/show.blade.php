@extends('master')
@section('title', 'Customer View')
@section('content')
    <div class="row">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="container col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>
                        Customer Info
                    </h2>
                </div>
                <table class="table table-hover text-center">
                    <tbody>
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>{{  $customer->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>
                            <a class="editable"
                               id ="name"
                               href="#"
                               data-name ="name"
                               pk="{{ $customer->id }}"
                               data-type="text"
                               data-url="/customers/{{ $customer->id }}/ajax"
                               data-title="Name">{{ $customer->name }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>
                            <a class="editable"
                               id = "phone"
                               href="#"
                               data-name ="phone"
                               pk="{{ $customer->id }}"
                               data-type="text"
                               data-url="/customers/{{ $customer->id }}/ajax"
                               data-title="Phone">{{ $customer->phone }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>E-mail:</strong></td>
                        <td>
                            <a class="editable"
                               id = "email"
                               href="#"
                               data-name ="email"
                               pk="{{ $customer->id }}"
                               data-type="text"
                               data-url="/customers/{{ $customer->id }}/ajax"
                               data-title="Email">{{ $customer->email }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Points:</strong></td>
                        <td>{{ $customer->points }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>Order History</h2>
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display" id="table">
                            <thead>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Order Total</th>
                            <th>Store</th>
                            <th>View</th>
                            </thead>
                            <tbody>
                            @foreach($customer->orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->updated_at }}</td>
                                    <td>${{ $order->total }}</td>
                                    <td>{{ $order->store }}</td>
                                    <td><a class="btn btn-xs btn-raised btn-info" href="/orders/{{ $order->id }}/show">View</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('shared.editable')
@endsection