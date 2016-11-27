@extends('master')
@section('title', 'Redline Product Instances')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    Redline Product Instances
                </h2>
            </div>
            @if ($productInstances->isEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="text-center"> All product instances are currently above the redline.</h2>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display clickable" id="table">
                            <thead>
                                <th>Name</th>
                                <th>Store</th>
                                <th>Stock</th>
                                <th>Redline</th>
                            </thead>
                            <tbody>
                            @foreach($productInstances as $instance)
                                <tr data-id="{{ $instance->product->id }}">
                                    <td>{{ $instance->product->name }}</td>
                                    <td>{{ config('store.stores')[$instance->store] }}</td>
                                    <td>{{ $instance->stock }}</td>
                                    <td>{{ $instance->redline }}</td>
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