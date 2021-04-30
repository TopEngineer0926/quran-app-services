<!-- start: HEADER -->
<div class="navbar navbar-inverse navbar-fixed-top">
    <!-- start: TOP NAVIGATION CONTAINER -->
    <div class="container">
        <div class="navbar-header">
            <!-- start: RESPONSIVE MENU TOGGLER -->
            <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                <span class="clip-list-2"></span>
            </button>
            <!-- end: RESPONSIVE MENU TOGGLER -->
            <!-- start: LOGO -->
            <a class="navbar-brand" href="{{route('admin.default')}}">
                <img src="{{logo()}}" title="{{env('APP_NAME','Unitited')}}" style="height:26px;"></img>  {{env('APP_NAME','Unitited')}}
            </a>
            <!-- end: LOGO -->
        </div>
        <div class="navbar-tools">
            <!-- start: TOP NAVIGATION MENU -->
            <ul class="nav navbar-right">
                <!-- start: TO-DO DROPDOWN -->

                <!-- end: TO-DO DROPDOWN-->
                <!-- start: NOTIFICATION DROPDOWN -->

                <!-- end: NOTIFICATION DROPDOWN -->
                <!-- start: MESSAGE DROPDOWN -->

                <!-- end: MESSAGE DROPDOWN -->
                <!-- start: USER DROPDOWN -->
                <li class="dropdown current-user">
                    <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
                        <img id="avatar" src="{{assets('avatars/no-avatar.png')}}" class="circle-img" alt="">
                        <span class="username">{{auth('admin')->user()->name}}
                        </span>
                        <i class="clip-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{route('profile.index')}}">
                                <i class="clip-user-2"></i>
                                &nbsp;Edit Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="clip-exit"></i>
                                &nbsp;Log Out
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- end: USER DROPDOWN -->
            </ul>
            <!-- end: TOP NAVIGATION MENU -->
        </div>
    </div>
    <!-- end: TOP NAVIGATION CONTAINER -->
</div>
<!-- end: HEADER -->
