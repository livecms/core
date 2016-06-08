@if (auth()->check())
    <link rel="stylesheet" href="/backend/dist/css/AdminLTE.dark.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <link rel="stylesheet" href="/backend/dist/css/skins/skin-dark.min.css">

  <style type="text/css">
    .user-label {
        width: 30px;
        height: 30px;
        text-align: center;
        background-color: rgba(85, 85, 85, 0.25);
        color: #fff;
        border-radius: 50%;
        margin-top: -5px;
        margin-bottom: -5px;
        padding: 5px;
        position: absolute;
    }

    .dropdown-toggle .user-label {
        right: 0;
        left: 0;
    }

    .user-block .user-label {
        width: 40px;
        height: 40px;
        margin-top: 0;
        padding: 10px;
    }

    .dark .user-label {
        background-color: #545353;
    }

  </style>

  <header class="admin-header main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <!-- Logo -->
          <a href="{{url(globalParams('slug_admin', config('livecms.slugs.admin')))}}" class="navbar-brand">
            {{ globalParams('site_name', 'Live CMS') }}
          </a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <!-- Sidebar Menu -->
          <ul class="nav navbar-nav">
          </ul>
          <!-- /.sidebar-menu -->
          
          <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
              <input type="text" class="form-control" id="navbar-search-input" placeholder="Pencarian...">
            </div>
          </form>
        </div>
        <!-- /.navbar-collapse -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
              <a class="dropdown-toggle">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs">{{auth()->user()->name}}</span>
              </a>
            </li>
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                @if (!auth()->user()->avatar)
                <div class="user-label">
                  <span>{{ auth()->user()->getInitial() }}</span>
                </div>
                @else
                <!-- The user image in the navbar-->
                <img src="{{auth()->user()->avatar}}" class="user-image" alt="User Image">
                @endif
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  @if (auth()->user()->avatar)
                  <img src="{{auth()->user()->avatar}}" class="img-circle" alt="User Image">
                  @else
                  <i class="text-gray ion ion-person fa-5x"></i>
                  @endif
                  <p>
                    {{ str_limit(auth()->user()->name, 20) }}
                    <small>Since {{ auth()->user()->created_at->diffForHumans() }}</small>
                  </p>
                </li>
                <!-- Menu Body -->
  <!--               <li class="user-body">
                  <div class="row">
                    <div class="col-xs-4 text-center">
                      <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Friends</a>
                    </div>
                  </div>
                </li> -->
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="{{ route((site()->subfolder ? site()->subfolder.'.' : '').globalParams('slug_userhome', config('livecms.slugs.userhome')).'.'.globalParams('slug_profile', config('livecms.slugs.profile')).'.index') }}" class="btn btn-default btn-flat">Profile</a>
                  </div>
                  <div class="pull-right">
                    <a href="{{ url('logout') }}" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            <li><a href="{{ url('/logout') }}"><i class="fa fa-lock"></i> <span class="hidden-sm">Logout</span></a></li>
          </ul>
        </div>
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
@endif