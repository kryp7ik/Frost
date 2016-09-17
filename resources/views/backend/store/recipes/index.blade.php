@extends('master')
@section('title', 'All Recipes')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    All Recipes
                    <a href="/admin/store/recipes/create" class="btn btn-success btn-lg btn-raised pull-right">New Recipe</a>
                    <a href="/admin/store/ingredients" class="btn btn-warning btn-lg btn-raised pull-right">Manage Ingredients</a>
                </h2>
            </div>
            @if ($recipes->isEmpty())
                <p> You currently do not have any recipes.</p>
            @else
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display clickable" id="table">
                            <thead>
                                <th>Name</th>
                                <th>Active</th>
                                <th>Date Created</th>
                            </thead>
                            <tbody>
                            @foreach($recipes as $recipe)
                                <tr class="{{ ($recipe->active) ? 'success' : 'danger' }}" data-id="{{ $recipe->id }}">
                                    <td>{{ $recipe->name }}</td>
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
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#table').DataTable( {
            "paging": false,
            "info" : false,
            "order" : [[ 0, "asc" ]]
        });
    });
    $('tbody').on('click', 'tr', function() {
        var url = '/admin/store/recipes/' + $(this).attr('data-id') + '/show';
        window.location.href = url;
    });
</script>
@endpush