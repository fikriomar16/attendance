				<?= (ENVIRONMENT === 'development') ?  '<div class="text-center text-primary font-weight-bold">Page Rendered in {elapsed_time} seconds.</div>' : '' ?>
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
	<script type="text/javascript">
		$(document).ready(() => {
			$('select').selectpicker();
		});
	</script>
	<script src="<?= base_url('assets/vendor/flatpickr/dist/flatpickr.min.js') ?>"></script>
	<!-- Functional -->
	<?php $p = uri_string(); ?>
	<?php if ($p=='dashboard'||$p==''): ?>
		<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='late'): ?>
		<script src="<?= base_url('assets/js/late.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='schedule/employee'): ?>
		<script src="<?= base_url('assets/js/schedule/employee.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='schedule/internal'): ?>
		<script src="<?= base_url('assets/js/schedule/internal.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='attendance/employee'): ?>
		<script src="<?= base_url('assets/js/attendance/employee.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='attendance/internal'): ?>
		<script src="<?= base_url('assets/js/attendance/internal.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='attendance/visitor'): ?>
		<script src="<?= base_url('assets/js/attendance/visitor.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='management/employee'): ?>
		<script src="<?= base_url('assets/js/management/employee.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='management/internal'): ?>
		<script src="<?= base_url('assets/js/management/internal.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='management/visitor'): ?>
		<script src="<?= base_url('assets/js/management/visitor.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='management/departement'): ?>
		<script src="<?= base_url('assets/js/management/departement.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='setup/gate'): ?>
		<script src="<?= base_url('assets/js/setup/gate.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='setup/duration'): ?>
		<script src="<?= base_url('assets/js/setup/duration.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='setup/card'): ?>
		<script src="<?= base_url('assets/js/setup/card.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='setup/menu'): ?>
		<script src="<?= base_url('assets/js/setup/menu.js') ?>"></script>
	<?php endif ?>
</body>
</html>