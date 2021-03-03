<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                @foreach($laravelAdminMenus->menus as $section)
                    @if($section->items)
                        <li class="menu-title">
                            <span>{{ $section->section }}</span>
                        </li>
                        @foreach($section->items as $menu)
                            @if ($menu->title == "Item")
                                <li class="submenu">
                                    <a href="#"><span> {{ $menu->title }}</span> <span class="menu-arrow"></span></a>
                                    <ul>
                                        <li><a href="{{route('items.pending')}}">Pending Items</a></li>
                                        <li><a href="{{route('items.approved')}}">Approved Items</a></li>
                                    </ul>
                                </li>

                                @else
                                <li>
                                    <a href="{{ url($menu->url) }}"> <span>{{ $menu->title }}</span></a>
                                </li>
                            @endif

                        @endforeach
                        <br>
                    @endif
                @endforeach


            </ul>

        </div>
    </div>

</div>

