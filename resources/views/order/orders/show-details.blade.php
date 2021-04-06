
<tr>
    <th> {{ (get_role() == "restaurant") ? 'نام مشتری' : 'Customer'}} </th>
    <td> {{ $order->customer->name }} </td>
</tr>

<tr>
    <th> {{ (get_role() == "restaurant") ? 'نوع ارسال' : 'Delivery Option'}} </th>
    <td>
        @if (!$order->has_delivery) By Customer @endif
        @if ($order->has_delivery == 1 and $order->deliveryDetails->delivery_type == 'own') By Restaurant
        @endif
        @if ($order->has_delivery == 1 and $order->deliveryDetails->delivery_type == 'company') By Company
        @endif
    </td>
</tr>

<tr>
    <th> {{ (get_role() == "restaurant") ? 'مجموع' : 'Total'}} </th>
    <td> {{ $order->total }} </td>
</tr>

<tr>
    <th> {{ (get_role() == "restaurant") ? 'یادداشت' : 'Note'}} </th>
    <td> {{ $order->note }} </td>
</tr>

<tr>
    <th> {{ (get_role() == "restaurant") ? 'حالت' : 'Satus'}} </th>
    <td>         
       <b>{{translate_status($order->status)}}</b>
    </td>
</tr>

@if((get_role() != "restaurant")) 
    <th> Reciever Phone </th>
    <td> {{ $order->reciever_phone }} </td>
@endif

<tr>
    
</tr>

<tr>
    <th> {{ (get_role() == "restaurant") ? 'محتویات' : 'Items'}} </th>
    <td @if(get_role() == "restaurant") style="float: right;" @endif >{!! show_order_itmes($order->contents) !!}</td>
</tr>
