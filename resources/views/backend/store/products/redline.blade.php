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
                <p> All product instances are currently above the redline.</p>
            @else
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display" id="table">
                            <thead>
                                <th>Name</th>
                                <th>Stock</th>
                                <th>Redline</th>
                                <th>Store</th>
                            </thead>
                            <tbody>
                            @foreach($productInstances as $instance)
                                <tr>
                                    <td>
                                        <a href="{!! action('Admin\Store\ProductController@show', $instance->product->id) !!}">{!! $instance->product->name !!} </a>
                                    </td>
                                    <td>{{ $instance->stock }}</td>
                                    <td>{{ $instance->redline }}</td>
                                    <td>{{ $instance->store }}</td>
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
</script>
@endpush