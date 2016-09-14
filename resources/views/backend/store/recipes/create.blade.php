@extends('master')
@section('title', 'Create A New Recipe')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="well well bs-component">
            <form class="form-horizontal" method="post">
                @foreach ($errors->all() as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                @endforeach
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <fieldset>
                    <legend>Create a new recipe</legend>
                    <div class="form-group">
                        <label for="name" class="col-lg-2 control-label">Name</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="name" placeholder="Recipe name" name="name">
                            <span class="help-block">The name of the recipe.</span>
                        </div>
                    </div>
                    <div class="cont">
                        <fieldset name="ingredients[]">
                            <div class="form-group">

                                <label for="ingredient" class="col-lg-2 control-label">Ingredient</label>
                                <div class="col-lg-10 input-group">
                                    <select class="form-control" name="ingredients[0][ingredient]">
                                        @foreach($ingredients as $ingredient)
                                            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                        @endforeach
                                    </select>
                                    <label class="input-group-addon">Amount(%)</label>
                                    <input type="text" class="form-control" id="amount" placeholder="10" name="ingredients[0][amount]">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success btn-add" type="button">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-4 col-lg-offset-8">
                            <a href="/admin/store/recipes" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript">
            $(document).on('click', '.btn-add', function(e)
            {
                e.preventDefault();
                var controlForm = $('.cont'),
                        currentEntry = $(this).parents('.cont fieldset:first');
                $("select").select2("destroy");
                var newEntry = $(currentEntry.clone()).appendTo(controlForm);
                var currentCount = ($('.cont fieldset').length -1);
                newEntry.find('select').attr('name', 'ingredients[' + currentCount + '][ingredient]');
                newEntry.find('input').attr('name', 'ingredients[' + currentCount + '][amount]');
                newEntry.find('input').val('');
                controlForm.find('fieldset:not(:last) .btn-add')
                        .removeClass('btn-add').addClass('btn-remove')
                        .removeClass('btn-success').addClass('btn-danger')
                        .html('<span class="glyphicon glyphicon-minus"></span>');
                $("select").select2();
            }).on('click', '.btn-remove', function(e)
            {
                $(this).parents('fieldset:first').remove();
                e.preventDefault();
                return false;
            });
            $('body').on('DOMNodeInserted', 'select', function () {
                $("select").select2();
            });
        </script>
    @endpush
@endsection