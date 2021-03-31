
<tr>
    <th> Customer </th>
    <td> {{ $order->customer->name }} </td>
</tr>

<tr>
    <th> Delivery Option </th>
    <td>
        @if (!$order->has_delivery) By Customer @endif
        @if ($order->has_delivery == 1 and $order->deliveryDetails->delivery_type == 'own') By Restaurant
        @endif
        @if ($order->has_delivery == 1 and $order->deliveryDetails->delivery_type == 'company') By Company
        @endif
    </td>
</tr>

<tr>
    <th> Total </th>
    <td> {{ $order->total }} </td>
</tr>

<tr>
    <th> Note </th>
    <td> {{ $order->note }} </td>
</tr>

<tr>
    <th> Satus </th>
    <td> {{ ucfirst($order->status) }} </td>
</tr>

<tr>
    <th> Reciever Phone </th>
    <td> {{ $order->reciever_phone }} </td>
</tr>

<tr>
    <th> Items </th>
    <td>{!! show_order_itmes($order->contents) !!}</td>
</tr>
