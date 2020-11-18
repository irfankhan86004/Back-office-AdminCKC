<!-- Sidebar Navigation -->
<ul class="sidebar-nav">
    <li>
        <a{!! Request::is('*dashboard*') ? ' class="active"' : null !!} href="{{ route('admin_dashboard') }}"><i class="fas fa-tachometer-alt sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Dashboard</span></a>
    </li>

    @if(Auth::guard('admin')->user()->hasPermission(1))
    <li>
        <a{!! Request::is('*admins*') ? ' class="active"' : null !!} href="{{ route('admins.index') }}"><i class="gi gi-lock sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Administrateurs</span></a>
    </li>
    @endif

	@if(Auth::guard('admin')->user()->hasPermission(7))
		<li>
			<a{!! Request::is('*users*') ? ' class="active"' : null !!} href="{{ route('users.index') }}"><i class="fa fa-user sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Utilisateurs</span></a>
		</li>
	@endif

    @if(Auth::guard('admin')->user()->hasPermission(14) || Auth::guard('admin')->user()->hasPermission(12))
        <li>
            <a href="#" class="sidebar-nav-menu{!! Request::is('*contact-requests*') || Request::is('*newsletters*') ? ' open' : null !!}"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-envelope sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Communication</span></a>
            <ul{!! Request::is('*contact-requests*') || Request::is('*newsletters*') ? ' style="display:block;"' : null !!}>
                @if(Auth::guard('admin')->user()->hasPermission(14))
                    <li>
                        <a{!! Request::is('*contact-requests*') ? ' class="active"' : null !!} href="{{ route('contact-requests.index') }}">Demandes de contact</a>
                    </li>
                @endif
                @if(Auth::guard('admin')->user()->hasPermission(12))
                    <li>
                        <a{!! Request::is('*newsletters*') ? ' class="active"' : null !!} href="{{ route('newsletters.index') }}"><span class="sidebar-nav-mini-hide">Newsletters</span></a>
                    </li>
                @endif
            </ul>
        </li>
    @endif

	@if(Auth::guard('admin')->user()->hasPermission(3) || Auth::guard('admin')->user()->hasPermission(4) || Auth::guard('admin')->user()->hasPermission(10))
		<li>
			<a href="#" class="sidebar-nav-menu{!! Request::is('*menu*') || Request::is('*pages*') || Request::is('*carousel*') ? ' open' : null !!}"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-font sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Contenus du site</span></a>
			<ul{!! Request::is('*menu*') || Request::is('*pages*') || Request::is('*carousel*') ? ' style="display:block;"' : null !!}>
				@if(Auth::guard('admin')->user()->hasPermission(3))
					<li>
						<a{!! Request::is('*menu*') ? ' class="active"' : null !!} href="{{ route('menu.index') }}">Menu</a>
					</li>
				@endif
				@if(Auth::guard('admin')->user()->hasPermission(4))
					<li>
						<a{!! Request::is('*pages*') ? ' class="active"' : null !!} href="{{ route('pages.index') }}">Pages</a>
					</li>
				@endif
				@if(Auth::guard('admin')->user()->hasPermission(10))
					<li>
						<a{!! Request::is('*carousel*') ? ' class="active"' : null !!} href="{{ route('carousel.index') }}">Carousels</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

	@if(Auth::guard('admin')->user()->hasPermission(2))
		<li>
			<a href="#" class="sidebar-nav-menu{!! Request::is('*categories-blog*') || Request::is('*articles-blog*') ? ' open' : null !!}"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-rss sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Blog</span></a>
			<ul{!! Request::is('*categories-blog*') || Request::is('*articles-blog*') ? ' style="display:block;"' : null !!}>
				<li>
					<a{!! Request::is('*categories-blog*') ? ' class="active"' : null !!} href="{{ route('categories-blog.index') }}">Categories</a>
				</li>
				<li>
					<a{!! Request::is('*articles-blog*') ? ' class="active"' : null !!} href="{{ route('articles-blog.index') }}">Articles</a>
				</li>
			</ul>
		</li>
	@endif

	@if(Auth::guard('admin')->user()->hasPermission(5) || Auth::guard('admin')->user()->hasPermission(6))
		<li>
			<a href="#" class="sidebar-nav-menu{!! Request::is('*medias*') ? ' open' : null !!}"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-folder-open-o sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Medias</span></a>
			<ul{!! Request::is('*medias*') ? ' style="display:block;"' : null !!}>
				@if(Auth::guard('admin')->user()->hasPermission(5))
					<li>
						<a{!! Request::is('*medias*') && !Request::is('*categories-medias*') ? ' class="active"' : null !!} href="{{ route('medias.index') }}">Listing</a>
					</li>
				@endif
				@if(Auth::guard('admin')->user()->hasPermission(6))
					<li>
						<a{!! Request::is('*categories-medias*') ? ' class="active"' : null !!} href="{{ route('categories-medias.index') }}">Categories</a>
					</li>
				@endif
			</ul>
		</li>
	@endif

	@if(Auth::guard('admin')->user()->hasPermission(13))
		<li>
			<a{!! Request::is('*tags*') ? ' class="active"' : null !!} href="{{ route('tags.index') }}"><i class="fas fa-tags sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Tags</span></a>
		</li>
	@endif

    @if(Auth::guard('admin')->user()->hasPermission(16))
        <li>
            <a{!! Request::is('*promo-codes*') ? ' class="active"' : null !!} href="{{ route('promo-codes.index') }}"><i class="fas fa-gift sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Codes promo</span></a>
        </li>
    @endif

	@if(Auth::guard('admin')->user()->hasPermission(15))
		<li>
			<a{!! Request::is('*redirects*') ? ' class="active"' : null !!} href="{{ route('redirects.index') }}"><i class="fa fa-map-signs sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Redirections</span></a>
		</li>
	@endif

	@if(Auth::guard('admin')->user()->hasPermission(11))
		<li>
			<a{!! Request::is('*settings*') ? ' class="active"' : null !!} href="{{ route('settings.index') }}"><i class="fas fa-wrench sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Configuration</span></a>
		</li>
	@endif

    @if(Auth::guard('admin')->user()->hasPermission(8) || Auth::guard('admin')->user()->hasPermission(9))
        <li>
            <a href="#" class="sidebar-nav-menu{!! Request::is('*emails*') || Request::is('*sms*') ? ' open' : null !!}"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-search sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Logs</span></a>
            <ul{!! Request::is('*emails*') || Request::is('*sms*') ? ' style="display:block;"' : null !!}>
                @if(Auth::guard('admin')->user()->hasPermission(8))
                    <li>
                        <a{!! Request::is('*emails*') ? ' class="active"' : null !!} href="{{ route('emails.index') }}">Emails</a>
                    </li>
                @endif
                @if(Auth::guard('admin')->user()->hasPermission(9))
                    <li>
                        <a{!! Request::is('*sms*') ? ' class="active"' : null !!} href="{{ route('sms.index') }}">SMS</a>
                    </li>
                @endif
            </ul>
        </li>
    @endif

	<li>
		<a href="{{ route('home') }}" target="_blank"><i class="fas fa-at sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Accéder au site</span></a>
	</li>
</ul>
<!-- END Sidebar Navigation -->
