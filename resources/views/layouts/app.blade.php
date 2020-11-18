<!doctype html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
		@if(app()->environment() !== 'production')
			<meta name="robots" content="noindex, nofollow">
		@endif
		
		<!-- SEO -->
		<title>@yield('title')</title>
		<meta name="description" content="@yield('description')">
	</head>
	<body>
		@yield('content')
		@stack('scripts')
	</body>
</html>


