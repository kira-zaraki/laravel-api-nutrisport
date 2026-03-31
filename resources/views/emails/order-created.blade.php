<h2>Order Confirmation</h2>

<p>Order ID: {{ $order->id }}</p>

<p>Total: {{ $order->total }} €</p>

<p>Site: {{ $order->site->domain }}</p>

<h3>Products</h3>

<ul>
@foreach($order->items as $item)
<li>
{{ $item->product->name }} - 
Quantity: {{ $item->quantity }} - 
Price: {{ $item->price }} €
</li>
@endforeach
</ul>

<p>Thank you for your order.</p>