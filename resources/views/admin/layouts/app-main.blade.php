<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- start: HEAD -->

<head>
    @if(isset($title))
    <title>{{$title}} - {{env('APP_NAME','Untitled Project')}}</title>
    @else
    <title>{{$page->title ?? "Untitled"}} - {{env('APP_NAME','Untitled Project')}}</title>
    @endif

    <!-- start: META -->
    <meta charset="utf-8" />
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- end: META -->
    <!-- Favicon -->

    <link rel="shortcut icon" href="{{assets('icons/favicon.ico')}}" />
    @include('admin.includes.css')

    @yield('css-form')
    @yield('css-table')
</head>

<body>

    @include('admin.includes.header')
    <div class="main-container">
        <div class="loader-background">
            <div class="loader">
            </div>
        </div>
        @include('admin.includes.navigation')
    </div>

    <!-- start: PAGE -->
    <div class="main-content">
        <div class="container">
            @include('admin.includes.breadcrumbs')
            @include('admin.includes.status')

            @yield('content')


        </div>
    </div>

    @include('admin.includes.footer')

    @include('admin.includes.js')

    @yield('js-form')
    @yield('js-table')
    @yield('js-chat')
    @yield('js-help')
    @yield('js-mishap')
    @include('admin.includes.modals')
</body>

</html>
