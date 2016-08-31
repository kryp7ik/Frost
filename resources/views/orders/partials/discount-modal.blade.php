<!-- Discount Modal -->
<div class="modal fade" id="discount" tabindex="-1" role="dialog" aria-labelledby="discount">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add A Discount To The Order</h4>
            </div>
            <form method="post" action="/orders/{{ $order->id }}/add-discount">
                {{ csrf_field() }}
                <div class="modal-body">
                    <fieldset class="form-inline">
                        <div class="form-group">
                            <div class="input-group">
                                <label for="discount" class="input-group-addon">Discount</label>
                                <select style="width:300px" class="form-control" name="discount" id="discount-select">
                                    @foreach($sortedDiscounts as $category => $discounts)
                                        <optgroup label="{{ $category }}">
                                            @foreach($discounts as $discount)
                                                <option value="{{ $discount['discount'] }}">{{ $discount['name'] }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="approval-group" style="display:none">
                            <div class="input-group">
                                <label for="approval" class="input-group-addon">Manager Pin</label>
                                <input class="form-control" id="pin" name="pin" type="password" />
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Apply Discount</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#discount-select').on('select2:select', function(e) {
       if(e.params.data.element.parentElement.label == "Approval Required"){
           $('#approval-group').show();
           $('#pin').focus();
       } else {
           $('#approval-group').hide();
       }
    });
</script>