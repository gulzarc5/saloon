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
    
    <title>Saloon App | Admin Dashboard</title>
  	<link rel="icon" href="{{ asset('logo/salon_small.webp')}}" type="image/ico" />


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
                <img src="{{ asset('logo/logo.png')}}" height="50" style=" width: 90%;">
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
                      <a><i class="fa fa-users" aria-hidden="true"></i> Users<span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('admin.freelancer_list')}}">Freelancer</a></li>
                        <li><a href="{{ route('admin.shop_list')}}">Shop List</a></li>
                        <li><a href="{{ route('admin.customer_list')}}">Customers</a></li>
                        <li><a href="{{ route('admin.password_request')}}">Password Request</a></li>
                      </ul>
                  </li>
                  <li>
                        <a><i class="fa fa-laptop" aria-hidden="true"></i> Category <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('admin.add.service_category')}}">Add Category</a></li>
                            <li><a href="{{ route('admin.service_category')}}">Category List</a></li>
                            <li><a href="{{ route('category.create')}}">Add Sub Category List</a></li>
                            <li><a href="{{ route('category.index')}}">Sub Category List</a></li>
                            <li><a href="{{ route('level.create')}}">Add Third Level Category</a></li>
                            <li><a href="{{ route('level.index')}}">Third Level Category List</a></li>
                        </ul>
                  </li>
                  <li>
                      <a><i class="fa fa-shopping-cart" aria-hidden="true"></i> Orders <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('admin.orders')}}">Orders List</a></li>
                        <li><a href="{{ route('admin.vendor_cancel_orders')}}">Vendor Cancel Orders</a></li>
                        {{-- <li><a href="{{ route('admin.refunds')}}">Refund List</a></li> --}}
                      </ul>
                  </li>
                  <li>
                      <a><i class="fa fa-cog" aria-hidden="true"></i> Configuration <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('admin.state')}}">State List</a></li>
                        <li><a href="{{ route('admin.city')}}">City List</a></li>
                        <li><a href="{{ route('admin.serviceCity')}}">Service City List</a></li>
                        <li><a href="{{ route('admin.app_slider_list')}}">Aapp Sliders</a></li>
                        <li class="sub_menu"><a href="{{route('admin.invoice_form')}}">Invoice Setting</a></li>
                        <li class="sub_menu"><a href="{{route('admin.admin_commission_list')}}">Admin Commission</a></li>
                        <li class="sub_menu"><a href="{{route('admin.referral_list')}}">Referral Setting</a></li>
                      </ul>
                  </li>
                  <li><a href="{{route('admin.coupon_list')}}"><i class="fa fa-key" aria-hidden="true"></i>Coupon</a></li>
                  <li><a href="{{route('admin.contact_mail_list')}}"><i class="fa fa-key" aria-hidden="true"></i>Contact Mail</a></li>
                  <li><a href="{{route('admin.offer_list')}}"><i class="fa fa-key" aria-hidden="true"></i>Offer</a></li>
                  <li><a href="{{route('admin.message_list')}}"><i class="fa fa-key" aria-hidden="true"></i>Message</a></li>
                  {{-- <li>
                      <a><i class="fa fa-search-plus" aria-hidden="true"></i> Enquery <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('admin.feedback')}}">Feedback</a></li>
                        <li><a href="{{ route('admin.enquery')}}">Enquery</a></li>
                      </ul>
                  </li> --}}
                  <li><a href="{{route('admin.change_password_form')}}"><i class="fa fa-key" aria-hidden="true"></i>Change Password</a></li>
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
