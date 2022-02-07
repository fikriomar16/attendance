<div class="container-fluid" ng-app="ScanLog" ng-controller="ScanLog">
	<div class="row justify-content-center">
		<div class="col">
			<div class="card border-0 shadow-sm mb-4 rounded">
				<div class="card-header border-0">
					<div class="row justify-content-between">
						<div class="col-auto">
							<span class="text-primary font-weight-bold px-3"><?= $title ?? 'Data Scan Log' ?></span>
						</div>
						<div class="col-auto">
							<button class="btn btn-primary btn-rounded btn-sm" type="button" ng-click="reloadTable()">
								<i class="fas fa-fw fa-sync"></i> Reload
							</button>
						</div>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="row justify-content-center my-1">
						<div class="col">
							<div class="table-responsive px-1">
								<table class="table table-striped table-hover align-middle shadow-sm" id="dataTable" data-source="<?= base_url('scanlog/dt_scanlog') ?>">
									<thead class="thead-light">
										<tr>
											<th>Dept. Name</th>
											<th>Gate</th>
											<th>Scan Time</th>
											<th>Name</th>
											<th>PIN</th>
											<th>Verify Mode</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>