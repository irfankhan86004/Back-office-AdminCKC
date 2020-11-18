<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="fr"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>@yield('title') - Back-office {{ config('app.name') }}</title>

        <meta name="description" content="Back-office {{ config('app.name') }}">
        <meta name="robots" content="noindex, nofollow">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

        <!-- Stylesheets -->
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="{{ asset('admin1/css/bootstrap.min.css') }}">

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="{{ asset('admin1/css/plugins.css') }}">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="{{ asset('admin1/css/main.css') }}">

        <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->
		<link rel="stylesheet" href="{{ asset('admin1/css/themes/fire.css') }}">

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        <link rel="stylesheet" href="{{ asset('admin1/css/themes.css') }}">

        <!-- Datatable 1.10.18 -->
        <link rel="stylesheet" href="{{ asset('admin1/plugins/datatables/datatables.css') }}">

        <!-- Datepicker 1.8.0 -->
        <link rel="stylesheet" href="{{ asset('admin1/plugins/bootstrap-datepicker/bootstrap-datepicker.css') }}">

        <!-- END Stylesheets -->

        <!-- Modernizr (browser feature detection library) -->
        <script src="{{ asset('admin1/js/vendor/modernizr.min.js') }}"></script>

		@stack('styles')
    </head>
    <body>
        <div id="page-wrapper" class="page-loading">
            <!-- Preloader -->
            <div class="preloader themed-background">
                <h1 class="push-top-bottom text-light text-center">{{ config('app.name') }}</h1>
                <div class="inner">
                    <h3 class="text-light visible-lt-ie10"><strong>Loading...</strong></h3>
                    <div class="preloader-spinner hidden-lt-ie10"></div>
                </div>
            </div>
            <!-- END Preloader -->

            <div id="page-container" class="sidebar-partial sidebar-visible-lg sidebar-no-animations">

                <!-- Main Sidebar -->
                <div id="sidebar">
                    <!-- Wrapper for scrolling functionality -->
                    <div id="sidebar-scroll">
                        <!-- Sidebar Content -->
                        <div class="sidebar-content">
                            <!-- Brand -->
                            <a href="{{ route('admin_dashboard') }}" class="sidebar-brand text-center">
                                <span class="sidebar-nav-mini-hide">Back-office {{ config('app.name') }}</span>
                            </a>
                            <!-- END Brand -->

                            <!-- User Info -->
                            <div class="sidebar-section sidebar-user clearfix sidebar-nav-mini-hide">
                                <div class="sidebar-user-avatar">
                                    <a href="{{ route('admins.edit', [Auth::guard('admin')->user()->id]) }}">
                                        <img src="{{ Auth::guard('admin')->user()->gravatar(Auth::guard('admin')->user()->email, 64) }}" alt="avatar">
                                    </a>
                                </div>
                                <div class="sidebar-user-name">{{ Auth::guard('admin')->user()->displayName() }}</div>
                                <div class="sidebar-user-links">
                                    <a href="{{ route('admins.edit', [Auth::guard('admin')->user()->id]) }}" data-toggle="tooltip" data-placement="bottom" title="Mon profil"><i class="gi gi-user"></i></a>
                                    <!-- Opens the user settings modal that can be found at the bottom of each page (page_footer.html in PHP version) -->
                                    <a href="{{ route('admin_logout') }}" data-toggle="tooltip" data-placement="bottom" title="Se déconnecter"><i class="gi gi-exit"></i></a>
                                </div>
                            </div>
                            <!-- END User Info -->

							@include('admin.partials.menu')

                        </div>
                        <!-- END Sidebar Content -->
                    </div>
                    <!-- END Wrapper for scrolling functionality -->
                </div>
                <!-- END Main Sidebar -->

                <!-- Main Container -->
                <div id="main-container">
                    <header class="navbar navbar-default">
                        <!-- Left Header Navigation -->
                        <ul class="nav navbar-nav-custom">
                            <!-- Main Sidebar Toggle Button -->
                            <li>
                                <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                                    <i class="fa fa-bars fa-fw"></i>
                                </a>
                            </li>
                            <!-- END Main Sidebar Toggle Button -->
                        </ul>
                        <!-- END Left Header Navigation -->

                        <!-- Right Header Navigation -->
                        <ul class="nav navbar-nav-custom pull-right">
                            <!-- User Dropdown -->
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ Auth::guard('admin')->user()->gravatar(Auth::guard('admin')->user()->email, 64) }}" alt="avatar"> <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                    <li class="dropdown-header text-center">
	                                    <a href="{{ route('admins.edit', [Auth::guard('admin')->user()->id]) }}">
		                                	<strong>{{ Auth::guard('admin')->user()->displayName() }}</strong><br />{{ Auth::guard('admin')->user()->email }}
										</a>
									</li>
                                    <li>
                                    	<a href="http://www.ovh.com/manager/web/login/" target="_blank"><i class="fa fa-fw fa-database"></i> {{ database_size() }}</a>
                                        <a href="{{ route('admin_logout') }}"><i class="fa fa-ban fa-fw pull-right"></i> Se déconnecter</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END User Dropdown -->
                        </ul>
                        <!-- END Right Header Navigation -->
                    </header>
                    <!-- END Header -->

                    <!-- Page content -->
                    <div id="page-content">
                        @yield('content')
                    </div>
                    <!-- END Page Content -->

                </div>
                <!-- END Main Container -->
            </div>
            <!-- END Page Container -->
        </div>
        <!-- END Page Wrapper -->

        <!-- Scroll to top link, initialized in js/app.js - scrollToTop() -->
        <a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>

        <!-- jQuery 3.3.1, Bootstrap 3.3.7, jQuery plugins and Custom JS code -->
        <script src="{{ asset('admin1/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('admin1/js/vendor/bootstrap.min.js') }}"></script>
        <script src="{{ asset('admin1/js/plugins.js') }}"></script>
        <script src="{{ asset('admin1/js/app.js') }}"></script>
		<!-- Noty -->
		<script src="{{ asset('admin1/js/vendor/jquery.noty.packaged.min.js') }}"></script>
		<!-- Bootbox -->
		<script src="{{ asset('admin1/js/vendor/bootbox.min.js') }}"></script>
        <!-- Datatables 1.10.18 -->
        <script src="{{ asset('admin1/plugins/datatables/datatables.js') }}"></script>
        <!-- Datepicker 1.8.0 -->
        <script src="{{ asset('admin1/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

		<script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').prop('content')
            }
        });
        $('.datepicker').datepicker({
            language: 'fr',
            format: 'dd/mm/yyyy',
            weekStart: 1,
            todayHighlight: true
        });
        </script>

        <!-- Default Datatable options-->
        <script type="text/javascript">
            $.extend( $.fn.dataTable.defaults, {
                language: {
                    "lengthMenu": '<select class="form-control input-sm">'+
                        '<option value="20">25</option>'+
                        '<option value="50">50</option>'+
                        '<option value="100">100</option>'+
                        '</select>',
                    "zeroRecords": "Aucun résultat",
                    "info": "Page _PAGE_ sur _PAGES_",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "search": "<div class=\"input-group\">_INPUT_<span class=\"input-group-addon\"><i class=\"fa fa-search\"></i></span></div>",
                    "searchPlaceholder": "Rechercher",
                    "processing": "Chargement...",
                    "emptyTable": "Aucune donnée disponible",
                    "paginate": {
                      "previous": "<i class='fa fa-chevron-left'></i>",
                      "next": "<i class='fa fa-chevron-right'></i>"
                    }
                },
            });
        </script>

        <!-- FullCalendar -->
        <script src="{{ asset('admin1/js/vendor/fullcalendar.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('admin1/js/vendor/locale-all.js') }}"></script>

		@include('admin.notification')

		@stack('scripts')



    </body>
</html>
