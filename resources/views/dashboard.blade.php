
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet"> --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet"> --}}

  </head>
  <body class="hold-transition sidebar-mini">
        <div id="app" class="wrapper">

            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">

                <!-- Notifications Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fas fa-bell">
                        </i>
                        {{-- <span class="badge badge-warning navbar-badge">15</span> --}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="{{ Route('home') }}" class="brand-link">
                    <img src="/images/logo.png" alt="" class="border brand-image img-circle elevation-3"
                        style="opacity: .8">
                    <span class="brand-text font-weight-light">
                       HS MOVIES
                    </span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                    <img src="/images/user.png" class="img-circle elevation-2" alt="">
                    </div>
                    <div class="info">
                    <a href="#" class="d-block">
                        <p class="mb-0 text-white" >{{ auth()->user()->name }}</p>
                        @foreach (auth()->user()->roles as $role)
                            <span>{{ $role->label }}</span>
                        @endforeach
                    </a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <router-link to="/dashboard/home" class="nav-link" active-class="active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                            </router-link>

                        </li>
                    <li class="nav-item has-treeview menu-open">
                        <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Managment
                            <i class="right fas fa-angle-left"></i>
                        </p>
                        </a>

                        <ul class="nav nav-treeview">
                            @can('manage_category')
                                <li class="nav-item">
                                    <router-link to="/dashboard/categories" class="nav-link" active-class="active" >
                                    <i class="fas fa-list-alt"></i>
                                    <p>Categories</p>
                                    </router-link>
                                </li>
                            @endcan
                            @can('manage_movie')
                                <li class="nav-item">
                                    <router-link to="/dashboard/movies" class="nav-link" active-class="active">
                                    <i class="fas fa-film"></i>
                                    <p>Movies</p>
                                    </router-link>
                                </li>
                            @endcan
                            @can('manage_actor')
                                <li class="nav-item">
                                    <router-link to="/dashboard/actors" class="nav-link" active-class="active">
                                    <i class="fas fa-user-tie"></i>
                                    <p>Actors</p>
                                    </router-link>
                                </li>
                            @endcan
                            @can('manage_role')
                                <li class="nav-item">
                                    <router-link to="/dashboard/roles" class="nav-link" active-class="active" >
                                        <i class="fas fa-tasks"></i>
                                    <p>Roles</p>
                                    </router-link>
                                </li>
                            @endcan
                            @can('manage_user')
                                <li class="nav-item">
                                    <router-link to="/dashboard/users" class="nav-link" active-class="active">
                                    <i class="fas fa-users"></i>
                                    <p>Users</p>
                                    </router-link>
                                </li>
                            @endcan
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a
                            href="{{ route('logout') }}"
                            class="nav-link"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>{{ __('Logout') }}</p>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                            </form>
                        </a>
                    </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <app-dashboard></app-dashboard>

            <!-- Main Footer -->
            <footer class="main-footer">
                    <!-- To the right -->
                    <div class="float-right d-none d-sm-inline">
                    HS MOVIES
                    </div>
                    <!-- Default to the left -->
                    <strong>Copyright &copy; 2020</strong> All rights reserved.
            </footer>
        </div>
  <script src="{{ asset('js/app.js') }}" defer></script>
  @auth
  <script>

      window.auth = @json([
        'userId' => auth()->id(),
        'roles' => auth()->user()->roles()->pluck('name')->unique(),
      'abilities' => auth()->user()->abilities()
      ])
</script>
  @endauth
  </body>
</html>
