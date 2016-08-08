<table class="table">
    <tr class="active">
        <td><strong>Products</strong></td>
        <td colspan="4"><strong>Name</strong></td>
        <td><strong>Quantity</strong></td>
        <td><strong>Price</strong></td>
    </tr>
    @foreach($order->productInstances as $instance)
        <tr>
            <td><a href="#" class="btn btn-sm btn-danger">Remove</a></td>
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
    @foreach($order->liquids as $liquid)
        <tr>
            <td><a href="#" class="btn btn-sm btn-danger">Remove</a></td>
            <td>{{ $liquid->recipe->name }}</td>
            <td>{{ $liquid->size }}ml</td>
            <td>{{ $liquid->nicotine }}mg</td>
            <td>{{ $liquid->vg }}%</td>
            <td>{{ $liquid->menthol }}</td>
            <td>{{ $liquid->getPrice() }}</td>
        </tr>
    @endforeach
    <tr class="active">
        <td><strong>Discounts</strong></td>
        <td colspan="5"><strong>Name</strong></td>
        <td><strong>Amount</strong></td>
    </tr>
    @foreach($order->discounts as $discount)
        <tr class="danger">
            <td><a href="#" class="btn btn-sm btn-danger">Remove</a></td>
            <td>{{ $discount->name }}</td>
            <td>{{ $discount->calculate() }}</td>
        </tr>
    @endforeach
    <tr class="warning">
        <td colspan="6"><strong>SubTotal</strong></td>
        <td>{{ $order->subtotal }}</td>
    </tr>
    <tr class="info">
        <td colspan="6"><strong>Tax</strong></td>
        <td>{{ $order->subtotal * .06 }}</td>
    </tr>
    <tr class="success">
        <td colspan="6"><strong>Total</strong></td>
        <td>{{ $order->total }}</td>
    </tr>
    <tr class="danger">
        <td colspan="6"><strong>Remaining Balance</strong></td>
        <td>{{ $order->getRemainingBalance() }}</td>
    </tr>
</table>