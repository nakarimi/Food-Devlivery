<li class="nav-item dropdown">
    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i> <span class="badge badge-pill" id="notification_number">{{ auth()->user()->unreadNotifications->count() }}</span>
    </a>
    <div class="dropdown-menu notifications">
        <div class="topnav-dropdown-header">
            <span class="notification-title">Notifications</span>
                                <a href="javascript:void(0)" class="clear-noti read-notification-button" notification_id="all"> Clear All </a>
        </div>
        <div class="noti-content">
            <ul class="notification-list" id="notifications">
                @foreach(auth()->user()->unreadNotifications as $notification)
                    <li class="notification-message"><a href="#"><div class="media"><div class="media-body">
                                    <div class="d-flex">
                                        <p class="noti-details"><span class="noti-title"><b>{{$notification->data['userName']}}</b></span> <span class="noti-title">{{ $notification->data['message'] }}.</span></p>
                                        <button class="read-notification-button" notification_id="{{$notification->id}}" title="Mark as read"><i class="la la-check"></i></button>
                                    </div>
                                    <p class="noti-time"><span class="notification-time ml-1"> {{$notification->created_at->diffForHumans()}}</span></p>
                                </div></div></a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</li>
