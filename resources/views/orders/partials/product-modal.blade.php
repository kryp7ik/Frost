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
                    <fieldset class="form-inline">
                        <div class="form-group">
                            <div class="input-group">
                                <label for="product" class="input-group-addon">Product</label>
                                <select style="width:500px" class="form-control p-select" name="instance">
                                    @foreach($sortedInstances as $category => $instances)
                                        <optgroup label="{{ $category }}">
                                            @foreach($instances as $instance)
                                                <option value="{{ $instance['instance_id'] }}">{{ $instance['name'] }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <label class="input-group-addon">Quantity</label>
                                <input type="text" class="form-control p-quantity" name="quantity" value="1">
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>