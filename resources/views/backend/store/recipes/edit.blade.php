@extends('master')
@section('title', 'Edit Recipe')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="well well bs-component">
            <form class="form-horizontal" method="post">
                @foreach ($errors->all() as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                @endforeach
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <fieldset>
                    <legend>Edit recipe</legend>
                    <div class="form-group">
                        <label for="name" class="col-lg-2 control-label">Name</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="name" placeholder="Recipe name" name="name" value="{{ $recipe->name }}">
                            <span class="help-block">The name of the recipe.</span>
                        </div>
                    </div>
                    <div class="cont">
                        @foreach($recipe->ingredients as $ring)
                            <fieldset name="ingredients[]">
                                <div class="form-group">
                                    <label for="ingredient" class="col-lg-2 control-label">Ingredient</label>
                                    <div class="col-lg-10 input-group">
                                        <select class="form-control" name="ingredients[0][ingredient]">
                                            @foreach($ingredients as $ingredient)
                                                <option value="{{ $ingredient->id }}"
                                                @if($ring->id == $ingredient->id) selected @endif>{{ $ingredient->name }}</option>
                                            @endforeach
                                        </select>
                                        <label class="input-group-addon">Amount(%)</label>
                                        <input type="text" class="form-control" id="amount" placeholder="10" name="ingredients[0][amount]" value="{{ $ring->pivot->amount }}">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success btn-add" type="button">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </fieldset>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <a href="/admin/store/recipes" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        $(document).on('click', '.btn-add', function(e)
        {
            e.preventDefault();

            var controlForm = $('.cont'),
                    currentEntry = $(this).parents('.cont fieldset:first'),
                    newEntry = $(currentEntry.clone()).appendTo(controlForm),
                    currentCount = ($('.cont fieldset').length -1);
            newEntry.find('select').attr('name', 'ingredients[' + currentCount + '][ingredient]');
            newEntry.find('input').attr('name', 'ingredients[' + currentCount + '][amount]');
            newEntry.find('input').val('');
            controlForm.find('fieldset:not(:last) .btn-add')
                    .removeClass('btn-add').addClass('btn-remove')
                    .removeClass('btn-success').addClass('btn-danger')
                    .html('<span class="glyphicon glyphicon-minus"></span>');
            $(select).chosen();
        }).on('click', '.btn-remove', function(e)
        {
            $(this).parents('fieldset:first').remove();

            e.preventDefault();
            return false;
        });


    </script>
@endsection