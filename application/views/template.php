<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/sb-admin-2.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/app.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>">
	<link rel="icon" href="<?= base_url('assets/img/undraw_posting_photo.svg') ?>">
	<title>Main Title | Sub Title</title>
</head>
<body id="page-top">
	<div id="wrapper">
		<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('/') ?>">
				<div class="sidebar-brand-icon">
					<i class="fab fa-envira"></i>
				</div>
				<div class="sidebar-brand-text mx-3">Regios</div>
			</a>
			<hr class="sidebar-divider">
			<!-- Administrator -->
			<div class="sidebar-heading mr-3">
				Administrator
			</div>
			<li class="nav-item active">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-home"></i>
					<span>Dashboard</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-exclamation-triangle"></i>
					<span>Late Notice</span>
				</a>
			</li>
			<hr class="sidebar-divider">
			<!-- Attendance Data -->
			<div class="sidebar-heading">
				Attendance Data
			</div>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-fingerprint"></i>
					<span>Employee</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-id-card"></i>
					<span>Internal</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="far fa-fw fa-credit-card"></i>
					<span>Visitor</span>
				</a>
			</li>
			<hr class="sidebar-divider">
			<!-- User Management -->
			<div class="sidebar-heading">
				User Management
			</div>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-user"></i>
					<span>Employee</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-id-card"></i>
					<span>Internal</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-users"></i>
					<span>Visitor</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-building"></i>
					<span>Department</span>
				</a>
			</li>
			<hr class="sidebar-divider">
			<!-- User Schedule -->
			<div class="sidebar-heading">
				User Schedule
			</div>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-user"></i>
					<span>Employee</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-id-card"></i>
					<span>Internal</span>
				</a>
			</li>
			<hr class="sidebar-divider">
			<!-- Setup -->
			<div class="sidebar-heading">
				Setup
			</div>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-door-open"></i>
					<span>Gate</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-credit-card"></i>
					<span>Card</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/') ?>">
					<i class="fas fa-fw fa-stopwatch"></i>
					<span>Duration</span>
				</a>
			</li>
			<!-- Divider -->
			<hr class="sidebar-divider d-none d-md-block">
			<!-- Sidebar Toggler (Sidebar) -->
			<div class="text-center d-none d-md-inline">
				<button class="rounded-circle border-0" id="sidebarToggle"></button>
			</div>
		</ul>
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">

				<!-- Topbar -->
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 sticky-top shadow">

					<!-- Sidebar Toggle (Topbar) -->
					<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
						<i class="fa fa-bars"></i>
					</button>

					<div>
						<h3 class="h3 px-lg-3 pb-2 pt-3 text-info font-weight-bold">Title Here...</h3>
					</div>

					<!-- Topbar Navbar -->
					<ul class="navbar-nav ml-auto">

						<!-- Nav Item - User Information -->
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mx-3 d-none d-lg-inline text-gray-600">Douglas McGee</span>
								<img class="img-profile rounded-circle" src="<?= base_url('assets/img/undraw_profile.svg') ?>">
							</a>
							<!-- Dropdown - User Information -->
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" ria-labelledby="userDropdown">
								<a class="dropdown-item" href="#">
									<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
									Profile
								</a>
								<a class="dropdown-item" href="#">
									<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
									Settings
								</a>
								<a class="dropdown-item" href="#">
									<i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
									Activity Log
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
									<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
									Logout
								</a>
							</div>
						</li>

					</ul>

				</nav>
				<!-- End of Topbar -->

				<!-- Begin Page Content -->
				<div class="container-fluid">

				</div>
				<!-- /.container-fluid -->

			</div>
			<!-- End of Main Content -->

		</div>
	</div>
	<a class="scroll-to-top rounded" href="#page-top">
		<i class="fas fa-angle-up"></i>
	</a>
	<!-- Core -->
	<script src="<?= base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
	<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/angular.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
	<!-- Functional -->
	<script src="<?= base_url('assets/js/app.js') ?>"></script>
	<!-- Additional -->
	<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
</body>
</html>
<!-- https://sb-admin-pro.startbootstrap.com/index.html -->