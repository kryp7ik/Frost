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
                        <button type="button" class="btn btn-success btn-lg btn-raised pull-right" data-toggle="modal" data-target="#modal">
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
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="Confirm">
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

    @push('scripts')
        <script type="text/javascript">
            // Script to add and remove product fieldsets
            $(document).on('click', '.product-add', function(e) {
                e.preventDefault();
                var controlForm = $('.product-group'),
                        currentEntry = $(this).parents('.product-group fieldset:first');
                $("select").select2("destroy");
                var newEntry = $(currentEntry.clone()).appendTo(controlForm);
                var currentCount = ($('.product-group fieldset').length -1);
                newEntry.find('.p-select').attr('name', 'products[' + currentCount + '][instance]');
                newEntry.find('.p-quantity').attr('name', 'products[' + currentCount + '][quantity]');
                newEntry.find('.p-quantity').val('');
                controlForm.find('fieldset:not(:last) .product-add')
                        .removeClass('product-add').addClass('product-remove')
                        .removeClass('btn-success').addClass('btn-danger')
                        .html('<span class="glyphicon glyphicon-minus"></span>');
                $("select").select2();
            }).on('click', '.product-remove', function(e) {
                $(this).parents('fieldset:first').remove();
                e.preventDefault();
                return false;
            }).on('click', '#confirm', function() {
                $('#shipment-form').submit();
            });

            // Gathers form data and compiles into a table for user confirmation
            $('#modal').on('show.bs.modal', function () {
                var modal = $(this);
                $('fieldset').each(function() {
                    var qty = $(this).find('input').val();
                    if (qty > 0) {
                        var product = $(this).find('select :selected').text();
                        var html = '<tr><td>' + product + '</td><td>' + qty + '</td></tr>';
                        modal.find('#details').append(html);
                    }
                });
            }).on('hide.bs.modal', function () {
                $(this).find('#details').empty();
            });
        </script>
    @endpush
@endsection