@extends('master')
@section('title', 'Inventory Count')
@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h2>
                        <i class="fa fa-database" aria-hidden="true"></i>
                        Inventory Count
                        <a href="/pdf/inventory" class="btn btn-raised btn-success pull-right" target="_blank">
                            <span class="glyphicon glyphicon-print"> </span>
                            Print Inventory Count Sheet
                        </a>
                    </h2>
                </div>
                <div class="panel-body">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-1">
                                    <span class="xl-font glyphicon glyphicon-alert"> </span>
                                </div>
                                <div class="col-xs-11">
                                    <h4>
                                        Note: Please verify that none of the products you are entering in have been sold since you made the initial count.
                                        Once you complete this form the inventory for your store will be automatically
                                        updated and the managers will be notified of any discrepancies.
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form method="post">
                        {{ csrf_field() }}
                        <div class="product-group">
                            <fieldset class="form-inline" name="products[]">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="product" class="input-group-addon">Product</label>
                                        <select style="width:500px" class="form-control p-select" name="products[0][instance]">
                                            <option value="0">Select a Product</option>
                                            @foreach($sortedInstances as $category => $instances)
                                                <optgroup label="{{ $category }}">
                                                    @foreach($instances as $instance)
                                                        <option
                                                                data-stock="{{ $instance['stock'] }}"
                                                                value="{{ $instance['instance_id'] }}">
                                                            {{ $instance['name'] }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        <label class="input-group-addon">Expected: <span class="expected"></span></label>
                                        <label class="input-group-addon">Actual Count</label>
                                        <input type="text" class="form-control p-quantity" name="products[0][quantity]" placeholder="0" autocomplete="off">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success product-add" type="button">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="row">
                            <div class="col-md-2 col-md-offset-8">
                                <button type="submit" class="btn btn-lg btn-raised btn-success">
                                    Process Inventory Count
                                    <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).on('select2:select', 'select', function(e) {
        $(this).parent().find('.expected').html(e.params.data.element.attributes[0].value);
    });
</script>
@endpush
