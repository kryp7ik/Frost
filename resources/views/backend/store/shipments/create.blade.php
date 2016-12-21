@extends('master')
@section('title', 'Create A New Shipment')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    <i class="fa fa-plus-globe" aria-hidden="true"></i>
                    Create a new Shipment
                </h2>
            </div>
            <div class="panel-body">
                <form method="post" id="shipment-form">
                    {!! csrf_field() !!}
                    @include('orders.partials.product-fieldset')
                </form>
                <div class="row">
                    <div class="col-lg-12">
                        <button type="button" class="btn btn-success btn-lg btn-raised pull-right" data-toggle="modal" data-target="#shipment-modal">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            Create Shipment
                        </button>
                        <a href="/admin/store/shipments" class="btn btn-raised btn-warning pull-right">Cancel</a>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="shipment-modal" tabindex="-1" role="dialog" aria-labelledby="Confirm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Shipment</h4>
                </div>
                <div class="modal-body">
                    <div class="well well-danger">
                        <h3>
                            Please verify the following information is correct.<br/>
                            <small>Note: Once a shipment is completed your inventory will be updated automatically and this action cannot be undone.</small>
                        </h3>
                    </div>
                    <table class="table">
                        <thead>
                            <th>Product</th>
                            <th>Quantity</th>
                        </thead>
                        <tbody id="details">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button id="close" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="confirm" type="button" class="btn btn-success">Create Shipment</button>
                </div>
            </div>
        </div>
    </div>
@endsection