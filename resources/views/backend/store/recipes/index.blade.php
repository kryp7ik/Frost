@extends('master')
@section('title', 'All Recipes')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    All Recipes
                    <!-- Button trigger modal -->
                    <a href="/admin/store/recipes/create" class="btn btn-success btn-lg btn-raised pull-right">New Recipe</a>
                </h2>
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if ($recipes->isEmpty())
                <p> You currently do not have any recipes.</p>
            @else
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display" id="table">
                            <thead>
                                <th>Name</th>
                                <th>Active</th>
                                <th>Date Created</th>
                            </thead>
                            <tbody>
                            @foreach($recipes as $recipe)
                                <tr>
                                    <td><a href="/admin/store/recipes/{{ $recipe->id }}/show">{{ $recipe->name }}</a></td>
                                    <td>{{ ($recipe->active) ? 'YES' : 'NO' }}</td>
                                    <td>{{ $recipe->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection