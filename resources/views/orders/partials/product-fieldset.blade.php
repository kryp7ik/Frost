<div class="product-group">
    <fieldset class="form-inline" name="products[]">
        <div class="form-group">
            <div class="input-group">
                <label for="product" class="input-group-addon">Product</label>
                <select style="width:500px" class="form-control p-select" name="products[0][instance]">
                    @foreach($sortedInstances as $category => $instances)
                        <optgroup label="{{ $category }}">
                            @foreach($instances as $instance)
                                <option value="{{ $instance['instance_id'] }}">{{ $instance['name'] }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <label class="input-group-addon">Expected: <span class="expected"></span></label>
                <label class="input-group-addon">Quantity</label>
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