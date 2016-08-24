<!-- Liquid Modal -->
<style>.liquid-add{ display: none }</style>
<div class="modal fade" id="liquid" tabindex="-1" role="dialog" aria-labelledby="liquid">
    <div class="modal-dialog" role="document" style="width:60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Another Liquid To The Order</h4>
            </div>
            <form method="post" action="/orders/{{ $order->id }}/">
                <div class="modal-body">
                    @include('orders.partials.liquid-fieldset')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
            </form>
        </div>
    </div>
</div>