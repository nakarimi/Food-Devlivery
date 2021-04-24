<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">

            @if(Auth::user()->role->name == "finance_officer")
                <ul>
                    <li class="menu-title">
                        <span>Main</span>
                    </li>
                    <li @if (\Request::is('finance_officer/dashboard*')) class="active" @endif>
                        <a href="{{url('/finance_officer/dashboard')}}"><i class="la la-dashboard"></i> <span>Dashboard </span></a>
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
            @elseif(Auth::user()->role->name == "finance_manager")
                <ul>
                    <li class="menu-title">
                        <span>Main</span>
                    </li>
                    <li @if (\Request::is('finance_manager/dashboard*')) class="active" @endif>
                        <a href="{{url('/finance_manager/dashboard')}}"><i class="la la-dashboard"></i> <span>Dashboard </span></a>
                    </li>

                    <li class="submenu">
                        <a href="#"><i class="la la-money"></i> <span class="menu-title">Drivers Payment</span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            {{--  <li><a  @if (\Request::is('orders/create*')) class="active" @endif href="{{ route('orders.create') }}">Add Order</a></li>--}}
                            <li><a  @if (\Request::is('active_payments')) class="active" @endif href="{{ route('driver.active_payments') }}">Active payments</a></li>
                            <li><a  @if (\Request::is('drivers_payment_history')) class="active" @endif href="{{ route('driverPaymentHistory') }}">History of Payments</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#"><i class="la la-money"></i> <span class="menu-title">Restaurants Payment</span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <li><a  @if (\Request::is('restaurants_pending_payments')) class="active" @endif href="{{ route('restaurantPendingPayments') }}">Active payments</a></li>
                            <li><a  @if (\Request::is('restaurants_payment_history')) class="active" @endif href="{{ route('restaurantPaymentHistory') }}">History of Payments</a></li>
                        </ul>
                    </li>
                </ul>
            @endif

        </div>
    </div>

</div>

