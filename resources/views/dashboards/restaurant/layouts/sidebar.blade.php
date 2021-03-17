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
                        <li><a href="{{route('items.pending')}}" @if (\Request::is('pendingItems*')) class="active" @endif>غذا ها معطل</a></li>
                        <li><a href="{{route('items.approved')}}" @if (\Request::is('approvedItems*')) class="active" @endif>غذا ها تایید شده</a></li>
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
                        <li><a  @if (\Request::is('activeOrders')) class="active" @endif href="{{ url('/activeOrders') }}">سفارشات فعال</a></li>
                        <li><a  @if (\Request::is('order-history')) class="active" @endif href="{{ route('order.history') }}">تاریخچه سفارشات</a></li>
                    </ul>
                </li>

                <li @if (\Request::is('paymentHistory')) class="active" @endif>
                    <a href="{{ route('payment.index') }}"><i class="la la-money"></i><span>پرداخت ها</span></a>
                </li>

        </div>
    </div>

</div>

