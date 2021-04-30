<div class="navbar-content">
    <!-- start: SIDEBAR -->
    <div class="main-navigation navbar-collapse collapse">
        <!-- start: MAIN MENU TOGGLER BUTTON -->
        <div class="navigation-toggler">
            <i class="clip-chevron-left"></i>
            <i class="clip-chevron-right"></i>
        </div>
        <!-- end: MAIN MENU TOGGLER BUTTON -->
        <!-- start: MAIN NAVIGATION MENU -->
        <ul class="main-navigation-menu">
            @foreach($navigation as $nav)
            <li class="@if(Request::url() == $nav['link']) active @endif">
                <a href="{{$nav['link']}}">
                    <i class="{{$nav['icon']}}"></i>
                    <span class="title"> {{$nav['name']}} </span>
                    @if(isset($nav['sub']))
                    <i class="icon-arrow"></i>
                    <span class="selected"></span>
                    <ul class="sub-menu">
                </a>
                @foreach($nav['sub'] as $sub)
            <li class="@if(Request::url() == $sub['link']) active @endif">
                <a href="{{$sub['link']}}">
                    <span class="title"> {{$sub['name']}} </span>
                </a>
            </li>
            @endforeach
        </ul>
        @else
        </a>
        @endif
        </li>
        @endforeach
        </ul>
    </div>
</div>
