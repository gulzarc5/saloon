<!DOCTYPE html>
<html lang="en">
  	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="icon" href="images/favicon.png" type="image/ico" />

    <title>Saloon App | Admin Dashboard</title>
    {{-- <link rel="icon" href="{{ asset('logo/logo.png')}}" type="image/icon type"> --}}


    <!-- Bootstrap -->
    <link href="{{asset('admin/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('admin/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('admin/vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{asset('admin/vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
  
    <!-- bootstrap-progressbar -->
    <link href="{{asset('admin/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{asset('admin/vendors/jqvmap/dist/jqvmap.min.css')}}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{asset('admin/vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    {{-- Datatables --}}
     <link href="{{asset('admin/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">

    {{-- pnotify --}}
    
   {{--  <link href="{{asset('admin/src_files/vendors/pnotify/dist/pnotify.css')}}" rel="stylesheet">
    <link href="{{asset('admin/src_files/vendors/pnotify/dist/pnotify.buttons.css')}}" rel="stylesheet">
    <link href="{{asset('admin/src_files/vendors/pnotify/dist/pnotify.nonblock.css')}}" rel="stylesheet"> --}}

    <!-- Custom Theme Style -->
    <link href="{{asset('admin/build/css/custom.min.css')}}" rel="stylesheet">
  </head>

  <body class="{{ $class='nav-md' ?? '' }}">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="{{route('admin.deshboard')}}" class="site_title">
                Saloon App
                {{-- <img src="{{ asset('logo/logo.png')}}" height="50" style=" width: 90%;"> --}}
              </a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
            {{--   <div class="profile_pic">
                <img src="images/img.jpg" alt="..." class="img-circle profile_img">
              </div> --}}
              <div class="profile_info">
                <span>Welcome,<b>{{ Auth::guard('admin')->user()->name }}</b></span>
                
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a href="{{ route('admin.deshboard')}}"><i class="fa fa-home"></i> Home </a></li>
                  <li>
                      <a><i class="fa fa-users" aria-hidden="true"></i> User <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('admin.carrier')}}">All Users</a></li>
                      </ul>
                  </li>
                  <li>
                      <a><i class="fa fa-first-order" aria-hidden="true"></i> Order <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('admin.carrier')}}">All Orders</a></li>
                      </ul>
                  </li>
                  {{-- <li>
                      <a><i class="fa fa-gift" aria-hidden="true"></i> Package <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('admin.package')}}">Add Package</a></li>
                        <li><a href="{{ route('admin.package_list')}}">Package List</a></li>
                      </ul>
                  </li> --}}
                  {{-- <li>
                      <a><i class="fa fa-certificate" aria-hidden="true"></i> Testimonial <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('admin.testimonial')}}">Post Testimonial</a></li>
                        <li><a href="{{ route('admin.testimonial_list')}}">Testimonial List</a></li>
                      </ul>
                  </li> --}}
                  <li>
                      <a><i class="fa fa-graduation-cap" aria-hidden="true"></i> Carrier <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('admin.carrier')}}">Post Job Openings</a></li>
                        <li><a href="{{ route('admin.carrier_list')}}">Job Openings</a></li>
                        <li><a href="{{ route('admin.carrier')}}">Applicant List</a></li>
                      </ul>
                  </li>
                  <li>
                      <a><i class="fa fa-newspaper-o" aria-hidden="true"></i> Blog <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('admin.blog')}}">Post Blog</a></li>
                        <li><a href="{{ route('admin.blog_list') }}">Blog List</a></li>
                      </ul>
                  </li>
                </ul>
              </div>

            </div>
          </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="#">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li><a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
             <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->