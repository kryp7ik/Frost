@extends('master')
@section('title', 'Edit Ingredient')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h2>Edit Ingredient</h2>
            </div>
            @foreach ($errors->all() as $error)
                <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
            <div class="panel-body">
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
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection