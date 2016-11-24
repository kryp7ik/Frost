<!-- Product Modal -->
<div class="modal fade" id="product" tabindex="-1" role="dialog" aria-labelledby="product">
    <div class="modal-dialog" role="document" style="width:60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Another Product To The Order</h4>
            </div>
            <form method="post" action="/orders/{{ $order->id }}/add-product">
                {{ csrf_field() }}
                <div class="modal-body">
                    @include('orders.partials.product-fieldset')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>