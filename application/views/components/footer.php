				<?= (ENVIRONMENT === 'development') ?  '<div class="text-center text-primary my-5 font-weight-bold">Page Rendered in {elapsed_time} seconds.</div>' : '' ?>
			</div>
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
	<!-- Additional -->
	<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/bootstrap-select.min.js') ?>"></script>
	<script src="<?= base_url('assets/vendor/flatpickr/dist/flatpickr.min.js') ?>"></script>
	<script src="<?= base_url('assets/vendor/sweetalert2/dist/sweetalert2.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/notif.js') ?>"></script>
	<script type="text/javascript">
		const sidebar_toggle = () => {
			if ($('.toggled').length > 0) {
				$.get($('body').data('home')+'sideToggle/deact');
			} else {
				$.get($('body').data('home')+'sideToggle/act');
			}
		}
	</script>
	<!-- Functional -->
	<?php $p = uri_string(); ?>
	<?php if ($p=='dashboard'||$p==''): ?>
		<script src="<?= base_url('assets/js/administrator/dashboard.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='late'): ?>
		<script src="<?= base_url('assets/js/administrator/late.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='schedule/employee'): ?>
		<script src="<?= base_url('assets/js/schedule/employee.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='attendance/employee'): ?>
		<script src="<?= base_url('assets/vendor/flatpickr/dist/plugins/monthSelect/index.js') ?>"></script>
		<script src="<?= base_url('assets/js/attendance/employee.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='attendance/office'): ?>
		<script src="<?= base_url('assets/vendor/flatpickr/dist/plugins/monthSelect/index.js') ?>"></script>
		<script src="<?= base_url('assets/js/attendance/office.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='attendance/visitor'): ?>
		<script src="<?= base_url('assets/vendor/flatpickr/dist/plugins/monthSelect/index.js') ?>"></script>
		<script src="<?= base_url('assets/js/attendance/visitor.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='setup/duration'): ?>
		<script src="<?= base_url('assets/js/setup/duration.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='setup/shift'): ?>
		<script src="<?= base_url('assets/js/setup/shift.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='scanlog'): ?>
		<script src="<?= base_url('assets/js/scanlog/scanlog.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='scanlog/filter'): ?>
		<script src="<?= base_url('assets/js/scanlog/filter.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='import_sql'): ?>
		<script src="<?= base_url('assets/js/query.js') ?>"></script>
	<?php endif ?>
</body>
</html>