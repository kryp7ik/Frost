@extends('master')
@section('title', 'Create A New Order')
@section('content')
    <div class="container col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>Create a new order</h2>
            </div>
            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="post">
                    @foreach ($errors->all() as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                        {!! csrf_field() !!}
                        <div class="product-group well">
                            <fieldset class="form-inline" name="products[]">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="product" class="input-group-addon">Product</label>
                                        <select style="width:300px" class="form-control p-select" name="products[0][instance]">
                                            @foreach($productInstances as $instance)
                                                <option value="{{ $instance->id }}">{{ $instance->product->name }}</option>
                                            @endforeach
                                        </select>
                                        <label class="input-group-addon">Quantity</label>
                                        <input type="text" class="form-control p-quantity" name="products[0][quantity]">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success product-add" type="button">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="liquid-group well">
                            <fieldset class="form-inline liquid-fs" name="liquids[]">
                                <div class="form-group liquid-form-group">
                                    <label for="size">Size</label>
                                    <select style="width:70px" class="form-control l-size" name="liquids[0][size]">
                                        <option value="10">10ml</option>
                                        <option value="30">30ml</option>
                                        <option value="50">50ml</option>
                                        <option value="100">100ml</option>
                                        <option value="250">250ml</option>
                                    </select>
                                </div>
                                <div class="form-group liquid-form-group">
                                    <label for="liquid">Flavor</label>
                                    <select style="width:300px" class="form-control l-select" name="liquids[0][recipe]">
                                        @foreach($recipes as $recipe)
                                            <option value="{{ $recipe->id }}">{{ $recipe->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group liquid-form-group">
                                    <label for="nicotine">Nicotine</label>
                                    <select style="width:70px" class="form-control l-nicotine" name="liquids[0][nicotine]">
                                        @for($i = 0; $i <= 30; $i++)
                                            <option value="$i">{{ $i }}mg</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="checkbox liquid-form-group" style="padding-top:16px;">
                                    <div class="togglebutton">
                                        <label>
                                            Extra<input type="checkbox" class="l-extra" id="extra" name="liquids[0][extra]" />
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group liquid-form-group">
                                    <label for="menthol">Menthol</label>
                                    <select style="width:80px" class="form-control l-menthol" name="liquids[0][menthol]">
                                        <option value="0">None</option>
                                        <option value="1">Light</option>
                                        <option value="2">Medium</option>
                                        <option value="3">Heavy</option>
                                        <option value="4">Super</option>
                                    </select>
                                </div>
                                <div class="form-group liquid-form-group">
                                    <label for="vg">VG</label>
                                    <select style="width:70px" class="form-control l-vg" name="liquids[0][vg]">
                                        <option value="40">40%</option>
                                        <option value="0">0%</option>
                                        <option value="20">20%</option>
                                        <option value="30">30%</option>
                                        <option value="50">50%</option>
                                        <option value="60">60%</option>
                                        <option value="70">70%</option>
                                        <option value="80">80%</option>
                                        <option value="100">MAX</option>
                                    </select>
                                </div>
                                <div class="form-group liquid-form-group">
                                    <div class="input-group">
                                        <label for="new">New Recipe</label>
                                        <input style="width:auto" type="text" class="form-control l-new" name="liquids[0][new]" />
                                        <span class="help-block">Creates a new Recipe on the fly.</span>
                                        <span class="input-group-btn">
                                            <button class="btn btn-success liquid-add" type="button">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>

                            </fieldset>
                        </div>
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
            newEntry.find('.l-new').attr('name', 'liquids[' + currentCount + '][new]');
            newEntry.find('.l-new').val('');
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
@endsection