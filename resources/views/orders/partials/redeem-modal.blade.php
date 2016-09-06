<!-- Redeem Points Modal -->
<div class="modal fade" id="redeem" tabindex="-1" role="dialog" aria-labelledby="redeem">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Redeem Points</h4>
            </div>
            <form method="post" action="/orders/{{ $order->id }}/add-discount">
                <input type="hidden" name="redeem" value="true" />
                <div class="modal-body">
                    @foreach ($errors->all() as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                    {{ csrf_field() }}
                        <div class="form-group">
                            <div class="input-group">
                                <label for="discount" class="input-group-addon">Discount</label>
                                <select style="width:300px" class="form-control" name="discount">
                                    @foreach($redeemableDiscounts as $rdiscount)
                                        <option value="{{ $rdiscount->id }}">{{ $rdiscount->value . 'pts - ' . $rdiscount->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Redeem Points</button>
                </div>
            </form>
        </div>
    </div>
</div>