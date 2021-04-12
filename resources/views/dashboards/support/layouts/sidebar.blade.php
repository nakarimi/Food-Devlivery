<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <li @if (\Request::is('admin/dashboard*')) class="active" @endif>
                    <a href="{{url('/admin/dashboard')}}"><i class="la la-dashboard"></i> <span>Dashboard </span></a>
                </li>
                @php $orderChanges = (@$sidebarData['waitingOrders'] + @$sidebarData['activeOrders']) ?: 0; @endphp
                <li class="submenu">
                    <a href="#"><i class="la la-legal"></i> <span class="menu-title">Orders @if($orderChanges != 0) <span class="badge badge-danger custom-badge-en-parent">{{$orderChanges}}</span> @endif</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        {{--  <li><a  @if (\Request::is('orders/create*')) class="active" @endif href="{{ route('orders.create') }}">Add Order</a></li>--}}
                        <li><a  @if (\Request::is('waitingOrders')) class="active" @endif href="{{ url('waitingOrders') }}">Waiting Orders</a>@if($sidebarData['waitingOrders'] != 0) <span class="badge badge-danger custom-badge-en">{{$sidebarData['waitingOrders']}}</span>@endif</li>
                        <li><a  @if (\Request::is('activeOrders')) class="active" @endif href="{{url('activeOrders')}}">Active Orders</a>@if($sidebarData['activeOrders'] != 0)<span class="badge badge-danger custom-badge-en">{{$sidebarData['activeOrders']}}</span>@endif</li>
                        <li><a  @if (\Request::is('order-history')) class="active" @endif href="{{ route('order.history') }}">Orders History</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>

</div>

