@extends('master')
@section('title', 'Recipe View')
@section('content')
    <div class="row">
        <div class="container col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>Recipe Info</h2>
                </div>
                <table class="table table-hover text-center">
                    <tbody>
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $recipe->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>
                                <a class="editable"
                                   id = "name"
                                   href="#"
                                   data-name ="name"
                                   pk="{{ $recipe->id }}"
                                   data-type="text"
                                   data-url="/admin/store/recipes/{{ $recipe->id }}/ajax"
                                   data-title="Recipe Name">{{ $recipe->name }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Active:</strong></td>
                            <td>
                                <form method="post" action="/admin/store/recipes/{{ $recipe->id }}/update">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="active" value="{{ ($recipe->active) ? '0' : '1' }}">
                                    <input
                                            type="submit"
                                            class="btn {{ ($recipe->active) ? 'btn-success' : 'btn-danger' }}"
                                            value="{{ ($recipe->active) ? 'De-activate' : 'Activate' }}">
                                </form>
                            </td>
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
                        {{ csrf_field() }}
                            <input type="hidden" name="recipe" value="{{ $recipe->id }}">
                            <div class="form-inline">
                                <div class="form-group">
                                    <label for="ingredient" class="control-label">Ingredient</label>
                                    <select style="width:250px" class="form-control" name="ingredient">
                                        @foreach($ingredients as $ingredient)
                                            <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" style="margin-left:30px">
                                    <label class="control-label">Amount(%)</label>
                                    <input type="text" class="form-control" id="amount" placeholder="10" name="amount" style="width:100px">
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Ingredient</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
@include('shared.editable')
@endpush