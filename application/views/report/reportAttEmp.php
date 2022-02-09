<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="ie=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/sb-admin-2.min.css') ?>"/>
	<link rel="icon" href="<?= base_url('assets/img/undraw_posting_photo.svg') ?>"/>
	<title><?= $title ?? '' ?></title>
</head>
<body>
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col"></div>
		</div>
		<div class="row justify-content-center">
			<div class="col">
				<div class="table-responsive">
					<table class="table table-striped table-bordered align-middle shadow-sm">
						<thead class="thead-light">
							<tr>
								<th width="5%">No</th>
								<th>NIK</th>
								<th>Name</th>
								<th>Departement</th>
								<th>Shift</th>
								<th>First Scan</th>
								<th>Last Scan</th>
								<th>Late Duration</th>
								<th>Out Duration</th>
								<th>In Duration</th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 0; ?>
							<?php foreach ($lists as $list): ?>
								<?php $no++; ?>
								<tr>
									<td><?= $no; ?></td>
									<td><?= $list->pin; ?></td>
									<td><?= $list->name; ?></td>
									<td><?= $list->dept_name; ?></td>
									<td><?= $list->shift; ?></td>
									<td><?= $list->in_scan; ?></td>
									<td><?= $list->out_scan; ?></td>
									<td><?= $list->late_duration; ?></td>
									<td><?= $list->out_duration; ?></td>
									<td><?= $list->in_duration; ?></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/sb-admin-2.min.js') ?>"></script>
</body>
</html>