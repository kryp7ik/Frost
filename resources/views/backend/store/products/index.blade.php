@extends('master')
@section('title', 'All Products')
@section('content')
    <style>
        tr { cursor: pointer; cursor: hand; }
    </style>
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    All Products
                    <a href="/admin/store/products/create" class="btn btn-lg btn-raised btn-success pull-right">Add New Product</a>
                </h2>
            </div>
            @if ($products->isEmpty())
                <p> You currently do not have any products.</p>
            @else
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display" id="table">
                            <thead>
                                <th>Name</th>
                                <th>Cost</th>
                                <th>Category</th>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr data-id="{{ $product->id }}">
                                    <td>{{ $product->name }}</td>
                                    <td>${!! number_format($product->cost, 2) !!}</td>
                                    <td>{{ config('store.product_categories')[$product->category] }}</td>
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
        var url = '/admin/store/products/' + $(this).attr('data-id') + '/show';
        window.location.href = url;
    });
</script>
@endpush