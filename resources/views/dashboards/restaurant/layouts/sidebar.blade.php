<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>سیستم</span>
                </li>
                <li @if (\Request::is('restaurant/dashboard*')) class="active" @endif>
                    <a href="{{url('/')}}"><i class="la la-dashboard"></i><span>داشبورد</span></a>
                </li>
                @php $itemChanges = (@$sidebarData['pendingItems'] + @$sidebarData['rejectedItems']) ?: 0; @endphp
                <li class="submenu">
                    <a href="#"><i class="la la-list"></i><span class="menu-title"> غذا ها @if($itemChanges != 0) <span class="badge badge-danger custom-badge">{{$itemChanges}}</span> @endif</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{route('item.create')}}" @if (\Request::is('item/create*')) class="active" @endif>اضافه کردن غذا جدید</a></li>
                        <li><a href="{{route('items.approved')}}" @if (\Request::is('approvedItems*')) class="active" @endif>غذا ها </a></li>
                        <li><a href="{{route('items.pending')}}" @if (\Request::is('pendingItems*')) class="active" @endif>غذا های معطل @if($sidebarData['pendingItems'] != 0)<span class="badge badge-danger custom-badge">{{$sidebarData['pendingItems']}}</span>@endif </a></li>
                        <li><a href="{{route('items.rejected')}}" @if (\Request::is('rejectedItems*')) class="active" @endif>غذا های رد شده @if($sidebarData['rejectedItems'] != 0)<span class="badge badge-danger custom-badge">{{$sidebarData['rejectedItems']}}</span>@endif </a></li>
                    </ul>
                </li>

                <li @if (\Request::is('menu*')) class="active" @endif>
                    <a href="{{route('menu.index')}}"><i class="la la-edit"></i><span>مینیو</span></a>
                </li>
                @php $orderChanges = (@$sidebarData['activeOrders']) ?: 0; @endphp
                <li class="submenu">
                    <a href="#"><i class="la la-legal"></i> <span class="menu-title">@if($orderChanges != 0) <span class="badge badge-danger custom-badge">{{$orderChanges}}</span> @endifسفارشات</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        {{--  <li><a  @if (\Request::is('orders/create*')) class="active" @endif href="{{ route('orders.create') }}">Add Order</a></li>--}}
                        {{-- <li><a  @if (\Request::is('orders')) class="active" @endif href="{{ route('orders.waitingOrders') }}">Waiting Orders</a></li> --}}
                        <li><a  @if (\Request::is('activeOrders')) class="active" @endif href="{{ url('/activeOrders') }}"> @if($sidebarData['activeOrders'] != 0)<span class="badge badge-danger custom-badge">{{$sidebarData['activeOrders']}}</span>@endifسفارشات فعال</a></li>
                        <li><a  @if (\Request::is('order-history')) class="active" @endif href="{{ route('order.history') }}">تاریخچه سفارشات</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-money"></i> <span>پرداخت ها</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li>
                            {{-- <a  @if (\Request::is('paymentsCreate')) class="active" @endif href="{{ url('paymentsCreate') }}"><span>اضافه کردن پرداخت جدید</span></a> --}}
                        </li>
                        <li>
                            <a  @if (\Request::is('active-payments')) class="active" @endif href="{{ route('active.payments') }}"><span>  پرداخت های فعال</span></a>
                        </li>
                        <li>
                            <a  @if (\Request::is('payment-history')) class="active" @endif href="{{ route('payment.history') }}"><span>  پرداخت های قبلی</span></a>
                        </li>

                    </ul>
                </li>
                <li @if (\Request::is('favorited-by')) class="active" @endif>
                    <a href="{{url('/favorited-by')}}"><i class="la la-heart"></i><span>مشتریان علاقمند</span></a>
                </li>


        </div>
    </div>

</div>

