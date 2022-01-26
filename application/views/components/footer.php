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

	<!-- Functional -->
	<?php $p = uri_string(); ?>
	<?php if ($p=='dashboard'||$p==''): ?>
		<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
	<?php endif ?>
	<?php if ($p=='late'): ?>
		<script src="<?= base_url('assets/js/late.js') ?>"></script>
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

	<!-- Additional -->
	<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
</body>
</html>