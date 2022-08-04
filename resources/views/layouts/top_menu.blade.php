<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">
    
      <div class="navbar-header">
        <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
          data-toggle="menubar">
          <span class="sr-only">Toggle navigation</span>
          <span class="hamburger-bar"></span>
        </button>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
          data-toggle="collapse">
          <i class="icon md-more" aria-hidden="true"></i>
        </button>
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
          <img class="navbar-brand-logo" src="{{asset(PUBLIC_FOLDER.'theme')}}/mmenu/assets/images/logo.png" title="{{WEBSITE_NAME}}">
          <span class="navbar-brand-text hidden-xs-down"> {{WEBSITE_NAME}}</span>
        </div>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search"
          data-toggle="collapse">
          <span class="sr-only">Toggle Search</span>
          <i class="icon md-search" aria-hidden="true"></i>
        </button>
      </div>
    
      <div class="navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
    
          <!-- Navbar Toolbar Right -->
          <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
   
            <li class="nav-item dropdown">
				<a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
					data-animation="scale-up" role="button">
					<span class="avatar avatar-online">
					  <img src="{{asset(PUBLIC_FOLDER.'theme')}}/global/portraits/5.jpg" alt="...">
					  <i></i>
					</span>
				</a>
				<div class="dropdown-menu" role="menu">
					<a class="dropdown-item" href="{{route('useredit',Auth::user()->id)}}" role="menuitem"><i class="icon md-account" aria-hidden="true"></i> Profile ({{ Auth::user()->full_name }})</a>

					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item" href="{{ route('logout') }}"  role="menuitem" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
						<i class="icon md-power" aria-hidden="true"></i>
							{{ __('Logout') }}
					</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					</form>
					
				</div>
            </li>
          </ul>
        </div>
      </div>
</nav>