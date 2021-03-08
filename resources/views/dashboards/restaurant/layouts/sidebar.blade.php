<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>
                <li>
                    <a href="{{url('/')}}"><span>Dashboard</span></a>
                </li>
                <li class="submenu">
                    <a href="#" class="subdrop"><span> Items</span> <span class="menu-arrow"></span></a>
                    <ul style="display: block;">
                        <li><a href="{{route('item.create')}}">Create New Item</a></li>
                        <li><a href="{{route('items.pending')}}">Pending Items</a></li>
                        <li><a href="{{route('items.approved')}}">Approved Items</a></li>
                        <li><a href="{{route('item.index')}}">All Items</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{route('menu.index')}}"><span>Menu</span></a>
                </li>

                <li>
                    <a href="{{route('orders.index')}}"><span>Orders</span></a>
                </li>


            </ul>

        </div>
    </div>

</div>

