@extends('master')
@section('title', 'All Customers')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    <i class="fa fa-users" aria-hidden="true"></i>
                    All Customers
                    <button type="button" class="btn btn-success btn-lg btn-raised pull-right" data-toggle="modal" data-target="#modal">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> New Customer
                    </button>
                </h2>
            </div>

                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display clickable" id="table">
                            <thead>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>E-mail</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

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
                        info: true,
                        order: [[ 0, "asc" ]],
                        processing: true,
                        serverSide: true,
                        ajax: '/customers/data-tables',
                        columns: [
                            { data: 'name', name: 'name' },
                            { data: 'phone', name: 'phone'},
                            { data: 'email', name: 'email'}
                        ]
                    });
                });
            $('tbody').on('click', 'tr', function() {
                var url = '/customers/' + $(this).attr('id') + '/show';
                window.location.href = url;
            });
        </script>
    @endpush
@endsection