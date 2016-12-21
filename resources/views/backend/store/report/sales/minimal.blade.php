@extends('master')
@section('title', 'Sales Report')
@section('content')
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2>
                <i class="fa fa-area-chart" aria-hidden="true"></i>
                Sales Report
            </h2>
        </div>
        @include('backend.store.report.partials.filters')
    </div>
    <!-- Start Sales Details -->
    <div class="row">
        <div class="col-md-3">
            <div class="well">
                <table class="table">
                    <tbody>
                    <tr>
                        <td colspan="2" class="text-center">
                            <h3><strong>Total Sales</strong></h3>
                        </td>
                    </tr>
                    <tr>
                        <td><h4>Gross Sales</h4></td>
                        <td><h4>${{ $data['gross'] }}</h4></td>
                    </tr>
                    <tr>
                        <td><h4>Sales Tax</h4></td>
                        <td><h4>${{ number_format($data['subtotal'] * 0.06,2) }}</h4></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
