<template id="liquid-fieldset-template">
        <fieldset class="form-inline liquid-fs" name="liquids[]">
            <div class="form-group liquid-form-group">
                <label for="size">Size</label>
                <select style="width:70px" class="form-control l-size" name="liquids[@{{ fsindex }}][size]">
                    @foreach(config('store.bottle_sizes') as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group liquid-form-group">
                <label for="liquid">Flavor</label>
                <select style="width:300px" class="form-control l-select" name="liquids[@{{ fsindex }}][recipe]">
                    <option value="0">None</option>
                    @foreach($recipes as $recipe)
                        <option value="{{ $recipe->id }}">{{ $recipe->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group liquid-form-group">
                <label for="nicotine">Nicotine</label>
                <select style="width:70px" class="form-control l-nicotine" name="liquids[@{{ fsindex }}][nicotine]">
                    @foreach(config('store.nicotine_levels') as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="checkbox liquid-form-group" style="padding-top:16px;">
                <div class="togglebutton">
                    <label>
                        Extra<input type="checkbox" class="l-extra" id="extra" name="liquids[@{{ fsindex }}][extra]" />
                    </label>
                </div>
            </div>
            <div class="form-group liquid-form-group">
                <label for="menthol">Menthol</label>
                <select style="width:80px" class="form-control l-menthol" name="liquids[@{{ fsindex }}][menthol]">
                    @foreach(config('store.menthol_levels') as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group liquid-form-group">
                <label for="vg">VG</label>
                <select style="width:70px" class="form-control l-vg" name="liquids[@{{ fsindex }}][vg]">
                    @foreach(config('store.vg_levels') as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group liquid-form-group">
                <div class="input-group">
                <span class="input-group-btn">
                    <button class="btn btn-success liquid-add" type="button">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
                </div>
            </div>
        </fieldset>
</template>

<script>

</script>