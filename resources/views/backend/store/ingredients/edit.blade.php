@extends('master')
@section('title', 'Edit Ingredient')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h2>Edit Ingredient</h2>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="post">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <fieldset>

                        <div class="form-group">
                            <label for="name" class="col-lg-2 control-label">Name</label>
                            <div class="col-lg-10 input-group">
                                <input type="text" class="form-control" placeholder="Flavor Name" id="name" name="name" value="{{ $ingredient->name }}">
                                <span class="help-block">The name of the flavor concentrate.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="vendor" class="col-lg-2 control-label">Vendor</label>
                            <div class="col-lg-10 input-group">
                                <input type="text" class="form-control" placeholder="Company Name" id="vendor" name="vendor" value="{{ $ingredient->vendor }}">
                                <span class="help-block">The name of the vendor who sells the flavor.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-4 col-lg-offset-8">
                                <a href="/admin/store/ingredients" class="btn btn-warning btn-raised">Cancel</a>
                                <button type="submit" class="btn btn-primary btn-raised">Submit</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection