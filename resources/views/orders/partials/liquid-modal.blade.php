<!-- Liquid Modal -->
<div class="modal fade" id="liquid" tabindex="-1" role="dialog" aria-labelledby="liquid">
    <div class="modal-dialog" role="document" style="width:60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Another Liquid To The Order</h4>
            </div>
            <form method="post" action="/orders/{{ $order->id }}/add-liquid">
                {{ csrf_field() }}
                <div class="modal-body">
                    <fieldset class="form-inline">
                        <div class="form-group liquid-form-group">
                            <label for="size">Size</label>
                            <select style="width:70px" class="form-control l-size" name="size">
                                @foreach(config('store.bottle_sizes') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group liquid-form-group">
                            <label for="liquid">Flavor</label>
                            <select style="width:300px" class="form-control l-select" name="recipe">
                                <option value="0">None</option>
                                @foreach($recipes as $recipe)
                                    <option value="{{ $recipe->id }}">{{ $recipe->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group liquid-form-group">
                            <label for="nicotine">Nicotine</label>
                            <select style="width:70px" class="form-control l-nicotine" name="nicotine">
                                @foreach(config('store.nicotine_levels') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="checkbox liquid-form-group" style="padding-top:16px;">
                            <div class="togglebutton">
                                <label>
                                    Extra <input type="checkbox" class="l-extra" id="extra" name="extra" />
                                </label>
                            </div>
                        </div>
                        <div class="form-group liquid-form-group">
                            <label for="menthol">Menthol</label>
                            <select style="width:80px" class="form-control l-menthol" name="menthol">
                                @foreach(config('store.menthol_levels') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group liquid-form-group">
                            <label for="vg">VG</label>
                            <select style="width:70px" class="form-control l-vg" name="vg">
                                @foreach(config('store.vg_levels') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Liquid</button>
                </div>
            </form>
        </div>
    </div>
</div>