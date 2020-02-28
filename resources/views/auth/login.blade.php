<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Datagen | Giriş Yap</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ asset('global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{ asset('global_assets/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
    <script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
	<script src="{{ asset('global_assets/js/plugins/notifications/noty.min.js') }}"></script>

	<script src="{{ asset('assets/js/app.js') }}"></script>
	<script src="{{ asset('global_assets/js/demo_pages/extra_jgrowl_noty.js') }}"></script>
    <script src="{{ asset('global_assets/js/demo_pages/login.js') }}"></script>
    @if (!$errors->isEmpty())
    <script type="text/javascript">
        $(document).ready(function(){
            @foreach ($errors->all() as $error)
                new Noty({
                    text: '{{ $error }}',
                    type: 'error'
                }).show();
            @endforeach
        })
    </script>
    @endif
	<!-- /theme JS files -->

</head>

<body class="bg-slate-800">

	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">
			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">
                <!-- Login card -->
                
				<form class="login-form" action="" method="POST">
                    {{ csrf_field() }}
					<div class="card mb-0">
						<div class="card-body">
							<div class="text-center mb-3">
								<i class="icon-people icon-2x text-warning-400 border-warning-400 border-3 rounded-round p-3 mb-3 mt-1"></i>
								<h5 class="mb-0">Hesabına giriş yap</h5>
								<span class="d-block text-muted">Bilgileriniz</span>
							</div>

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="email" name="email" class="form-control" placeholder="E-Posta">
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="password" name="password" class="form-control" placeholder="Parola">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
							</div>

							<div class="form-group d-flex align-items-center">
								<div class="form-check mb-0">
									<label class="form-check-label">
										<input type="checkbox" name="remember" class="form-input-styled" checked data-fouc>
										Beni Hatırla
									</label>
								</div>

								<a href="#" class="ml-auto">Parolamı Unuttum?</a>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block">Giriş yap <i class="icon-circle-right2 ml-2"></i></button>
							</div>

							<span class="form-text text-center text-muted">Devam ederek <a href="#">Şartlar &amp; Koşullarımızı</a> ve <a href="#">Çerez Politikamızı</a> okuduğunuzu onaylıyorsunuz.</span>
						</div>
					</div>
				</form>
				<!-- /login card -->

			</div>
			<!-- /content area -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->

</body>
</html>