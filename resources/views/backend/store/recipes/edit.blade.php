@extends('master')
@section('title', 'Edit Recipe')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="well well bs-component">
            <form class="form-horizontal" method="post">
                {{ csrf_field() }}
                <fieldset>
                    <legend>Edit recipe</legend>
                    <div class="form-group">
                        <label for="name" class="col-lg-2 control-label">Name</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="name" name="name" value="{{ $recipe->name }}">
                            <span class="help-block">The name of the recipe.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-lg-2 control-label">Description</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="description" name="description" value="{{ $recipe->description }}">
                            <span class="help-block">A description of the flavor..</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category" class="col-lg-2 control-label">Category</label>
                        <div class="col-lg-10">
                            <select class="form-control" id="category" name="category">
                                @foreach(config('store.recipe_categories') as $category)
                                    <option value="{{ $category }}" @if($recipe->category == $category)selected @endif>{{ $category }}</option>
                                @endforeach
                            </select>
                            <span class="help-block">A category for the flavor.</span>
                        </div>
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
@endsection