<div class="liquid-group">
    <fieldset class="form-inline liquid-fs" name="liquids[]">
        <div class="form-group liquid-form-group">
            <label for="size">Size</label>
            <select style="width:70px" class="form-control l-size" name="liquids[0][size]">
                <option value="10">10ml</option>
                <option value="30">30ml</option>
                <option value="50">50ml</option>
                <option value="100">100ml</option>
                <option value="250">250ml</option>
            </select>
        </div>
        <div class="form-group liquid-form-group">
            <label for="liquid">Flavor</label>
            <select style="width:300px" class="form-control l-select" name="liquids[0][recipe]">
                @foreach($recipes as $recipe)
                    <option value="{{ $recipe->id }}">{{ $recipe->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group liquid-form-group">
            <label for="nicotine">Nicotine</label>
            <select style="width:70px" class="form-control l-nicotine" name="liquids[0][nicotine]">
                @for($i = 0; $i <= 30; $i++)
                    <option value="$i">{{ $i }}mg</option>
                @endfor
            </select>
        </div>
        <div class="checkbox liquid-form-group" style="padding-top:16px;">
            <div class="togglebutton">
                <label>
                    Extra<input type="checkbox" class="l-extra" id="extra" name="liquids[0][extra]" />
                </label>
            </div>
        </div>
        <div class="form-group liquid-form-group">
            <label for="menthol">Menthol</label>
            <select style="width:80px" class="form-control l-menthol" name="liquids[0][menthol]">
                <option value="0">None</option>
                <option value="1">Light</option>
                <option value="2">Medium</option>
                <option value="3">Heavy</option>
                <option value="4">Super</option>
            </select>
        </div>
        <div class="form-group liquid-form-group">
            <label for="vg">VG</label>
            <select style="width:70px" class="form-control l-vg" name="liquids[0][vg]">
                <option value="40">40%</option>
                <option value="0">0%</option>
                <option value="20">20%</option>
                <option value="30">30%</option>
                <option value="50">50%</option>
                <option value="60">60%</option>
                <option value="70">70%</option>
                <option value="80">80%</option>
                <option value="100">MAX</option>
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
</div>