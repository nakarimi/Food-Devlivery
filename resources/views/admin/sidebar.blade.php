<div class="col-md-3">
    @foreach($laravelAdminMenus->menus as $section)
        @if($section->items)
            <div class="card">
                <div class="card-header">
                    {{ $section->section }}
                </div>

                <div class="card-body">
                    <ul class="nav flex-column" role="tablist">
                        @foreach($section->items as $menu)
                            @if ($loop->last)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ url('/dashboard') }}">
                                       Dashboard
                                    </a>
                                </li>
                                @else
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" href="{{ url($menu->url) }}">
                                        {{ $menu->title }}
                                    </a>
                                </li>
                            @endif

                        @endforeach
                    </ul>
                </div>
            </div>
            <br/>
        @endif
    @endforeach
</div>
