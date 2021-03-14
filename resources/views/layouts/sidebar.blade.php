<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <li @if (\Request::is('admin/dashboard*')) class="active" @endif>
                    <a href="{{url('/admin/dashboard')}}"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-legal"></i> <span class="menu-title">Orders</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        {{--  <li><a  @if (\Request::is('orders/create*')) class="active" @endif href="{{ route('orders.create') }}">Add Order</a></li>--}}
                        {{-- <li><a  @if (\Request::is('orders')) class="active" @endif href="{{ route('orders.waitingOrders') }}">Waiting Orders</a></li> --}}
                        <li><a  @if (\Request::is('orders')) class="active" @endif href="{{ route('orders.index') }}">Active Orders</a></li>
                        <li><a  @if (\Request::is('order-history')) class="active" @endif href="{{ route('order.history') }}">Orders History</a></li>
                    </ul>
                </li>

                 <li class="submenu">
                    <a href="#"><i class="la la-list"></i><span class="menu-title">Item</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{route('items.pending')}}" @if (\Request::is('pendingItems*')) class="active" @endif>Pending Items</a></li>
                        <li><a href="{{route('items.approved')}}" @if (\Request::is('approvedItems*')) class="active" @endif>Approved Items</a></li>
                        <li><a  @if (\Request::is('category')) class="active" @endif href="{{ route('category.index') }}">Category</a></li>
                        {{-- <li><a  @if (\Request::is('menu')) class="active" @endif href="{{ route('menu.index') }}">Menus</a></li> --}}
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-recycle"></i> <span class="menu-title">Branch</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a @if (\Request::is('pendingBranches')) class="active" @endif href="{{route('branches.pending')}}">Pending Branches</a></li>
                        <li><a @if (\Request::is('approvedBranches')) class="active" @endif href="{{route('branches.approved')}}">Approved Branches</a></li>
                    </ul>
                </li>
                
                <li>
                    <a  @if (\Request::is('driver')) class="active" @endif href="{{ route('driver.index') }}"><i class="la la-truck"></i> <span class="menu-title">Drivers</span> </a>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-user-times"></i> <span class="menu-title">Finance</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a  @if (\Request::is('payment')) class="active" @endif href="{{ route('payment.index') }}">Payments</a></li>
                        <li><a  @if (\Request::is('commission')) class="active" @endif href="{{ route('commission.index') }}">Commissions</a></li>
                    </ul>
                </li>

                <li class="menu-title">
                    <span>System</span>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-lock"></i> <span>Security</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">

                        <li><a  @if (\Request::is('admin/users')) class="active" @endif href="{{ route('users.index') }}">Users</a></li>
                        <li><a  @if (\Request::is('blockedCustomer')) class="active" @endif href="{{ route('blockedCustomer.index') }}">Blocked Customers</a></li>
                        <li>
                            <a @if (\Request::is('admin/activitylogs*')) class="active" @endif href="{{url('/admin/activitylogs')}}">Activity Logs</a>
                        </li>
                        <li><a  @if (\Request::is('admin/backup')) class="active" @endif href="#">Backup & Restore</a></li>
                        
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-code"></i> <span> Developer Options</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">

                        <li>
                            <a @if (\Request::is('admin/settings*')) class="active" @endif href="{{url('/admin/settings')}}"> <span>Settings</span></a>
                        </li>

                        <li>
                            <a @if (\Request::is('admin/generator*')) class="active" @endif href="{{url('/admin/generator')}}">Generator</a>
                        </li>
                    </ul>
                </li>


            </ul>

        </div>
    </div>

</div>

