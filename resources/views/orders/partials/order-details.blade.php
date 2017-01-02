<table class="table">
    <tr class="active">
        <td width="13%"><strong>Products</strong></td>
        <td colspan="4"><strong>Name</strong></td>
        <td><strong>Quantity</strong></td>
        <td><strong>Price</strong></td>
    </tr>
    @foreach($order->productInstances as $instance)
        <tr>
            <td><a href="/orders/{{ $order->id }}/remove-product/{{ $instance->pivot->id }}" class="btn btn-sm btn-danger" style="margin:0px">x</a></td>
            <td colspan="4">{{ $instance->product->name }}</td>
            <td>
                <a href="/orders/{{ $order->id }}/quantity-update?pid={{ $instance->pivot->id }}&inc=minus">
                    <span class="glyphicon glyphicon-minus-sign"></span>
                </a>
                {{ $instance->pivot->quantity }}
                <a href="/orders/{{ $order->id }}/quantity-update?pid={{ $instance->pivot->id }}&inc=plus">
                    <span class="glyphicon glyphicon-plus-sign"></span>
                </a>
            </td>
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
            <td>
                <a href="/orders/{{ $order->id }}/remove-liquid/{{ $liquid->id }}" class="btn btn-sm btn-danger" style="margin:0px">x</a>
                <a href="/orders/duplicate-liquid/{{ $liquid->id }}" class="btn btn-sm btn-success" style="margin:0px">+</a>
            </td>
            <td>{{ $liquid->recipe->name }} {{ ($liquid->extra) ? 'XTRA' : '' }}</td>
            <td>{{ $liquid->size }}ml</td>
            <td>{{ $liquid->nicotine }}mg</td>
            <td>{{ config('store.vg_levels')[$liquid->vg] }}</td>
            <td>{{ config('store.menthol_levels')[$liquid->menthol] }}</td>
            <td>${{ $liquid->getPrice() }}</td>
        </tr>
    @endforeach
    @if(count($order->discounts) > 0)
        <tr class="active">
            <td><strong>Discounts</strong></td>
            <td colspan="5"><strong>Name</strong></td>
            <td><strong>Amount</strong></td>
        </tr>
        @foreach($order->discounts as $discount)
            <tr class="danger">
                <td><a href="/orders/{{ $order->id }}/remove-discount/{{ $discount->pivot->id }}" class="btn btn-sm btn-danger" style="margin:0px">Remove</a></td>
                <td>{{ $discount->name }}</td>
                <td colspan="4"></td>
                <td><strong>-${{ $discount->pivot->applied }}</strong></td>
            </tr>
        @endforeach
    @endif


    <tr class="warning">
        <td colspan="6"><strong class="pull-right">Subtotal</strong></td>
        <td>${{ number_format($order->subtotal,2) }}</td>
    </tr>
    <tr class="info">
        <td colspan="6"><strong class="pull-right">Tax</strong></td>
        <td>${{ number_format(($order->subtotal * .06),2) }}</td>
    </tr>
    <tr class="success">
        <td colspan="6"><strong class="pull-right">Total</strong></td>
        <td>${{ number_format($order->total,2) }}</td>
    </tr>
    @if(count($order->payments) > 0)
        <tr class="active">
            <td><strong>Payments</strong></td>
            <td colspan="5"><strong>Type</strong></td>
            <td><strong>Amount</strong></td>
        </tr>
        @foreach($order->payments as $payment)
            <tr class="success">
                <td>
                    @if (Auth::user()->hasRole('manager'))
                        <a href="/orders/payment/{{ $payment->id }}/delete" class="btn btn-raised btn-danger">Remove</a>
                    @endif
                </td>
                <td colspan="5">{{ ucfirst($payment->type) }}</td>
                <td>${{ number_format($payment->amount,2) }}</td>
            </tr>
        @endforeach
    @endif
    <tr class="danger">
        <td colspan="6"><strong class="pull-right">Remaining Balance</strong></td>
        <td>${{ $order->calculator()->getRemainingBalance() }}</td>
    </tr>
</table>