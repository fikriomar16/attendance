<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="ie=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/sb-admin-2.min.css') ?>"/>
	<title><?= $title ?? '' ?></title>
	<style type="text/css">
		body {
			font-size: 0.75rem;
			margin: 0;
		}
	</style>
</head>
<body>
	<div class="container-fluid p-0 m-0">
		<div class="row justify-content-center">
			<div class="col">
				<h4 class="text-dark font-weight-bold text-center"><?= $title ?? '' ?></h3>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col">
				<div class="row justify-content-between">
					<div class="col-auto">
						<p><span class="font-weight-bold text-dark">Bulan : </span><?= $month ?></p>
					</div>
					<div class="col-auto">
						<p><span class="font-weight-bold text-dark">Nama : </span><?= $name ?> - <span class="font-weight-bold text-dark">NIK : </span><?= $nik ?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-center small">
			<div class="col">
				<div class="table-responsive">
					<table class="table table-sm table-bordered align-middle shadow-sm">
						<thead class="thead-light">
							<tr>
								<th width="5%">No</th>
								<th>Date</th>
								<th>Shift</th>
								<th>First Scan</th>
								<th>Last Scan</th>
								<th>Late Duration</th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 0; ?>
							<?php foreach ($lists as $list): ?>
								<?php $no++; ?>
								<tr>
									<td><?= $no; ?></td>
									<td><?= $list->date; ?></td>
									<td><?= $list->shift; ?></td>
									<td><?= $list->first_scan; ?></td>
									<td><?= $list->last_scan; ?></td>
									<td><?= $list->late_duration; ?></td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</body>
</html>