<!-- Cash Modal -->
<div class="modal fade" id="cash" tabindex="-1" role="dialog" aria-labelledby="cash">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="cashLabel">Apply A Cash Payment</h4>
            </div>
            <form method="post" id="cash-form" action="/orders/{{ $order->id }}/payment">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="cash" />
                <div class="modal-body">
                    <div class="form-group">
                        <label for="amount" class="col-lg-4 control-label">Cash Amount Received</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="amount" placeholder="25" name="amount">
                        </div>
                    </div>
                    <button type="button" id="5" class="btn btn-success btn-raised cash">$5</button>
                    <button type="button" id="10" class="btn btn-success btn-raised cash">$10</button>
                    <button type="button" id="20" class="btn btn-success btn-raised cash">$20</button>
                    <button type="button" id="30" class="btn btn-success btn-raised cash">$30</button>
                    <button type="button" id="50" class="btn btn-success btn-raised cash">$50</button>
                    <button type="button" id="100" class="btn btn-success btn-raised cash">$100</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $('.cash').on('click', function(e) {
            e.preventDefault();
            $('#amount').val(this.id);
            $('#cash-form').submit();
        });

    </script>
@endpush