@extends('master')
@section('title', 'Product Management')
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>Product Management</h2>
            </div>
            <div class="panel-body">
                <div class="row">

                    <div class="col-md-3">
                        <a href="/admin/store/products/index">
                            <div class='cool_btn1 green'>
                                <h1 class='top'>View <i>Products</i></h1>
                                <h2>J</h2>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/admin/store/products/redline">
                            <div class='cool_btn1 orange'>
                                <h1 class='top'>View <i>Redline</i></h1>
                                <h2>6</h2>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/admin/store/shipments">
                            <div class='cool_btn1 teal'>
                                <h1 class='top'>View <i>Shipments</i></h1>
                                <h2>w</h2>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="/admin/store/transfers">
                            <div class='cool_btn1 blue'>
                                <h1 class='top'>View <i>Transfers</i></h1>
                                <h2>p</h2>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-center">
                        <a href="/admin/store/products/create">
                            <div class='cool_btn1 green'>
                                <h1 class='top'>Create <i>Product</i></h1>
                                <h2>J</h2>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 text-center">
                        <a href="/admin/store/shipments/create">
                            <div class='cool_btn1 teal'>
                                <h1 class='top'>Create <i>Shipment</i></h1>
                                <h2>w</h2>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 text-center">
                        <a href="/admin/store/transfers/create">
                            <div class='cool_btn1 blue'>
                                <h1 class='top'>Create <i>Transfer</i></h1>
                                <h2>p</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection