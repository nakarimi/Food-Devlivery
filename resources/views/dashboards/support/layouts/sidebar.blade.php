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
                <li class="submenu">
                    <a href="#"><i class="la la-money"></i> <span class="menu-title">Payments</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        {{--  <li><a  @if (\Request::is('orders/create*')) class="active" @endif href="{{ route('orders.create') }}">Add Order</a></li>--}}
                        <li><a  @if (\Request::is('pendingPayments')) class="active" @endif href="{{ url('pendingPayments') }}">Pending Payments</a></li>
                        <li><a  @if (\Request::is('activePayments')) class="active" @endif href="{{url('activePayments')}}">Active Payments</a></li>
                        <li><a  @if (\Request::is('paymentHistory')) class="active" @endif href="{{ url('paymentHistory') }}">Payments History</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>

</div>

