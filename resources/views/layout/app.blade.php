<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="LMS">
	<meta name="author" content="LMS">
	<meta name="keywords" content="LMS">

	<link rel="preconnect" href="#">
	<link rel="shortcut icon" href="{{asset('public/img/icons/icon-48x48.png')}}" />

	<link rel="canonical" href="#" />

	<title>LMS</title>

	<link href="{{asset('public/css/app.css')}}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	<style>
		.badge {
			padding: 5px 0px;
			border-radius: 50px;
			font-size: 14px;
			font-weight: 600;
			/* box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.19); */
			border: 1px solid lightgray;
		}

		.badge2 {
			border-radius: 50px;
			border: 1px solid lightgray;
			padding: 0px 2px 4px 2px;
		}
	</style>
	@yield('styles')
</head>

<body>
	<div class="wrapper">
		@include('components.sidebar')
		<div class="main">
			@include('components.navbar')
			@yield('content')
			@include('components.footer')
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
	<script src="{{asset('public/js/app.js')}}"></script>

	@yield('scripts')
</body>