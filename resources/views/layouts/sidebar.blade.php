<div class="sidebar" id="sidebar">
  <div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
        <ul>
          <li class="menu-title">
            <span>Main</span>
          </li>

          @if(Auth::user()->role->name == "admin")
            <li @if (\Request::is('admin/dashboard*')) class="active" @endif>
              <a href="{{ url('/admin/dashboard') }}"><i class="la la-dashboard"></i> <span>Dashboard </span></a>
            </li>
          @elseif(Auth::user()->role->name == "support")
            <li @if (\Request::is('support/dashboard*')) class="active" @endif>
              <a href="{{ url('/support/dashboard') }}"><i class="la la-dashboard"></i> <span>Dashboard </span></a>
            </li>
          @endif
          @php $orderChanges = (@$sidebarData['waitingOrders'] + @$sidebarData['activeOrders']) ?: 0; @endphp
          <li class="submenu orders">
            <a href="#"><i class="la la-legal"></i> <span class="menu-title">Orders @if ($orderChanges != 0) <span
                    class="badge badge-danger custom-badge-en-parent" id="tatalOrdersCount">{{ $orderChanges }}</span> @endif</span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
              {{-- <li><a  @if (\Request::is('orders/create*')) class="active" @endif href="{{ route('orders.create') }}">Add Order</a></li> --}}
              <li><a @if (\Request::is('waitingOrders')) class="active" @endif href="{{ url('waitingOrders') }}">Waiting Orders</a>
                @if ($sidebarData['waitingOrders'] != 0) <span
                    class="badge badge-danger custom-badge-en" id="waitingOrdersCount">{{ $sidebarData['waitingOrders'] }}</span>@endif
              </li>
              <li><a @if (\Request::is('activeOrders')) class="active" @endif href="{{ url('activeOrders') }}">Active Orders</a>
                @if ($sidebarData['activeOrders'] != 0)<span
                    class="badge badge-danger custom-badge-en" id="activeOrdersCount">{{ $sidebarData['activeOrders'] }}</span>@endif
              </li>
              <li><a @if (\Request::is('order-history')) class="active" @endif href="{{ route('order.history') }}">Orders History</a></li>
            </ul>
          </li>
          @php $itemChanges = (@$sidebarData['pendingItems'] + @$sidebarData['rejectedItems']) ?: 0; @endphp
          <li class="submenu">
            <a href="#"><i class="la la-list"></i><span class="menu-title">Item @if ($itemChanges != 0) <span
                    class="badge badge-danger custom-badge-en-parent">{{ $itemChanges }}</span> @endif</span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
              <li><a href="{{ route('items.pending') }}" @if (\Request::is('pendingItems*')) class="active" @endif>Pending Items</a>
                @if ($sidebarData['pendingItems'] != 0)<span
                    class="badge badge-danger custom-badge-en">{{ $sidebarData['pendingItems'] }}</span>@endif
              </li>
              <li><a href="{{ route('items.approved') }}" @if (\Request::is('approvedItems*')) class="active" @endif>Approved Items</a></li>
              <li><a href="{{route('items.rejected')}}" @if (\Request::is('rejectedItems*')) class="active" @endif>Rejected Items @if($sidebarData['rejectedItems'] != 0)<span class="badge badge-danger custom-badge">{{$sidebarData['rejectedItems']}}</span>@endif </a></li>


              <li><a @if (\Request::is('category')) class="active" @endif href="{{ route('category.index') }}">Category</a></li>
              {{-- <li><a  @if (\Request::is('menu')) class="active" @endif href="{{ route('menu.index') }}">Menus</a></li> --}}
            </ul>
          </li>
          @php $branchChanges = (@$sidebarData['pendingBranches'] + @$sidebarData['rejectedBranches']) ?: 0; @endphp
          <li class="submenu">
            <a href="#"><i class="la la-recycle"></i> <span class="menu-title">Branch @if ($branchChanges != 0) <span
                    class="badge badge-danger custom-badge-en-parent">{{ $branchChanges }}</span> @endif</span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
              <li><a @if (\Request::is('approvedBranches')) class="active" @endif href="{{ route('branches.approved') }}">Approved Branches</a></li>
              <li><a @if (\Request::is('pendingBranches')) class="active" @endif href="{{ route('branches.pending') }}">Pending Branches</a>
                @if ($sidebarData['pendingBranches'] != 0)<span
                    class="badge badge-danger custom-badge-en">{{ $sidebarData['pendingBranches'] }}</span>
                @endif
              </li>
              <li><a @if (\Request::is('rejectedBranches')) class="active" @endif href="{{ route('branches.rejected') }}">Rejected Branches</a>
                @if ($sidebarData['rejectedBranches'] != 0)<span
                    class="badge badge-danger custom-badge-en">{{ $sidebarData['rejectedBranches'] }}</span>
                @endif
              </li>

              <!--  -->
            </ul>
          </li>

          <li>
            <a @if (\Request::is('driversTracking')) class="active" @endif
              href="{{ url('driversTracking') }}"><i class="la la-truck"></i> <span class="menu-title">Drivers Tracking</span>
            </a>
          </li>

          @if(Auth::user()->role->name == "admin")
            <li class="submenu">
              <a href="#"><i class="la la-money"></i> <span class="menu-title">Finance</span> <span
                  class="menu-arrow"></span></a>
              <ul style="display: none;">
                <li><a @if (\Request::is('payment')) class="active" @endif href="{{ route('payment.index') }}">Payments</a></li>
                <li><a @if (\Request::is('commission')) class="active" @endif href="{{ route('commission.index') }}">Commissions</a></li>
              </ul>
            </li>

            <li class="menu-title">
              <span>System</span>
            </li>
            <li class="submenu">
              <a href="#"><i class="la la-lock"></i> <span>Security</span> <span class="menu-arrow"></span></a>
              <ul style="display: none;">

                <li><a @if (\Request::is('admin/users')) class="active" @endif href="{{ route('users.index') }}">Users</a></li>
                <li><a @if (\Request::is('blockedCustomer')) class="active" @endif href="{{ route('blockedCustomer.index') }}">Blocked Customers</a></li>
                <li>
                  <a @if (\Request::is('driver')) class="active" @endif
                    href="{{ route('driver.index') }}"><i class="la la-truck"></i> <span class="menu-title">Drivers</span>
                  </a>
                </li>
                <li>
                  <a @if (\Request::is('admin/activitylogs*')) class="active" @endif href="{{ url('/admin/activitylogs') }}">Activity Logs</a>
                </li>
                <li>
                  <a @if (\Request::is('backups*')) class="active" @endif href="{{ url('/backups') }}">Backups</a>
                </li>
              </ul>
            </li>
            <li class="submenu">
              <a href="#"><i class="la la-code"></i> <span> Developer Options</span> <span class="menu-arrow"></span></a>
              <ul style="display: none;">

                <li>
                  <a @if (\Request::is('admin/settings*')) class="active" @endif href="{{ url('/admin/settings') }}"> <span>Settings</span></a>
                </li>

                <li>
                  <a @if (\Request::is('admin/generator*')) class="active" @endif href="{{ url('/admin/generator') }}">Generator</a>
                </li>
              </ul>
            </li>
          @endif
        </ul>
    </div>
  </div>

</div>
