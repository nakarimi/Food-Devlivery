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
                <li class="submenu">
                    <a href="#"><i class="la la-list"></i><span> غذا ها</span> <span class="menu-arrow"></span></a>
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
                <li class="submenu">
                    <a href="#"><i class="la la-legal"></i> <span>سفارشات</span> <span class="menu-arrow"></span></a>
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
                            <a  @if (\Request::is('paymentsCreate')) class="active" @endif href="{{ url('paymentsCreate') }}"><span>اضافه کردن پرداخت جدید</span></a>
                        </li>
                        <li>
                            <a  @if (\Request::is('paymentHistory')) class="active" @endif href="{{ route('paymentHistory') }}"><span> لست پرداخت ها</span></a>
                        </li>

                    </ul>
                </li>


        </div>
    </div>

</div>

