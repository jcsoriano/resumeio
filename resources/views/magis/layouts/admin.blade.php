<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>
		@yield('title', 'MagisSolutions, Inc.')
	</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}" />
    <meta id="homepage" name="homepage" content="{{ url('/') }}" />

	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.min.css">
	<style>
		* {
			font-family: Lato;
		}
		.fixed .content-wrapper {
			padding-top: 90px;
			padding-left: 40px;
			padding-right: 30px;
		}
		.magis-form {
			margin-top:40px;
		}
		.navbar-custom-menu>.navbar-nav>li>.dropdown-menu {
			-webkit-box-shadow: 5px 5px 20px 0px rgba(0,0,0,0.3);
			-moz-box-shadow: 5px 5px 20px 0px rgba(0,0,0,0.3);
			box-shadow: 5px 5px 20px 0px rgba(0,0,0,0.3);
		}
		.main-sidebar, .left-side {
			/*-webkit-box-shadow: 5px 5px 20px 0px rgba(0,0,0,0.3);
			-moz-box-shadow: 5px 5px 20px 0px rgba(0,0,0,0.3);
			box-shadow: 5px 5px 20px 0px rgba(0,0,0,0.3);*/
		}
		.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6, label, .skin-black-light .sidebar-menu>li>a {
			font-weight: 400;
		}
		.main-header .navbar {
			-webkit-box-shadow: 5px 5px 20px 0px rgba(0,0,0,0.3);
			-moz-box-shadow: 5px 5px 20px 0px rgba(0,0,0,0.3);
			box-shadow: 5px 5px 20px 0px rgba(0,0,0,0.3);
		}
		.content-wrapper, .right-side {
			background-color:white;
		}
		.error {
			color: red;
		}
		.chosen-container {
			width:100% !important;
		}
		#admin-my-recent-activity hr {
			margin-top: 5px;
			margin-bottom: 5px;
		}
	</style>

	@yield('header')

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="hold-transition skin-black-light sidebar-mini fixed">
	<div class="wrapper" id="app">
		<header class="main-header">
			<!-- Logo -->
			<a href="../../index2.html" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels -->
				<span class="logo-mini"><b>M</b></span>
				<!-- logo for regular state and mobile devices -->
				<span class="logo-lg"><b>MAGIS</b>CMS</span>
			</a>
			<!-- Header Navbar: style can be found in header.less -->
			<nav class="navbar navbar-static-top">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>

				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<!-- Messages: style can be found in dropdown.less-->
						<li class="dropdown messages-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-envelope-o"></i>
								<span class="label label-success">4</span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">You have 4 messages</li>
								<li>
									<!-- inner menu: contains the actual data -->
									<ul class="menu">
										<li><!-- start message -->
											<a href="#">
												<div class="pull-left">
													<img src="{{ asset(Auth::user()->photo) }}" class="img-circle" alt="User Image">
												</div>
												<h4>
													Support Team
													<small><i class="fa fa-clock-o"></i> 5 mins</small>
												</h4>
												<p>Why not buy a new awesome theme?</p>
											</a>
										</li>
										<!-- end message -->
										<li>
											<a href="#">
												<div class="pull-left">
													<img src="{{ asset('Magis/AdminLTE-2.3.7/dist/img/user3-128x128.jpg') }}" class="img-circle" alt="User Image">
												</div>
												<h4>
													AdminLTE Design Team
													<small><i class="fa fa-clock-o"></i> 2 hours</small>
												</h4>
												<p>Why not buy a new awesome theme?</p>
											</a>
										</li>
										<li>
											<a href="#">
												<div class="pull-left">
													<img src="{{ asset('Magis/AdminLTE-2.3.7/dist/img/user4-128x128.jpg') }}" class="img-circle" alt="User Image">
												</div>
												<h4>
													Developers
													<small><i class="fa fa-clock-o"></i> Today</small>
												</h4>
												<p>Why not buy a new awesome theme?</p>
											</a>
										</li>
										<li>
											<a href="#">
												<div class="pull-left">
													<img src="{{ asset('Magis/AdminLTE-2.3.7/dist/img/user3-128x128.jpg') }}" class="img-circle" alt="User Image">
												</div>
												<h4>
													Sales Department
													<small><i class="fa fa-clock-o"></i> Yesterday</small>
												</h4>
												<p>Why not buy a new awesome theme?</p>
											</a>
										</li>
										<li>
											<a href="#">
												<div class="pull-left">
													<img src="{{ asset('Magis/AdminLTE-2.3.7/dist/img/user4-128x128.jpg') }}" class="img-circle" alt="User Image">
												</div>
												<h4>
													Reviewers
													<small><i class="fa fa-clock-o"></i> 2 days</small>
												</h4>
												<p>Why not buy a new awesome theme?</p>
											</a>
										</li>
									</ul>
								</li>
								<li class="footer"><a href="#">See All Messages</a></li>
							</ul>
						</li>
						<li class="dropdown messages-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-bell-o"></i>
								<span class="label label-warning">10</span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">Recent Activities</li>
								<li class="col-xs-12">
									<collection resource="activities" subtype="string" items="{{ json_encode(Activities::recentFormatted()) }}" ></collection>
								</li>
							</ul>
						</li>
						<!-- Tasks: style can be found in dropdown.less -->
						<li class="dropdown tasks-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-flag-o"></i>
								<span class="label label-danger">9</span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">You have 9 tasks</li>
								<li>
									<!-- inner menu: contains the actual data -->
									<ul class="menu">
										<li><!-- Task item -->
											<a href="#">
												<h3>
													Design some buttons
													<small class="pull-right">20%</small>
												</h3>
												<div class="progress xs">
													<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
														<span class="sr-only">20% Complete</span>
													</div>
												</div>
											</a>
										</li>
										<!-- end task item -->
										<li><!-- Task item -->
											<a href="#">
												<h3>
													Create a nice theme
													<small class="pull-right">40%</small>
												</h3>
												<div class="progress xs">
													<div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
														<span class="sr-only">40% Complete</span>
													</div>
												</div>
											</a>
										</li>
										<!-- end task item -->
										<li><!-- Task item -->
											<a href="#">
												<h3>
													Some task I need to do
													<small class="pull-right">60%</small>
												</h3>
												<div class="progress xs">
													<div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
														<span class="sr-only">60% Complete</span>
													</div>
												</div>
											</a>
										</li>
										<!-- end task item -->
										<li><!-- Task item -->
											<a href="#">
												<h3>
													Make beautiful transitions
													<small class="pull-right">80%</small>
												</h3>
												<div class="progress xs">
													<div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
														<span class="sr-only">80% Complete</span>
													</div>
												</div>
											</a>
										</li>
										<!-- end task item -->
									</ul>
								</li>
								<li class="footer">
									<a href="#">View all tasks</a>
								</li>
							</ul>
						</li>
						<!-- User Account: style can be found in dropdown.less -->
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="{{ asset(Auth::user()->photo) }}" class="user-image" alt="User Image">
								<span class="hidden-xs">{{ Auth::user()->name }}</span>
							</a>
							<ul class="dropdown-menu">
								<!-- User image -->
								<li class="user-header">
									<img src="{{ asset(Auth::user()->photo) }}" class="img-circle" alt="User Image">

									<p>
										{{ Auth::user()->name }}
										<small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
									</p>
								</li>

								<?php $quickStats = [
									[
										'metric' => 'Posts',
										'link' => 'posts/author/'.Auth::user()->slug,
										'model' => 'App\Models\Content\Post',
										'module' => 'Blog'
									],
									[
										'metric' => 'Products',
										'model' => 'App\Models\Shop\Product',
										'module' => 'Shop'
									],
									[
										'metric' => 'Orders',
										'model' => 'App\Models\Shop\Order',
										'module' => 'Shop'
									],
								]; ?>
								
								<?php $hasAnyInstalled = false; ?>
								
								@foreach($quickStats as $qs)
									@if(defined($qs['model'].'::SLUG'))
										<?php $hasAnyInstalled = true; ?>
									@endif
								@endforeach

								<?php $hasUserAbout = isset(Auth::user()->about) && ! empty(Auth::user()->about); ?>

								<!-- Menu Body -->
								@if($hasAnyInstalled || $hasUserAbout)
									<li class="user-body">
										<div class="row">
											@if($hasUserAbout)
												<div class="col-xs-12">
													{{ Content::excerpt(Auth::user()->about) }}
												</div>
											@endif

											@if($hasAnyInstalled)
												@foreach($quickStats as $qs)
													<div class="col-xs-4 text-center">
														@if(defined($qs['model'].'::SLUG'))
															<a href="{{ url($qs['link']) }}" target="_blank">
																{{ $qs['metric'] }}
															</a>
															<br/>
															<h4 style="margin:0">
																{{ $qs['model']::mine()->count() }}
															</h4>
														@else
															<a href="#">
																{{ $qs['metric'] }}
															</a>
															<br/>
															<small>{{ $qs['module'] }} not installed.</small>
														@endif
													</div>
												@endforeach
											@endif
										</div>
									</li>
								@endif
								<!-- Menu Footer-->
								<li class="user-footer">
									<div class="pull-left">
										<a href="{{ url('profile') }}" class="btn btn-default btn-flat">Profile</a>
									</div>
									<div class="pull-right">
										<a href="{{ url('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
	                                        Sign out
	                                    </a>
	                                    <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
	                                        {{ csrf_field() }}
	                                    </form>
									</div>
								</li>
							</ul>
						</li>
						<!-- Control Sidebar Toggle Button -->
						<li>
							<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
						</li>
					</ul>
				</div>
			</nav>
		</header>

		<aside class="main-sidebar">
			<!-- sidebar: style can be found in sidebar.less -->
			<section class="sidebar">
				<!-- Sidebar user panel -->
				<div class="user-panel">
					<div class="pull-left image">
						<img src="{{ asset(Auth::user()->photo) }}" class="img-circle" alt="User Image">
					</div>
					<div class="pull-left info">
						<p>{{ Auth::user()->name }}</p>
						<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
					</div>
				</div>
				<!-- search form -->
				<form action="#" method="get" class="sidebar-form">
					<div class="input-group">
						<input type="text" name="q" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
							<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
							</button>
						</span>
					</div>
				</form>
				<!-- /.search form -->
				<!-- sidebar menu: : style can be found in sidebar.less -->
				<ul class="sidebar-menu">
					<li class="header">CREATE A BETTER WORLD</li>
					@foreach(Menu::groupedByModule('admin') as $module => $items)
						@if(empty($module))
							@foreach($items as $i)
								{!! $i->htmlLink(['check_permissions' => true]) !!}
							@endforeach
						@else
							<li class="treeview">
								<a href="#">
									{{ $module }}
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>
								</a>
								<ul class="treeview-menu">
									@foreach($items as $i)
										{!! $i->htmlLink(['check_permissions' => true]) !!}
									@endforeach
								</ul>
							</li>
						@endif
					@endforeach
				</ul>
		</aside>

		<div class="content-wrapper">
			@yield('content')
		</div>

		<footer class="main-footer">
			<div class="pull-right hidden-xs">
				<b>Version</b> 2.0.0
			</div>
			<strong>This system has been designed, developed, and is continuously maintained with passion by <a href="https://magis.solutions">MagisSolutions, Inc.</a>.</strong> All rights
			reserved.
		</footer>

		<aside class="control-sidebar control-sidebar-dark">
			<!-- Create the tabs -->
			<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
				<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
				<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<!-- Home tab content -->
				<div class="tab-pane" id="control-sidebar-home-tab">
					<h3 class="control-sidebar-heading">Your Recent Activity</h3>
					<ul class="control-sidebar-menu">
						<li class="col-xs-12">
							<div id="admin-my-recent-activity" class="col-xs-12">
								<collection resource="activities" collection-type="default" subtype="string" items="{{ json_encode(Activities::myRecentFormatted()) }}" ></collection>
							</div>
						</li>
						<li>
							<a href="javascript:void(0)">
								<i class="menu-icon fa fa-birthday-cake bg-red"></i>

								<div class="menu-info">
									<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

									<p>Will be 23 on April 24th</p>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)">
								<i class="menu-icon fa fa-user bg-yellow"></i>

								<div class="menu-info">
									<h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

									<p>New phone +1(800)555-1234</p>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)">
								<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

								<div class="menu-info">
									<h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

									<p>nora@example.com</p>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)">
								<i class="menu-icon fa fa-file-code-o bg-green"></i>

								<div class="menu-info">
									<h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

									<p>Execution time 5 seconds</p>
								</div>
							</a>
						</li>
					</ul>
					<!-- /.control-sidebar-menu -->

					<h3 class="control-sidebar-heading">Tasks Progress</h3>
					<ul class="control-sidebar-menu">
						<li>
							<a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									Custom Template Design
									<span class="label label-danger pull-right">70%</span>
								</h4>

								<div class="progress progress-xxs">
									<div class="progress-bar progress-bar-danger" style="width: 70%"></div>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									Update Resume
									<span class="label label-success pull-right">95%</span>
								</h4>

								<div class="progress progress-xxs">
									<div class="progress-bar progress-bar-success" style="width: 95%"></div>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									Laravel Integration
									<span class="label label-warning pull-right">50%</span>
								</h4>

								<div class="progress progress-xxs">
									<div class="progress-bar progress-bar-warning" style="width: 50%"></div>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)">
								<h4 class="control-sidebar-subheading">
									Back End Framework
									<span class="label label-primary pull-right">68%</span>
								</h4>

								<div class="progress progress-xxs">
									<div class="progress-bar progress-bar-primary" style="width: 68%"></div>
								</div>
							</a>
						</li>
					</ul>
					<!-- /.control-sidebar-menu -->

				</div>
				<!-- /.tab-pane -->
				<!-- Settings tab content -->
				<div class="tab-pane" id="control-sidebar-settings-tab">
					<settings settings="{{ Settings::get() }}"></settings>
				</div>
				<!-- /.tab-pane -->
			</div>
		</aside>
	</div>
	@yield('before-foundation')
	@yield('foundation', view('magis.partials.imports.admin-foundation'))
	@include('magis.partials.imports.additional')
	@yield('scripts')
</body>
</html>