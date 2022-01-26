<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
	<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('/') ?>">
		<div class="sidebar-brand-icon">
			<i class="fab fa-envira"></i>
		</div>
		<div class="sidebar-brand-text mx-3">Regios</div>
	</a>
	<?php $p = uri_string(); ?>
	<hr class="sidebar-divider">
	<!-- Administrator -->
	<div class="sidebar-heading mr-3">
		Administrator
	</div>
	<li class="nav-item <?= ($p=='dashboard'||$p=='')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('dashboard') ?>">
			<i class="fas fa-fw fa-home"></i>
			<span>Dashboard</span>
		</a>
	</li>
	<li class="nav-item <?= ($p=='late')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('late') ?>">
			<i class="fas fa-fw fa-exclamation-triangle"></i>
			<span>Late Notice</span>
		</a>
	</li>
	<hr class="sidebar-divider">
	<!-- Attendance Data -->
	<div class="sidebar-heading">
		Attendance Data
	</div>
	<li class="nav-item <?= ($p=='attendance/employee')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('attendance/employee') ?>">
			<i class="fas fa-fw fa-fingerprint"></i>
			<span>Employee</span>
		</a>
	</li>
	<li class="nav-item <?= ($p=='attendance/internal')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('attendance/internal') ?>">
			<i class="fas fa-fw fa-id-card"></i>
			<span>Internal</span>
		</a>
	</li>
	<li class="nav-item <?= ($p=='attendance/visitor')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('attendance/visitor') ?>">
			<i class="far fa-fw fa-credit-card"></i>
			<span>Visitor</span>
		</a>
	</li>
	<hr class="sidebar-divider">
	<!-- User Management -->
	<div class="sidebar-heading">
		User Management
	</div>
	<li class="nav-item <?= ($p=='management/employee')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('management/employee') ?>">
			<i class="fas fa-fw fa-user"></i>
			<span>Employee</span>
		</a>
	</li>
	<li class="nav-item <?= ($p=='management/internal')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('management/internal') ?>">
			<i class="fas fa-fw fa-id-card"></i>
			<span>Internal</span>
		</a>
	</li>
	<li class="nav-item <?= ($p=='management/visitor')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('management/visitor') ?>">
			<i class="fas fa-fw fa-users"></i>
			<span>Visitor</span>
		</a>
	</li>
	<li class="nav-item <?= ($p=='management/departement')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('management/departement') ?>">
			<i class="fas fa-fw fa-building"></i>
			<span>Departement</span>
		</a>
	</li>
	<hr class="sidebar-divider">
	<!-- User Schedule -->
	<div class="sidebar-heading">
		User Schedule
	</div>
	<li class="nav-item <?= ($p=='schedule/employee')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('schedule/employee') ?>">
			<i class="fas fa-fw fa-user"></i>
			<span>Employee</span>
		</a>
	</li>
	<li class="nav-item <?= ($p=='schedule/internal')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('schedule/internal') ?>">
			<i class="fas fa-fw fa-id-card"></i>
			<span>Internal</span>
		</a>
	</li>
	<hr class="sidebar-divider">
	<!-- Setup -->
	<div class="sidebar-heading">
		Setup
	</div>
	<li class="nav-item <?= ($p=='setup/gate')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('setup/gate') ?>">
			<i class="fas fa-fw fa-door-open"></i>
			<span>Gate</span>
		</a>
	</li>
	<li class="nav-item <?= ($p=='setup/card')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('setup/card') ?>">
			<i class="fas fa-fw fa-credit-card"></i>
			<span>Card</span>
		</a>
	</li>
	<li class="nav-item <?= ($p=='setup/duration')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('setup/duration') ?>">
			<i class="fas fa-fw fa-stopwatch"></i>
			<span>Duration</span>
		</a>
	</li>
	<li class="nav-item <?= ($p=='setup/menu')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('setup/menu') ?>">
			<i class="fas fa-fw fa-list"></i>
			<span>Menu</span>
		</a>
	</li>
	<!-- Divider -->
	<hr class="sidebar-divider d-none d-md-block">
	<!-- Sidebar Toggler (Sidebar) -->
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>
</ul>