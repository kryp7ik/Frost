@extends('master')
@section('title', 'All Recipes')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    <i class="fa fa-tint" aria-hidden="true"></i>
                    All Recipes
                    <a href="/admin/store/recipes/create" class="btn btn-success btn-lg btn-raised pull-right">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> New Recipe
                    </a>
                    <a href="/admin/store/ingredients" class="btn btn-warning btn-lg btn-raised pull-right">
                        <i class="fa fa-flask" aria-hidden="true"></i> Manage Ingredients
                    </a>
                </h2>
            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <table class="table table-hover display clickable" id="table">
                        <thead>
                        <th>View</th>
                        <th>Name</th>
                        <th>Active</th>
                        <th>Date Created</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#table').DataTable( {
            info: true,
            order: [[ 0, "asc" ]],
            processing: true,
            serverSide: true,
            ajax: '/admin/store/recipes/data-tables',
            columns: [
                { data: 'view', name: 'view', orderable:false, searchable:false},
                { data: 'name', name: 'name' },
                { data: 'active', name: 'active'},
                { data: 'created_at', name: 'created_at'},

            ]
        });
    });
    $('tbody').on('click', 'tr', function() {
        var url = '/admin/store/recipes/' + $(this).attr('id') + '/show';
        window.location.href = url;
    });
</script>
@endpush