@extends('master')
@section('title', 'All Customers')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    All Customers
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-success btn-lg btn-raised pull-right" data-toggle="modal" data-target="#modal">
                        New Customer
                    </button>
                </h2>

            </div>
            @if ($customers->isEmpty())
                <p> You currently do not have any customers.</p>
            @else
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display clickable" id="table">
                            <thead>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>E-mail</th>
                            </thead>
                            <tbody>
                            @foreach($customers as $customer)
                                <tr data-id="{{ $customer->id }}">
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->email }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $customers->links() }}
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="customerForm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Create new Customer</h4>
                </div>
                <form method="post" action="/customers/create">
                    <div class="modal-body">

                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="form-group">
                            <label for="name" class="col-lg-2 control-label">Name</label>
                            <div class="col-lg-10 input-group">
                                <input type="text" class="form-control" placeholder="John Doe" id="name" name="name">
                                <span class="help-block">The name of the Customer.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="col-lg-2 control-label">Phone</label>
                            <div class="col-lg-10 input-group">
                                <input type="text" class="form-control" placeholder="6161234567" id="phone" name="phone">
                                <span class="help-block">The customers phone number.</span>
                            </div>
                        </div>
                            <div class="form-group">
                                <label for="email" class="col-lg-2 control-label">E-mail</label>
                                <div class="col-lg-10 input-group">
                                    <input type="text" class="form-control" placeholder="johndoe@gmail.com" id="email" name="email">
                                    <span class="help-block">The customers e-mail.</span>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                $('#table').DataTable( {
                    "paging": false,
                    "info" : false,
                    "order" : [[ 0, "desc" ]]
                });
            });
            $('tbody').on('click', 'tr', function() {
                var url = '/customers/' + $(this).attr('data-id') + '/show';
                window.location.href = url;
            });
        </script>
    @endpush
@endsection