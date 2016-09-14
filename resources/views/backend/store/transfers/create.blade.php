@extends('master')
@section('title', 'Create A New Transfer')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>Create a new Transfer</h2>
            </div>
            <div class="panel-body">
                <form method="post" id="transfer-form">
                    @foreach ($errors->all() as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                    <div class="row">
                        <div class="col-md-4 form-inline">
                            <label for="from" class="control-label">From Store</label>
                            <select style="width:200px" class="form-control" name="from" id="from-select" disabled >
                                <option value="{{ Auth::user()->store }}">{{ config('store.stores')[Auth::user()->store] }}</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-inline">
                            <label for="to" class="control-label">To Store</label>
                            <select style="width:200px" class="form-control" name="to" id="to-select">
                                @foreach(config('store.stores') as $key => $value)
                                    @if($key != Auth::user()->store)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {!! csrf_field() !!}
                        <div class="product-group">
                            <fieldset class="form-inline" name="products[]">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="product" class="input-group-addon">Product</label>
                                        <select style="width:500px" class="form-control p-select" name="products[0][instance]">
                                            @foreach($sortedInstances as $category => $instances)
                                                <optgroup label="{{ $category }}">
                                                    @foreach($instances as $instance)
                                                        <option value="{{ $instance['instance_id'] }}">{{ $instance['name'] . ' - Stock:' . $instance['stock'] }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        <label class="input-group-addon">Quantity</label>
                                        <input type="text" class="form-control p-quantity" name="products[0][quantity]" placeholder="0" autocomplete="off">
                                        <span class="input-group-btn">
                                        <button class="btn btn-success product-add" type="button">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                </form>
                <div class="row">
                    <div class="col-lg-12">
                        <button type="button" class="btn btn-success btn-lg btn-raised pull-right" data-toggle="modal" data-target="#modal">
                            Create Transfer
                        </button>
                        <a href="/admin/store/products" class="btn btn-raised btn-warning pull-right">Cancel</a>
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
                    <h4 class="modal-title" id="myModalLabel">Confirm Transfer</h4>
                </div>
                <div class="modal-body">
                    <div class="well well-danger">
                        <h3>
                            Please verify the following information is correct.<br/>
                        </h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="text-center">From: <span id="from"></span></h3>
                        </div>
                        <div class="col-md-6">
                            <h3 class="text-center">To: <span id="to"></span></h3>
                        </div>
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
                    <button id="confirm" type="button" class="btn btn-success">Create Transfer</button>
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
            $('#transfer-form').submit();
        });

        // Gathers form data and compiles into a table for user confirmation
        $('#modal').on('show.bs.modal', function () {
            var modal = $(this);
            $('#from').append($('#from-select :selected').text());
            $('#to').append($('#to-select :selected').text());
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
            $(this).find('#from').empty();
            $(this).find('#to').empty();
        });
    </script>
    @endpush
@endsection