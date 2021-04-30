<!DOCTYPE html>
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- start: HEAD -->

<head>
    <title>Login</title>
    <!-- start: META -->
    <meta charset="utf-8" />
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link rel="shortcut icon" href="{{assets('icons/favicon.ico')}}" />
    <!-- end: META -->
    <!-- start: MAIN CSS -->
    @include('admin.includes.css')
    <!--[if IE 7]>
		<link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome-ie7.min.css">
		<![endif]-->
    <!-- end: MAIN CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
</head>

<body class="login main">
    <div class="main-login col-sm-4 col-sm-offset-4">
        <div class="logo">
        <img src="{{logo()}}" title="{{env('APP_NAME','Unitited Project')}}"></img> {{env('APP_NAME','Unitited Project')}}
        </div>
        @yield('content')
        <!-- start: COPYRIGHT -->
			<div class="copyright">
				2019 &copy; {{env('APP_NAME','Unitited Project')}}
			</div>
			<!-- end: COPYRIGHT -->
    </div>
    @include('admin.includes.js')
    <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script src="{{assets('plugins/jquery-validation/dist/jquery.validate.min.js')}}"></script>
		<script src="{{assets('js/login.js')}}"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script>
			jQuery(document).ready(function() {
				Main.init();
				Login.init();
			});
		</script>
</body>

</html>
