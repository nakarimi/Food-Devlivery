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
                            <li>
                                <a href="{{ url($menu->url) }}"> <span>{{ $menu->title }}</span></a>
                            </li>

                        @endforeach
                        <br>
                    @endif
                @endforeach

            </ul>

        </div>
    </div>

</div>

