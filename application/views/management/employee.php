<div class="container-fluid" ng-app="empMan" ng-controller="empMan">
	<div class="row justify-content-center">
		<div class="col">
			<div class="card border-0 shadow mb-4 rounded">
				<div class="card-header border-0">
					<div class="row justify-content-between">
						<div class="col-auto">
							<span class="text-primary font-weight-bold px-3">Employee Data</span>
						</div>
						<div class="col-auto">
							<button class="btn btn-primary btn-rounded btn-sm" type="button" ng-click="reloadTable()">
								<i class="fas fa-fw fa-sync"></i> Reload
							</button>
						</div>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="row justify-content-between my-1 px-1">
						<div class="col-auto my-1">
							<div class="btn-group btn-group-sm" role="group">
								<button type="button" class="btn btn-primary"><i class="fas fa-fw fa-plus-circle"></i> New</button>
								<button type="button" class="btn btn-success"><i class="fas fa-fw fa-upload"></i> Import CSV</button>
							</div>
						</div>
						<div class="col-xl-4 col-md-6 my-1">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text bg-primary border-0">
										<i class="fas fa-search text-white"></i>
									</div>
								</div>
								<input type="text" class="form-control" id="searchInTable" placeholder="Cari Data..." ng-keyup="search()" ng-model="searchInTable">
							</div>
						</div>
					</div>
					<div class="row justify-content-center my-2">
						<div class="col">
							<div class="table-responsive px-1">
								<table class="table table-striped table-hover align-middle shadow-sm" id="dataTable" data-source="<?= base_url('management/dt_employee') ?>">
									<thead class="thead-light">
										<tr>
											<th width="5%" class="text-center">
												<div class="custom-control custom-checkbox">
													<input type="checkbox" id="chk_boxes" class="custom-control-input">
													<label class="custom-control-label" for="chk_boxes"></label>
												</div>
											</th>
											<th>Nama</th>
											<th>NIK</th>
											<th>Departement</th>
											<th width="5%">Whitelist</th>
											<th width="7.5%">Action</th>
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