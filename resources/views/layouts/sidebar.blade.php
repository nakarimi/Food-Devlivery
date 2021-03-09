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
                    <a href="#"><i class="la la-users"></i> <span class="menu-title">Users</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a  @if (\Request::is('admin/users/create*')) class="active" @endif href="{{ route('users.create') }}">Add User</a></li>
                        <li><a  @if (\Request::is('admin/users')) class="active" @endif href="{{ route('users.index') }}">All Users</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-recycle"></i> <span class="menu-title">Branch</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a  @if (\Request::is('branch/create')) class="active" @endif href="{{ route('branch.create') }}">Add Branch</a></li>
                        <li><a @if (\Request::is('pendingBranches')) class="active" @endif href="{{route('branches.pending')}}">Pending Branches</a></li>
                        <li><a @if (\Request::is('approvedBranches')) class="active" @endif href="{{route('branches.approved')}}">Approved Branches</a></li>
                        <li><a  @if (\Request::is('branch')) class="active" @endif href="{{ route('branch.index') }}">All Branches</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-sign-out"></i> <span class="menu-title">Commission</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a  @if (\Request::is('commission/create*')) class="active" @endif href="{{ route('commission.create') }}">Add Commission</a></li>
                        <li><a  @if (\Request::is('commission')) class="active" @endif href="{{ route('commission.index') }}">All Commissions</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-truck"></i> <span class="menu-title">Driver</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a  @if (\Request::is('driver/create*')) class="active" @endif href="{{ route('driver.create') }}">Add Driver</a></li>
                        <li><a  @if (\Request::is('driver')) class="active" @endif href="{{ route('driver.index') }}">All Drivers</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-money"></i> <span class="menu-title">Payment</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a  @if (\Request::is('payment/create*')) class="active" @endif href="{{ route('payment.create') }}">Add Payment</a></li>
                        <li><a  @if (\Request::is('payment')) class="active" @endif href="{{ route('payment.index') }}">All Payments</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-list"></i><span class="menu-title">Item</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{route('item.create')}}" @if (\Request::is('item/create*')) class="active" @endif>Create New Item</a></li>
                        <li><a href="{{route('items.pending')}}" @if (\Request::is('pendingItems*')) class="active" @endif>Pending Items</a></li>
                        <li><a href="{{route('items.approved')}}" @if (\Request::is('approvedItems*')) class="active" @endif>Approved Items</a></li>
                        <li><a href="{{route('item.index')}}" @if (\Request::is('item')) class="active" @endif>All Items</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-yelp"></i> <span class="menu-title">Category</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a  @if (\Request::is('category/create*')) class="active" @endif href="{{ route('category.create') }}">Add Category</a></li>
                        <li><a  @if (\Request::is('category')) class="active" @endif href="{{ route('category.index') }}">All Categories</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-edit"></i> <span class="menu-title">Menu</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a  @if (\Request::is('menu/create*')) class="active" @endif href="{{ route('menu.create') }}">Add Menu</a></li>
                        <li><a  @if (\Request::is('menu')) class="active" @endif href="{{ route('menu.index') }}">All Menus</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"><i class="la la-legal"></i> <span class="menu-title">Orders</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
{{--                        <li><a  @if (\Request::is('orders/create*')) class="active" @endif href="{{ route('orders.create') }}">Add Order</a></li>--}}
                        <li><a  @if (\Request::is('orders')) class="active" @endif href="{{ route('orders.index') }}">All Orders</a></li>
                    </ul>
                </li>

                <li class="menu-title">
                    <span>System</span>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-lock"></i> <span>Security</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">

                        <li>
                            <a @if (\Request::is('admin/activitylogs*')) class="active" @endif href="{{url('/admin/activitylogs')}}">Activity Logs</a>
                        </li>

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

