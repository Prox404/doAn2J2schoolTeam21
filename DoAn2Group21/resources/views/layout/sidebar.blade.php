<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{route('dashboard')}}"><img src="{{asset('images/logo/logo.png')}}" alt="Logo" srcset=""></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{'dashboard' == request()->path() ? 'active' : ''}}">
                    <a href="{{route('dashboard')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item {{'classes' == request()->path() ? 'active' : ''}}">
                    <a href="{{route('class.index')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Classes</span>
                    </a>
                </li>
                <li class="sidebar-item {{'users' == request()->path() ? 'active' : ''}}">
                    <a href="{{route('user.index')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="sidebar-item {{'subjects' == request()->path() ? 'active' : ''}}">
                    <a href="{{route('subject.index')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Subjects</span>
                    </a>
                </li>
                <li class="sidebar-item {{'schedules' == request()->path() ? 'active' : ''}} ">
                    <a href="{{route('schedule.index')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Schedules</span>
                    </a>
                </li>
                <li class="sidebar-item {{'attendance' == request()->path() ? 'active' : ''}} ">
                    <a href="{{route('attendance.index')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Attendance</span>
                    </a>
                </li>

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
