<li class="nav-item dropdown">
    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i> <span class="badge badge-pill">{{ auth()->user()->unreadNotifications->count() }}</span>
    </a>
    <div class="dropdown-menu notifications">
        <div class="topnav-dropdown-header">
            <span class="notification-title">Notifications</span>
            <a href="javascript:void(0)" class="clear-noti read-notification-button" notification_id="all"> حذف همه</a>
        </div>
        <div class="noti-content">
            <ul class="notification-list">
                @foreach(auth()->user()->unreadNotifications as $notification)
                    <li class="notification-message"><a href="#"><div class="media"><div class="media-body">
                                    <div class="d-flex">
                                        <p class="noti-details"><span class="noti-title">{{ $notification->data['message'] }}.</span></p>
                                        <button class="read-notification-button" notification_id="{{$notification->id}}" title="Mark as read" style="right: 90%; border: none; position: absolute"><i class="la la-check"></i></button>
                                    </div>
                                    <p class="noti-time"><span class="notification-time"> {{$notification->created_at->diffForHumans()}}</span></p>
                                </div></div></a>

                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</li>
