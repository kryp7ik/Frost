@extends('master')
@section('title', 'All Transfers')
@section('content')
    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2>
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                    All Transfers
                    <a href="/admin/store/transfers/create" class="btn btn-lg btn-raised btn-success pull-right">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Create Transfer
                    </a>
                </h2>
            </div>
            @if ($transfers->isEmpty())
                <p> You currently do not have any Transfers.</p>
            @else
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <table class="table table-hover display clickable" id="table">
                            <thead>
                                <th>Date</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                            @foreach($transfers as $transfer)
                                <tr class="{{ ($transfer->received) ? 'success' : 'danger' }}" data-id="{{ $transfer->id }}">
                                    <td>{{ date('m-d-Y h:ia', strtotime($transfer->created_at)) }}</td>
                                    <td>{{ config('store.stores')[$transfer->from_store] }}</td>
                                    <td>{{ config('store.stores')[$transfer->to_store] }}</td>
                                    <td>{{ ($transfer->received) ? 'Completed' : 'Pending' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <p>{{ $transfers->links() }}</p>
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
            "order" : [[ 0, "desc" ]]
        });
    });
    $('tbody').on('click', 'tr', function() {
        var url = '/admin/store/transfers/' + $(this).attr('data-id') + '/show';
        window.location.href = url;
    });
</script>
@endpush