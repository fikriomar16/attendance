<div class="container-fluid" ng-app="outApp" ng-controller="outController">
	<div class="row justify-content-center">
		<div class="col-xl-12">
			<div class="card border-0 border-bottom-warning shadow mb-4 rounded">
				<div class="card-header border-0 shadow-sm h-100 px-4">
					<div class="row justify-content-between">
						<div class="col-auto my-auto">
							<p class="text-primary font-weight-bold h6">Out Notice</p>
						</div>
						<div class="col-auto my-auto">
							<button class="btn btn-primary btn-rounded btn-sm" type="button" ng-click="reloadTable()">
								<i class="fas fa-fw fa-sync"></i> Reload Table
							</button>
						</div>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="row justify-content-center my-2">
						<div class="col my-1">
							<div class="table-responsive mx-1">
								<table class="table table-sm table-striped table-hover align-middle shadow-sm" id="dataTable" data-source="<?= base_url('dt_out') ?>">
									<thead class="thead-light">
										<tr>
											<th class="text-center">NIK</th>
											<th class="text-center">Name</th>
											<th class="text-center">Shift</th>
											<th class="text-center">Departement</th>
											<th class="text-center">Status</th>
										</tr>
									</thead>
									<tbody id="tbodyDataTable"></tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="row justify-content-between">
						<div class="col my-2">
							<div class="alert alert-primary alert-loading text-center font-weight-bold d-none" role="alert">
								<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>&nbsp; Sedang Memuat Data
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>