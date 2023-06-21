<!DOCTYPE html>
<html lang="en">
<head>
	<title>PMC -IMS v2</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{env('APP_URL')}}/login/bk/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="{{env('APP_URL')}}/login/bk/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="{{env('APP_URL')}}/login/bk/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="{{env('APP_URL')}}/login/bk/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="{{env('APP_URL')}}/login/bk/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="{{env('APP_URL')}}/login/bk/vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="{{env('APP_URL')}}/login/bk/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="{{env('APP_URL')}}/login/bk/vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="{{env('APP_URL')}}/login/bk/css/util.css">
	<link rel="stylesheet" type="text/css" href="{{env('APP_URL')}}/login/bk/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-b-160 p-t-50">
				<form class="login100-form validate-form" action="{{ url('/ims/checklogin') }}" method="post">
                @csrf
					<span class="login100-form-title p-b-43">
						<img alt="" style="height: 58px;" src="{{env('APP_URL')}}/assets/layouts/layout3/img/imsv2.png">
					</span>
					
					<div class="wrap-input100 rs1 validate-input" data-validate = "Domain is required">
						<input class="input100" type="text" name="domainAccount" placeholder="Domain Account">
					</div>
					
					
					<div class="wrap-input100 rs2 validate-input" data-validate="Password is required">
						<input class="input100" type="password" name="password" placeholder="Password">
					</div>

					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">
							Sign in
						</button>
					</div>
					
					<div class="text-center w-full p-t-23">
						<a href="javascript:;" class="txt1">
							{{ date('Y') }} &copy; Philsaga Mining Corporation
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	
<!--===============================================================================================-->
	<script src="{{env('APP_URL')}}/login/bk/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="{{env('APP_URL')}}/login/bk/vendor/animsition/js/animsition.min.js"></script>
	<script src="{{env('APP_URL')}}/login/bk/vendor/bootstrap/js/popper.js"></script>
	<script src="{{env('APP_URL')}}/login/bk/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="{{env('APP_URL')}}/login/bk/vendor/select2/select2.min.js"></script>
	<script src="{{env('APP_URL')}}/login/bk/vendor/daterangepicker/moment.min.js"></script>
	<script src="{{env('APP_URL')}}/login/bk/vendor/daterangepicker/daterangepicker.js"></script>
	<script src="{{env('APP_URL')}}/login/bk/vendor/countdowntime/countdowntime.js"></script>
	<script src="{{env('APP_URL')}}/login/bk/js/main.js"></script>
<!--===============================================================================================-->
</body>
</html>