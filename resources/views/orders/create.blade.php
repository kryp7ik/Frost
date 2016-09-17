@extends('master')
@section('title', 'Create A New Order')
@section('content')
    <div class="container col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>Create a new order</h2>
            </div>
            <div class="panel-body">
                <form method="post">
                        {!! csrf_field() !!}
                        @include('orders.partials.product-fieldset')
                        @include('orders.partials.liquid-fieldset')
                        <div class="row">
                            <div class="col-lg-2 col-lg-offset-10">
                                <button type="submit" class="btn btn-lg btn-raised btn-success">
                                    Checkout
                                    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                                    <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript">
            $(document).on('click', '.liquid-add', function(e)
            {
                e.preventDefault();
                var controlForm = $('.liquid-group'),
                        currentEntry = $(this).parents('.liquid-group fieldset:first');
                $("select").select2("destroy");
                var newEntry = $(currentEntry.clone()).appendTo(controlForm);
                var currentCount = ($('.liquid-group fieldset').length -1);
                newEntry.find('.l-size').attr('name', 'liquids[' + currentCount + '][size]');
                newEntry.find('.l-select').attr('name', 'liquids[' + currentCount + '][recipe]');
                newEntry.find('.l-nicotine').attr('name', 'liquids[' + currentCount + '][nicotine]');
                newEntry.find('.l-extra').attr('name', 'liquids[' + currentCount + '][extra]');
                newEntry.find('.l-menthol').attr('name', 'liquids[' + currentCount + '][menthol]');
                newEntry.find('.l-vg').attr('name', 'liquids[' + currentCount + '][vg]');
                controlForm.find('fieldset:not(:last) .liquid-add')
                        .removeClass('liquid-add').addClass('liquid-remove')
                        .removeClass('btn-success').addClass('btn-danger')
                        .html('<span class="glyphicon glyphicon-minus"></span>');
                $("select").select2();
            }).on('click', '.product-add', function(e)
            {
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
            }).on('click', '.product-remove', function(e)
            {
                $(this).parents('fieldset:first').remove();
                e.preventDefault();
                return false;
            }).on('click', '.liquid-remove', function(e)
            {
                $(this).parents('fieldset:first').remove();
                e.preventDefault();
                return false;
            });
        </script>
    @endpush
@endsection