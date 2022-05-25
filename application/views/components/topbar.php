<div id="content-wrapper" class="d-flex flex-column bg-gradient-light">
	<div id="content">
		<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 sticky-top shadow">
			<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" onclick="sidebar_toggle()">
				<i class="fa fa-bars"></i>
			</button>
			<div>
				<h4 class="h5 px-lg-3 pb-2 pt-3 text-primary font-weight-bold"><?= $nav_title ?? $title ?></h4>
			</div>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item dropdown no-arrow">
					<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="mx-3 d-none d-lg-inline text-gray-600 font-weight-bold"><?= $this->session->userdata('user')->nama ?? '' ?></span>
						<img class="img-profile rounded-circle" src="<?= base_url('assets/img/undraw_profile.svg') ?>">
					</a>
					<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
						<div class="dropdown-header pb-1">
							<i class="fas fa-fw fa-building text-primary"></i> &nbsp; <?= $this->session->userdata('user')->dept_name ?? '' ?>
						</div>
						<div class="dropdown-header pb-3 d-sm-inline d-md-none">
							<i class="fas fa-fw fa-user-circle"></i> &nbsp; <?= $this->session->userdata('user')->nama ?? '' ?>
						</div>
						<a class="dropdown-item d-none" href="#">
							<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
							Profile
						</a>
						<div class="dropdown-divider d-none"></div>
						<a class="dropdown-item" href="<?= base_url('logout') ?>">
							<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
							Logout
						</a>
					</div>
				</li>
			</ul>
		</nav>
		<!-- End of Topbar -->