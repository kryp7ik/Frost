@extends('master')
@section('title', 'All Shipments')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    All Shipments
                    <a href="/admin/store/shipments/create" class="btn btn-lg btn-raised btn-success pull-right">Create Shipment</a>
                </h2>
            </div>
            @if ($shipments->isEmpty())
                <p> You currently do not have any Shipments.</p>
            @else
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display clickable" id="table">
                            <thead>
                                <th>Date</th>
                                <th>Store Received</th>
                            </thead>
                            <tbody>
                            @foreach($shipments as $shipment)
                                <tr data-id="{{ $shipment->id }}">
                                    <td>{{ date('m-d-Y h:ia', strtotime($shipment->created_at)) }}</td>
                                    <td>{{ $shipment->store }}</td>
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
            "order" : [[ 1, "desc" ]]
        });
    });
    $('tbody').on('click', 'tr', function() {
        var url = '/admin/store/shipments/' + $(this).attr('data-id') + '/show';
        window.location.href = url;
    });
</script>
@endpush