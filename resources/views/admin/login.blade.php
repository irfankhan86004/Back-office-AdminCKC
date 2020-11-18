<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10" lang="fr"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="fr"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>Login back-office {{ config('app.name') }}</title>

        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="{{ asset('admin1/css/bootstrap.min.css') }}">

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="{{ asset('admin1/css/plugins.css') }}">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="{{ asset('admin1/css/main.css') }}">

        <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->
		<link rel="stylesheet" href="{{ asset('admin1/css/themes/fire.css') }}">

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        <link rel="stylesheet" href="{{ asset('admin1/css/themes.css') }}">
        <!-- END Stylesheets -->

        <!-- Modernizr (browser feature detection library) -->
        <script src="{{ asset('admin1/js/vendor/modernizr.min.js') }}"></script>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
	</head>
    <body>
        <!-- Login Full Background -->
        <!-- For best results use an image with a resolution of 1280x1280 pixels (prefer a blurred image for smaller file size) -->
        <img src="{{ asset('admin1/img/placeholders/backgrounds/new-admin.jpg') }}" alt="Login Full Background" class="full-bg animation-pulseSlow">
        <!-- END Login Full Background -->

        <!-- Login Container -->
        <div id="login-container" class="animation-fadeIn">
            <!-- Login Title -->
            <div class="login-title text-center">
	           <!-- Logo -->
               <img src="{{ asset('admin1/img/logo.png') }}">
            </div>
            <!-- END Login Title -->

            <!-- Login Block -->
            <div class="block push-bit">
                <!-- Login Form -->
                {{ Form::open(['route' => ['admin_login'], 'method' => 'POST','class' => 'form-horizontal form-bordered form-control-borderless', 'id' => 'form-login']) }}
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                {{ Form::text('email', \Request::old('email'),['class' => 'form-control input-lg', 'id' => 'login-email', 'placeholder' => 'Email']) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
								{{ Form::password('password',['class' => 'form-control input-lg', 'id' => 'login-password', 'placeholder' => 'Mot de passe']) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-actions text-center">
                        {{--<div class="col-xs-6">--}}
                            {{--<label class="switch switch-primary" data-toggle="tooltip" title="Se souvenir de moi ?">--}}
                                {{--<input type="checkbox" id="login-remember-me" name="login-remember-me" checked>--}}
                                {{--<span></span>--}}
                            {{--</label>--}}
                        {{--</div>--}}
                        {{--<div class="col-xs-6 text-right">--}}
                            {{--<button type="submit" class="btn btn-sm btn-primary">Se connecter <i class="fa fa-angle-right"></i></button>--}}
                        {{--</div>--}}
						<button type="submit" class="btn btn-sm btn-primary">Connexion</button>
                    </div>
                {{ Form::close() }}
                <!-- END Login Form -->
            </div>
            <!-- END Login Block -->
        </div>
        <!-- END Login Container -->

        <!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->
        <script src="{{ asset('admin1/js/vendor/jquery.min.js') }}"></script>
        <script src="{{ asset('admin1/js/vendor/bootstrap.min.js') }}"></script>
        <script src="{{ asset('admin1/js/plugins.js') }}"></script>
        <script src="{{ asset('admin1/js/app.js') }}"></script>

        <!-- Load and execute javascript code used only in this page -->
        <script src="{{ asset('admin1/js/pages/login.js') }}"></script>
        <script>$(function(){ Login.init(); });</script>

        <script src="{{ asset('admin1/js/vendor/jquery.noty.packaged.min.js') }}"></script>
		@include('admin.notification')
    </body>
</html>
