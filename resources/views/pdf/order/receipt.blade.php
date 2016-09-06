<h1>Order #: {{ $order->id }}</h1>
<ul>
    @foreach($order->liquidProducts as $liquid)
        <li>{{ $liquid->size }}ml</li>
        <li>{{ $liquid->recipe->name }}</li>
    @endforeach
</ul>
