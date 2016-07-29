@extends('master')
@section('title', 'Recipe View')
@section('content')
    <div class="row">

        <div class="container col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>
                        Recipe Info

                    </h2>
                </div>
                <table class="table table-hover text-center">
                    <tbody>
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>{{ $recipe->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $recipe->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Active:</strong></td>
                        <td>{{ ($recipe->active) ? 'Yes' : 'No' }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>
                        Ingredients
                        <button type="button" class="btn btn-success btn-lg btn-raised pull-right" data-toggle="modal" data-target="#modal">
                            Add Ingredient
                        </button>
                    </h2>
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display" id="table">
                            <thead>
                            <th>Name</th>
                            <th>Vendor</th>
                            <th>Amount (%)</th>
                            <th>Remove</th>
                            </thead>
                            <tbody>
                            @foreach($recipe->ingredients as $ingredient)
                                <tr>
                                    <td>{{ $ingredient->name }}</td>
                                    <td>{{ $ingredient->vendor }}</td>
                                    <td>{{ $ingredient->pivot->amount }}%</td>
                                    <td><a class="btn btn-danger btn-raised" href="/admin/store/recipes/{{ $recipe->id }}/remove/{{ $ingredient->id }}">Remove</a> </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="ingredientForm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Ingredient</h4>
                </div>
                <form method="post" action="/admin/store/recipes/add">
                    <div class="modal-body">
                        @foreach ($errors->all() as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                        @endforeach
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <input type="hidden" name="recipe" value="{{ $recipe->id }}">
                            <div class="form-group">

                                <label for="ingredient" class="col-lg-2 control-label">Ingredient</label>
                                <div class="col-lg-10 input-group">
                                    <select class="form-control" name="ingredient">
                                        @foreach($ingredients as $ingredient)
                                            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                        @endforeach
                                    </select>
                                    <label class="input-group-addon">Amount(%)</label>
                                    <input type="text" class="form-control" id="amount" placeholder="10" name="amount">

                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection