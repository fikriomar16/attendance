<div class="container-fluid" ng-app="setDur" ng-controller="setDur">
	<div class="row justify-content-center">
		<div class="col-xl-12">
			<div class="alert alert-primary alert-loading text-center font-weight-bold d-none" role="alert">
				<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div> Sedang Memuat Data
			</div>
			<div class="card border-0 shadow mb-4 rounded card-new d-none">
				<div class="card-header border-0">
					<span class="text-primary font-weight-bold px-3">{{getTitle}}</span>
				</div>
				<div class="card-body p-3">
					<div class="row justify-content-center my-1">
						<div class="col">
							<form action="<?= base_url('saveShift') ?>" id="shiftForm" name="shiftForm" method="POST">
								<input type="hidden" id="id_shift" name="id_shift" ng-model="id_shift">
								<div class="row justify-content-center">
									<div class="col-xl-1 col-lg-2 col-md-2 col-4">
										<div class="form-group form-label-group">
											<label for="shift_code" class="small">Shift Code</label>
											<input type="text" name="shift_code" id="shift_code" ng-model="shift_code" class="form-control text-center bg-light">
										</div>
									</div>
									<div class="col-md-3 col-8">
										<div class="form-group form-label-group">
											<label for="work_time" class="small">Work Time</label>
											<input type="text" name="work_time" id="work_time" ng-model="work_time" class="form-control text-center bg-light input-time">
										</div>
									</div>
									<div class="col-md-3 col-6">
										<div class="form-group form-label-group">
											<label for="work_start" class="small">Work Start</label>
											<input type="text" name="work_start" id="work_start" ng-model="work_start" class="form-control text-center bg-light input-time">
										</div>
									</div>
									<div class="col-md-3 col-6">
										<div class="form-group form-label-group">
											<label for="work_end" class="small">Work End</label>
											<input type="text" name="work_end" id="work_end" ng-model="work_end" class="form-control text-center bg-light input-time">
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					<form action="<?= base_url('importDWS') ?>" enctype="multipart/form-data" id="importForm" name="importForm" class="d-none importForm">
						<input type="file" name="import_dws" id="import_dws" class="import_dws" accept=".csv,.xls,.xlsx" onchange="angular.element(this).scope().doImportDWS(this.files)">
					</form>
				</div>
				<div class="card-footer border-0 shadow-sm">
					<div class="row justify-content-between">
						<div class="col-auto">
							<button type="button" class="btn btn-primary btn-rounded shadow-sm" ng-click="saveDuration()"><i class="fas fa-fw fa-save"></i> Simpan Data</button>
						</div>
						<div class="col-auto">
							<button type="button" class="btn btn-danger btn-rounded shadow-sm" ng-click="closeAdd()"><i class="fas fa-fw fa-times-circle"></i> Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-xl-12">
			<div class="card border-0 border-bottom-primary shadow mb-4 rounded">
				<div class="card-header border-0">
					<div class="row justify-content-between">
						<div class="col-auto">
							<span class="text-primary font-weight-bold px-3"><?= $title ?></span>
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
								<button type="button" class="btn btn-primary btn-sm" ng-click="newDuration()"><i class="fas fa-fw fa-plus-circle"></i> New Shift Setup</button>
								<button type="button" class="btn btn-success" ng-click="importButton()"><i class="fas fa-fw fa-upload"></i> Import from Excel</button>
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
								<table class="table table-striped table-hover align-middle shadow-sm" id="dataTable" data-source="<?= base_url('setup/dt_shift') ?>">
									<thead class="thead-light">
										<tr>
											<th width="5%" class="text-center">No</th>
											<th class="text-center">Shift Code</th>
											<th class="text-center">Work Time</th>
											<th class="text-center">Work Start</th>
											<th class="text-center">Work End</th>
											<th class="text-center">Out Allowed</th>
											<th width="10%" class="text-center">Action</th>
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
	<div class="modal fade" id="modalImport" data-backdrop="static" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
			<div class="modal-content border-0">
				<div class="modal-header bg-light shadow-sm border-0">
					<h5 class="modal-title text-primary font-weight-bold" id="modalImportLabel">Import Data</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover align-middle shadow-sm">
							<thead class="thead-light">
								<tr>
									<th>CODE SHIFT</th>
									<th>JAM KERJA</th>
									<th>WORK START</th>
									<th>WORK END</th>
								</tr>
							</thead>
							<tbody id="table-import"></tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-rounded btn-primary shadow-sm m-2" ng-click="importFromModal()"><i class="fas fa-fw fa-save"></i> Import Data</button>
					<button type="button" class="btn btn-sm btn-rounded btn-secondary shadow-sm m-2" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i> Batal</button>
				</div>
			</div>
		</div>
	</div>
</div>