<!-- Redeem Points Modal -->
<div class="modal fade" id="redeem" tabindex="-1" role="dialog" aria-labelledby="redeem">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Create new Ingredient</h4>
            </div>
            <form method="post" action="/admin/store/ingredients/create">
                <div class="modal-body">
                    @foreach ($errors->all() as $error)
                        <p class="alert alert-danger">{{ $error }}</p>
                    @endforeach
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <div class="form-group">
                        <label for="name" class="col-lg-2 control-label">Name</label>
                        <div class="col-lg-10 input-group">
                            <input type="text" class="form-control" placeholder="Flavor Name" id="name" name="name">
                            <span class="help-block">The name of the flavor concentrate.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="vendor" class="col-lg-2 control-label">Vendor</label>
                        <div class="col-lg-10 input-group">
                            <input type="text" class="form-control" placeholder="Company Name" id="vendor" name="vendor">
                            <span class="help-block">The name of the vendor who sells the flavor.</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
            </form>
        </div>
    </div>
</div>