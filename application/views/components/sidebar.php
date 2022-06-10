<?php
if (!$this->session->userdata('sideToggle') || $this->session->userdata('sideToggle') == FALSE) {
	$toggle = '';
} else if ($this->session->userdata('sideToggle') == TRUE) {
	$toggle = 'toggled';
}
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion <?= $toggle ?>" id="accordionSidebar">
	<a class="sidebar-brand d-lg-flex align-items-center justify-content-center bg-white shadow-sm d-none" href="<?= base_url('/') ?>">
		<div class="sidebar-brand-icon text-primary">
			<i class="fas fa-building"></i>
		</div>
		<div class="sidebar-brand-text mx-3 my-auto">
			<img class="img-fluid rounded company-logo" src="<?= base_url('assets/img/indofoodcbp_mini.png') ?>">
		</div>
	</a>
	<?php $p = uri_string(); ?>
	<!-- Administrator -->
	<div class="sidebar-heading mr-3 mt-3">
		Dashboard
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
			<span>Keterlambatan</span>
		</a>
	</li>
	<?php if (in_array($this->session->userdata('user')->dept_code, [2,3,4,12]) || $this->session->userdata('user')->is_spv == 1): ?>
	<li class="nav-item <?= ($p=='out')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('out') ?>">
			<i class="fas fa-fw fa-shoe-prints"></i>
			<span>Kedisiplinan</span>
		</a>
	</li>
	<?php endif; ?>
	<hr class="sidebar-divider">
	<!-- Attendance Data -->
	<div class="sidebar-heading">
		Attendance Data
	</div>
	<?php if (in_array($this->session->userdata('user')->dept_code, [2,3,4,12]) || $this->session->userdata('user')->is_spv == 1): ?>
	<li class="nav-item <?= ($p=='attendance/employee')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('attendance/employee') ?>">
			<i class="fas fa-fw fa-fingerprint"></i>
			<span>Employee</span>
		</a>
	</li>
	<?php endif; ?>
	<?php if (!in_array($this->session->userdata('user')->dept_code, [2,3,4,12]) || $this->session->userdata('user')->is_spv == 1): ?>
	<li class="nav-item <?= ($p=='attendance/office')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('attendance/office') ?>">
			<i class="fas fa-fw fa-building"></i>
			<span>Office</span>
		</a>
	</li>
	<?php endif; ?>
	<?php if ($this->session->userdata('user')->is_spv == 1): ?>
	<li class="nav-item <?= ($p=='attendance/visitor')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('attendance/visitor') ?>">
			<i class="far fa-fw fa-credit-card"></i>
			<span>Visitor</span>
		</a>
	</li>
	<?php endif; ?>
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
	<?php if ($this->session->userdata('user')->is_spv == 1): ?>
	<hr class="d-none sidebar-divider">
	<!-- Setup -->
	<div class="d-none sidebar-heading">
		Setup
	</div>
	<li class="d-none nav-item <?= ($p=='setup/duration')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('setup/duration') ?>">
			<i class="fas fa-fw fa-stopwatch"></i>
			<span>Duration</span>
		</a>
	</li>
	<li class="d-none nav-item <?= ($p=='setup/shift')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('setup/shift') ?>">
			<i class="fas fa-fw fa-clock"></i>
			<span>Shift</span>
		</a>
	</li>
	<hr class="sidebar-divider">
	<!-- Log Data-->
	<div class="sidebar-heading">
		Log Data
	</div>
	<li class="nav-item <?= ($p=='scanlog/filter')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('scanlog/filter') ?>">
			<i class="fas fa-fw fa-filter"></i>
			<span>Filter Data Kehadiran</span>
		</a>
	</li>
	<li class="nav-item <?= ($p=='scanlog')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('scanlog') ?>">
			<i class="fas fa-fw fa-align-left"></i>
			<span>Scan Log</span>
		</a>
	</li>
	<hr class="sidebar-divider">
	<!-- Auth-->
	<div class="sidebar-heading">
		Access
	</div>
	<li class="nav-item <?= ($p=='auth/manage')?'active':'' ?>">
		<a class="nav-link" href="<?= base_url('auth/manage') ?>">
			<i class="fas fa-fw fa-user-circle"></i>
			<span>Authorization</span>
		</a>
	</li>
	<?php endif; ?>
	<!-- Divider -->
	<hr class="sidebar-divider d-none d-md-block">
	<!-- Sidebar Toggler (Sidebar) -->
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle" onclick="sidebar_toggle()"></button>
	</div>
</ul>