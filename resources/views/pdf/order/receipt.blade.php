<html>
    <head>
        <title>Order {{ $order->id }} Receipt</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <img src="http://frost.com/img/joltreceipt.jpg" style=""/>
                </div>
            </div>
        </div>
        <table style="width:100%">
            <tr>
                <td class="text-center">
                    5654 Balsam Dr.<br/>
                    Suite 200<br/>
                    Hudsonville, MI 49426<br/>
                    (616) 308-8300
                </td>
                <td class="text-center">
                    2730 44th ST SW<br/>
                    Suite D<br/>
                    Wyoming, MI 49519<br/>
                    (616) 288-5220
                </td>
                <td class="text-center">
                    1136 W Randall<br/>
                    Suite G<br/>
                    Coopersville, MI 49404<br/>
                    (616) 438-4000
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-center">
                    <br/>
                    Order online at <strong><u>https://joltvapor.com!</u></strong>
                </td>
            </tr>
        </table>
        <br/>
        <div class="text-center">
            {{ date('m-d-Y h:ia') }}<br/>
            Order #: {{ $order->id }}
        </div>
        <br/>
        <table class="table" style="width:100%">
            <tr>
                <td width="50%"><strong><u>Product</u></strong></td>
                <td width="20%"><strong><u>Price</u></strong></td>
                <td width="10%"><strong><u>Qty</u></strong></td>
                <td width="20%"><strong><u>Total</u></strong></td>
            </tr>
            @foreach($order->productInstances as $instance)
                <tr>
                    <td>{{ $instance->product->name }}</td>
                    <td>${{ $instance->price }}</td>
                    <td>{{ $instance->pivot->quantity }}</td>
                    <td>${{ number_format($instance->price * $instance->pivot->quantity, 2) }}</td>
                </tr>
            @endforeach
            @foreach($order->liquidProducts as $liquid)
                <tr>
                    <td>{{ $liquid->size }}ml {{ $liquid->recipe->name }}</td>
                    <td>${{ $liquid->getPrice() }}</td>
                    <td>1</td>
                    <td>${{ $liquid->getPrice() }}</td>
                </tr>
            @endforeach
            @if($order->discounts->count() > 0)
                <tr>
                    <td colspan="3"><strong><u>Discounts</u></strong></td>
                    <td><strong><u>Amount</u></strong></td>
                </tr>
                @foreach($order->discounts as $discount)
                    <tr>
                        <td colspan="3">{{ $discount->name }}</td>
                        <td>-${{ $discount->pivot->applied }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td></td>
                <td colspan="2" align="right"><strong>Subtotal:</strong></td>
                <td>${{ $order->subtotal }}</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2" align="right"><strong>Tax:</strong></td>
                <td>${{ number_format($order->subtotal * 0.06, 2) }}</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2" align="right"><strong>Total:</strong></td>
                <td>${{ $order->total }}</td>
            </tr>
            @if($order->customer)
                <tr>
                    <td colspan="4"><strong>Points Earned: {{ $order->calculator()->getPoints() }}</strong></td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Point Balance: {{ $order->customer->points }}</strong></td>
                </tr>
            @endif
            <tr>
                <td colspan="4" align="center">
                    All sales are final and cannot be returned for the following items: Tanks, Coils, E-Liquid, and Accessories.
                    All batteries are exchange or store credit only and will have a 30 day warranty from the date of purchase for manufacturer defects only.
                    Receipt is required to look up your order
                </td>
            </tr>
        </table>
    </body>
</html>



