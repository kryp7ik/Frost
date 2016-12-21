@extends('master')
@section('title', 'Create A New Order')
@section('content')
    <div class="container col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>Create a new order</h2>
            </div>
            <div class="panel-body">
                <form method="post">
                        {!! csrf_field() !!}
                        @include('orders.partials.product-fieldset')
                        @include('orders.partials.liquid-fieldset')
                        <div class="row">
                            <div class="col-lg-2 col-lg-offset-10">
                                <button type="submit" class="btn btn-lg btn-raised btn-success">
                                    Checkout
                                    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                                    <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection