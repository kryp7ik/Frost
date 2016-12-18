@extends('master')
@section('title', 'Customer View')
@section('content')
    <div class="row">
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
                        <td>
                            @if (Auth::user()->hasRole('manager'))
                                <a class="editable"
                                   id = "points"
                                   href="#"
                                   data-name ="points"
                                   pk="{{ $customer->id }}"
                                   data-type="text"
                                   data-url="/customers/{{ $customer->id }}/ajax"
                                   data-title="Points">{{ $customer->points }}</a>
                            @else
                                {{ $customer->points }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Preferred Customer:</strong></td>
                        <td>
                            <button id="preferred"
                                    data-id="{{ $customer->id }}"
                                    data-pref="{{ $customer->preferred }}"
                                    class="btn btn-raised btn-xs {{ ($customer->preferred) ? 'btn-success' : 'btn-danger' }}">
                                {{ ($customer->preferred) ? 'Yes' : 'No' }}
                            </button>
                        </td>
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
                            </thead>
                            <tbody>
                            @foreach($customer->orders as $order)
                                <tr>
                                    <td><a class="btn btn-xs btn-raised btn-info" href="/orders/{{ $order->id }}/show">{{ $order->id }}</a></td>
                                    <td>{{ date('m-d-Y h:ia', strtotime($order->created_at)) }}</td>
                                    <td>${{ $order->total }}</td>
                                    <td>{{ $order->store }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
@include('shared.editable')
<script type="text/javascript">

    $(document).ready(function() {
        $('#table').DataTable( {
            "paging": false,
            "info" : false,
            "order" : [[ 0, "desc" ]]
        });
    });
</script>
@endpush