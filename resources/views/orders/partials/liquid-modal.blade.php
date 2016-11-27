<!-- Liquid Modal -->
<div class="modal fade" id="liquid" tabindex="-1" role="dialog" aria-labelledby="liquid">
    <div class="modal-dialog" role="document" style="width:60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add A Liquid To The Order</h4>
            </div>
            <form method="post" action="/orders/{{ $order->id }}/add-liquid">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div id="last-liquid" class="well" v-show="liquids != 'fail'">
                        <h4>Previous Orders</h4>
                        <table class="table">
                            <thead>
                                <th>Re-Order</th>
                                <th>Size</th>
                                <th>Flavor</th>
                                <th>Nicotine</th>
                                <th>Menthol</th>
                                <th>VG</th>
                            </thead>
                            <tbody>
                                <tr v-for="liquid in liquids">
                                    <td>Coming Soon</td>
                                    <td>@{{ liquid.size }}ml</td>
                                    <td>@{{ liquid.recipe }}</td>
                                    <td>@{{ liquid.nicotine }}mg</td>
                                    <td>@{{ liquid.menthol }}</td>
                                    <td>@{{ liquid.vg }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    @include('orders.partials.liquid-fieldset')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Liquid</button>
                </div>
            </form>
        </div>
    </div>
</div>