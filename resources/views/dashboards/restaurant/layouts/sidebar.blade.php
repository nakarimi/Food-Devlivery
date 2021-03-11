<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <li @if (\Request::is('restaurant/dashboard*')) class="active" @endif>
                    <a href="{{url('/')}}"><i class="la la-dashboard"></i><span>Dashboard</span></a>
                </li>
                <li class="submenu">
                    <a href="#"><i class="la la-list"></i><span> Items</span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li><a href="{{route('item.create')}}" @if (\Request::is('item/create*')) class="active" @endif>Create New Item</a></li>
                        <li><a href="{{route('items.pending')}}" @if (\Request::is('pendingItems*')) class="active" @endif>Pending Items</a></li>
                        <li><a href="{{route('items.approved')}}" @if (\Request::is('approvedItems*')) class="active" @endif>Approved Items</a></li>
                    </ul>
                </li>

                <li @if (\Request::is('menu*')) class="active" @endif>
                    <a href="{{route('menu.index')}}"><i class="la la-edit"></i><span>Menu</span></a>
                </li>

                <li @if (\Request::is('orders*')) class="active" @endif>
                    <a href="{{route('orders.index')}}"><i class="la la-object-group"></i><span>Orders</span></a>
                </li>


        </div>
    </div>

</div>

