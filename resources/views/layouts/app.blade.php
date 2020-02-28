<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Datagen</title>

	<!-- Global stylesheets -->
	<link href="{{ asset('https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="{{ asset('css/materialdesignicons.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{ asset('global_assets/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{ asset('global_assets/js/plugins/visualization/d3/d3.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/visualization/d3/d3_tooltip.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>

	<script src="{{ asset('assets/js/app.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_pages/dashboard.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_charts/pages/dashboard/light/streamgraph.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_charts/pages/dashboard/light/sparklines.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_charts/pages/dashboard/light/lines.js') }}"></script>	
	<script src="{{ asset('global_assets/js/demo_charts/pages/dashboard/light/areas.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_charts/pages/dashboard/light/donuts.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_charts/pages/dashboard/light/bars.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_charts/pages/dashboard/light/progress.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_charts/pages/dashboard/light/heatmaps.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_charts/pages/dashboard/light/pies.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_charts/pages/dashboard/light/bullets.js') }}"></script>
	<!-- /theme JS files -->

	<!-- Important Styles -->
	<style rel="stylesheet">
		* {
			font-family: Roboto,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
			font-size: .8125rem;
		}
		html {
			overflow: hidden !important;
		}
		.modal {
			z-index: 99 !important;
		}
		ul.pagination-list {
			list-style: none !important;
			margin-top: 0 !important;
		}
		ul.pagination-list .pagination-link {
			font-size: initial !important;
		}
		.table-full {
			width: 100%;
		}
		.action {
			cursor: pointer;
		}
	</style>
	<!-- /Important Styles -->
</head>

<body>
	<div id="app">
		<!-- Main navbar -->
		<div class="navbar navbar-expand-md navbar-dark" style="background-color:#324148;">
			<div class="navbar-brand">
				<a href="index.html" class="d-inline-block">
					<img src="../../../../global_assets/images/logo_light.png" alt="">
				</a>
			</div>

			<div class="d-md-none">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
					<i class="icon-tree5"></i>
				</button>
				<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
					<i class="icon-paragraph-justify3"></i>
				</button>
			</div>

			<div class="collapse navbar-collapse" id="navbar-mobile">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
							<i class="icon-paragraph-justify3"></i>
						</a>
					</li>
				</ul>

				<span class="badge bg-success ml-md-3 mr-md-auto">Online</span>

				<header-user :user="{{ $user }}"></header-user>
			</div>
		</div>
		<!-- /main navbar -->

		<!-- Page content -->
		<div class="page-content">
			<!-- Main sidebar -->
			<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

				<!-- Sidebar mobile toggler -->
				<div class="sidebar-mobile-toggler text-center">
					<a href="#" class="sidebar-mobile-main-toggle">
						<i class="icon-arrow-left8"></i>
					</a>
					Navigation
					<a href="#" class="sidebar-mobile-expand">
						<i class="icon-screen-full"></i>
						<i class="icon-screen-normal"></i>
					</a>
				</div>
				<!-- /sidebar mobile toggler -->


				<!-- Sidebar content -->
				<div class="sidebar-content">

					<!-- User menu -->
					<div class="sidebar-user">
						<div class="card-body">
							<div class="media">
								<div class="mr-3">
									<a href="#"><img src="../../../../global_assets/images/placeholders/placeholder.jpg" width="38" height="38" class="rounded-circle" alt=""></a>
								</div>

								<div class="media-body">
									<div class="media-title font-weight-semibold">{{ $user->name }}</div>
									<div class="font-size-xs opacity-50">
										<i class="icon-envelop3 font-size-sm"></i> {{ $user->email }}
									</div>
								</div>

								<div class="ml-3 align-self-center">
									<a href="#" class="text-white"><i class="icon-cog3"></i></a>
								</div>
							</div>
						</div>
					</div>
					<!-- /user menu -->


					<!-- Main navigation -->
					<div class="card card-sidebar-mobile">
						<ul class="nav nav-sidebar" data-nav-type="accordion">

							<!-- Main -->
							<li class="nav-item-header">
								<div class="text-uppercase font-size-xs line-height-xs">Main</div> 
								<i class="icon-menu" title="Main"></i>
							</li>
							<li class="nav-item">
								<router-link :to="{name: 'main-home'}" class="nav-link" :class="{'active': $route.name == 'main-home'}">
									<i class="icon-home4"></i>
									<span>Dashboard</span>
								</router-link>
							</li>						
							<!-- /main -->

							@if (request()->user()->is_admin)
							<!-- admin -->
							<li class="nav-item-header">
								<div class="text-uppercase font-size-xs line-height-xs">Admin</div> 
								<i class="icon-menu" title="Admin"></i>
							</li>
							<li class="nav-item">
								<router-link :to="{name: 'admin-users'}" class="nav-link" :class="{'active': $route.name == 'admin-users'}">
									<i class="icon-users"></i>
									<span>Kullanıcılar</span>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link :to="{name: 'admin-texts'}" class="nav-link" :class="{'active': $route.name == 'admin-texts'}">
									<i class="icon-paragraph-center3 "></i>
									<span>Metinler</span>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link :to="{name: 'admin-settings'}" class="nav-link" :class="{'active': $route.name == 'admin-settings'}">
									<i class="icon-gear"></i>
									<span>Ayarlar</span>
								</router-link>
							</li>							
							<!-- /admin -->
							@endif

							<!-- Texts -->
							<li class="nav-item-header">
								<div class="text-uppercase font-size-xs line-height-xs">Metinler</div> 
								<i class="icon-menu" title="Metinler"></i>
							</li>
							<li class="nav-item">
								<router-link :to="{name: 'texts-tagging'}" class="nav-link" :class="{'active': $route.name == 'texts-tagging'}">
									<i class="icon-pencil3"></i> 
									<span>İşaretle</span>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link :to="{name: 'texts-tagged'}" class="nav-link" :class="{'active': $route.name == 'texts-tagged'}">
									<i class="icon-quill4"></i> 
									<span>Son işaretlenenler</span>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link :to="{name: 'texts-statistics'}" class="nav-link" :class="{'active': $route.name == 'texts-statistics'}">
									<i class="icon-stats-dots"></i> 
									<span>İstatistik</span>
								</router-link>
							</li>
							<!-- /texts -->

							<!-- Support -->
							<li class="nav-item-header">
								<div class="text-uppercase font-size-xs line-height-xs">Destek</div> 
								<i class="icon-menu" title="Destek"></i>
							</li>
							<li class="nav-item">
								<router-link :to="{name: 'support-contact'}" class="nav-link" :class="{'active': $route.name == 'support-contact'}">
									<i class="icon-comment-discussion"></i> 
									<span>İletişim</span>
								</router-link>
							</li>
							<!-- /support -->
							

						</ul>
					</div>
					<!-- /main navigation -->

				</div>
				<!-- /sidebar content -->
				
			</div>
			<!-- /main sidebar -->


			<!-- Main content -->
			<div class="content-wrapper scrollbar bold" style="max-height: calc(100vh - 54px);">
				@yield('content')
			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->
	</div>
</body>
<!-- Vue Component Loader -->
<script src="{{ mix('js/app.js') }}"></script>
<!-- /Vue Component Loader -->
</html>
