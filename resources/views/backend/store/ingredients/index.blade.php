@extends('master')
@section('title', 'All Ingredients')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    <i class="fa fa-flask" aria-hidden="true"></i>
                    All Ingredients
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-success btn-lg btn-raised pull-right" data-toggle="modal" data-target="#modal">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> New Ingredient
                    </button>
                </h2>

            </div>
            @if ($ingredients->isEmpty())
                <p> You currently do not have any ingredients.</p>
            @else
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display clickable" id="table">
                            <thead>
                                <th>Name</th>
                                <th>Vendor</th>
                            </thead>
                            <tbody>
                            @foreach($ingredients as $ingredient)
                                <tr data-id="{{ $ingredient->id }}">
                                    <td>{{ $ingredient->name }}</td>
                                    <td>{{ $ingredient->vendor }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="ingredientForm">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Create new Ingredient</h4>
                </div>
                <form method="post" action="/admin/store/ingredients/create">
                    <div class="modal-body">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <div class="form-group">
                            <label for="name" class="col-lg-2 control-label">Name</label>
                            <div class="col-lg-10 input-group">
                                <input type="text" class="form-control" placeholder="Flavor Name" id="name" name="name">
                                <span class="help-block">The name of the flavor concentrate.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="vendor" class="col-lg-2 control-label">Vendor</label>
                            <div class="col-lg-10 input-group">
                                <input type="text" class="form-control" placeholder="Company Name" id="vendor" name="vendor">
                                <span class="help-block">The name of the vendor who sells the flavor.</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
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
        var url = '/admin/store/ingredients/' + $(this).attr('data-id') + '/edit';
        window.location.href = url;
    });
</script>
@endpush

