<div class="row">
    <div class="col-md-2">
        <a id="product-button" href="#">
            <div class="panel panel-orange">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <span class="xl-font glyphicon glyphicon-tags"></span>
                        </div>
                        <div class="col-xs-9 text-right">
                            <h2>Products</h2>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-2">
        <a href="/admin/store/recipes">
            <div class="panel panel-light-blue">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <span class="xl-font glyphicon glyphicon-tint"></span>
                        </div>
                        <div class="col-xs-9 text-right">
                            <h2>Liquids</h2>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-2">
        <a href="/admin/store/discounts">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <span class="xl-font glyphicon glyphicon-usd"></span>
                        </div>
                        <div class="col-xs-9 text-right">
                            <h2>Discounts</h2>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-2">
        <a href="/admin/store/report/sales">
            <div class="panel panel-light-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <span class="xl-font glyphicon glyphicon-shopping-cart"></span>
                        </div>
                        <div class="col-xs-9 text-right">
                            <h2>Reports</h2>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-2">
        <a href="/touch">
            <div class="panel panel-dark-blue">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <span class="xl-font glyphicon glyphicon-list"></span>
                        </div>
                        <div class="col-xs-9 text-right">
                            <h2>Touch</h2>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-2">
        <a href="/admin/users">
            <div class="panel panel-teal">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <span class="xl-font glyphicon glyphicon-user"></span>
                        </div>
                        <div class="col-xs-9 text-right">
                            <h2>Users</h2>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
@include('shared.product-links')
@push('scripts')
<script>
    $('#product-button').on('click', function() {
        $('#product-links').fadeToggle();
    })
</script>
@endpush