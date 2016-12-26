<div class="panel-body">

    <div class="col-md-3">
        <div class="form-group">
            <label for="start" class="col-md-2 control-label">Start</label>
            <div class="input-group col-md-10">
                <input type="text" id="start" name="start" class="form-control datepicker" value="{{ $filters['start'] }}"/>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="end" class="col-md-2 control-label">End</label>
            <div class="input-group col-md-10">
                <input type="text" id="end" name="end" class="form-control datepicker" value="{{ $filters['end'] }}"/>
                <span class="input-group-addon">
                    <span class="fa fa-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <select name="store" id="store-select" style="width:80%">
                <option value="0">All Stores</option>
                @foreach(config('store.stores') as $key => $store)
                    <option value="{{ $key }}" {{ ($filters['store'] == $key) ? 'selected' : '' }}>{{ $store }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <select name="type" id="report-type" style="width:80%">
                <option value="detailed" {{ ($filters['type'] == 'detailed') ? 'selected' : '' }}>Detailed Report</option>
                <option value="minimal" {{ ($filters['type'] == 'minimal') ? 'selected' : '' }}>Minimal Report</option>
            </select>
        </div>
    </div>
    <div class="col-md-1">
        <button id="filter" class="btn btn-raised btn-success">
            Generate <i class="fa fa-spinner fa-pulse fa-fw" aria-hidden="true"></i>
        </button>
    </div>
</div>