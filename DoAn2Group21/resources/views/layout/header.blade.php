<header class="mb-3">
    <nav class="navbar navbar-expand navbar-light ">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="bi bi-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown me-1">
                        <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-envelope bi-sub fs-4 text-gray-600"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li>
                                <h6 class="dropdown-header">Mail</h6>
                            </li>
                            <li><a class="dropdown-item" href="#">No new mail</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-bell bi-sub fs-4 text-gray-600"></i>
                            @php
                                $notifications = auth()->user()->notification;
                            @endphp
                            @if (count($notifications) > 0)
                                <span
                                    class="position-absolute top-10 start-90 translate-middle badge rounded-pill bg-danger p-1"
                                    style="height:20px; width:20px;">{{ count($notifications) }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li>
                                <h6 class="dropdown-header">Notifications</h6>
                            </li>

                            @if (count($notifications) > 0)
                                @foreach ($notifications as $notification)
                                    <li><a class="dropdown-item" href="#">{{ $notification->content }}</a></li>
                                @endforeach
                                <div class="col-12 d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-light me-2" id="clearAll">Clear All</button>
                                </div>
                            @else
                                <li><a class="dropdown-item" href="#">No new notifications</a></li>
                            @endif
                        </ul>
                    </li>
                </ul>
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false" class="">
                        <div class="user-menu d-flex">
                            <div class="user-name text-end me-3">
                                <h6 class="mb-0 text-gray-600">
                                    @auth
                                        {{ auth()->user()->name }}
                                    @endauth
                                </h6>
                                <p class="mb-0 text-sm text-gray-600">
                                    @auth
                                        <?php
                                        switch (auth()->user()->level) {
                                            case 1:
                                                echo 'Student';
                                                break;
                                            case 2:
                                                echo 'Teacher';
                                                break;
                                            case 3:
                                                echo 'Admin';
                                                break;
                                            case 4:
                                                echo 'Super Admin';
                                                break;
                                            default:
                                                echo 'Guest';
                                                break;
                                        }
                                        ?>
                                    @endauth
                                </p>
                            </div>
                            <div class="user-img d-flex align-items-center">
                                <div class="avatar avatar-md">
                                    <img src="{{ asset('images/faces/1.jpg') }}">
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <li>
                            <h6 class="dropdown-header">Hello, {{ auth()->user()->name }}</h6>
                        </li>
                        <li><a class="dropdown-item" href="{{route('user.show')}}"><i class="icon-mid bi bi-person me-2"></i> My
                                Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="icon-mid bi bi-gear me-2"></i>
                                Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('signout') }}"><i
                                    class="icon-mid bi bi-box-arrow-left me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
