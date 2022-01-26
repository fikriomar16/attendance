<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/sb-admin-2.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/app.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>">
	<link rel="icon" href="<?= base_url('assets/img/undraw_posting_photo.svg') ?>">
	<title><?= $title ?? 'Masuk' ?></title>
</head>
<body class="bg-gradient-light" ng-app="loginPage" ng-controller="loginController" data-home="<?= base_url() ?>">
	<div class="container">
		<div class="row justify-content-center min-vh-100">
			<div class="col-lg-6 m-auto">
				<div class="card border-0 shadow rounded-lg">
					<div class="card-header text-center h4 font-weight-bold text-uppercase p-3 border-0 shadow-sm">
						<i class="fab fa-fw fa-envira"></i>&nbsp; Selamat Datang
					</div>
					<div class="card-body p-lg-5 p-md-4 p-sm-3">
						<div class="alert alert-success fade show text-center d-none" role="alert">
							<strong>{{successMsg}}.</strong>
						</div>
						<div class="alert alert-danger fade show text-center d-none" role="alert">
							<strong>{{errorMsg}}</strong>
						</div>
						<form method="post" class="user" action="<?= base_url('auth') ?>" id="loginForm" name="loginForm">
							<div class="form-group">
								<label class="font-weight-bold">Username</label>
								<input type="text" class="form-control form-control-user text-center" id="username" name="username" ng-model="username" autofocus>
							</div>
							<div class="form-group">
								<label class="font-weight-bold">Password</label>
								<input type="password" class="form-control form-control-user text-center" id="password" name="password" ng-model="password">
							</div>
							<button type="button" class="btn btn-primary btn-user btn-block shadow-lg" ng-click="auth()">
								<p class="h5 font-weight-bold">Login</p>
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Core -->
	<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
	<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/angular.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
	<!-- Functional -->
	<script src="<?= base_url('assets/js/auth.js') ?>"></script>
</body>
</html>
<!-- https://sb-admin-pro.startbootstrap.com/index.html -->