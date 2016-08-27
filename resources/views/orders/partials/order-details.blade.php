<table class="table">
    <tr class="active">
        <td><strong>Products</strong></td>
        <td colspan="4"><strong>Name</strong></td>
        <td><strong>Quantity</strong></td>
        <td><strong>Price</strong></td>
    </tr>
    @foreach($order->productInstances as $instance)
        <tr>
            <td><a href="#" class="btn btn-sm btn-danger" style="margin:0px">Remove</a></td>
            <td colspan="4">{{ $instance->product->name }}</td>
            <td>{{ $instance->pivot->quantity }}</td>
            <td>${{ $instance->price * $instance->pivot->quantity }}</td>
        </tr>
    @endforeach
    <tr class="active">
        <td><strong>Liquids</strong></td>
        <td><strong>Flavor</strong></td>
        <td><strong>Size</strong></td>
        <td><strong>Nicotine</strong></td>
        <td><strong>VG%</strong></td>
        <td><strong>Menthol</strong></td>
        <td><strong>Price</strong></td>
    </tr>
    @foreach($order->liquidProducts as $liquid)
        <tr>
            <td><a href="#" class="btn btn-sm btn-danger" style="margin:0px">Remove</a></td>
            <td>{{ $liquid->recipe->name }} {{ ($liquid->extra) ? 'XTRA' : '' }}</td>
            <td>{{ $liquid->size }}ml</td>
            <td>{{ $liquid->nicotine }}mg</td>
            <td>{{ $liquid->vg }}%</td>
            <td>{{ $liquid->menthol }}</td>
            <td>${{ $liquid->getPrice() }}</td>
        </tr>
    @endforeach
    <tr class="active">
        <td><strong>Discounts</strong></td>
        <td colspan="5"><strong>Name</strong></td>
        <td><strong>Amount</strong></td>
    </tr>
    @foreach($order->discounts as $discount)
        <tr class="danger">
            <td><a href="#" class="btn btn-sm btn-danger" style="margin:0px">Remove</a></td>
            <td>{{ $discount->name }}</td>
            <td>{{ $discount->calculate() }}</td>
        </tr>
    @endforeach
    <tr class="warning">
        <td colspan="6"><strong class="pull-right">Subtotal</strong></td>
        <td>${{ $order->subtotal }}</td>
    </tr>
    <tr class="info">
        <td colspan="6"><strong class="pull-right">Tax</strong></td>
        <td>${{ number_format(($order->subtotal * .06),2) }}</td>
    </tr>
    <tr class="success">
        <td colspan="6"><strong class="pull-right">Total</strong></td>
        <td>${{ $order->total }}</td>
    </tr>
    <tr class="danger">
        <td colspan="6"><strong class="pull-right">Remaining Balance</strong></td>
        <td>${{ $order->calculator()->getRemainingBalance() }}</td>
    </tr>
</table>