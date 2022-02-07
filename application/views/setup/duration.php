<div class="container-fluid" ng-app="setDur" ng-controller="setDur">
	<div class="row justify-content-center">
		<div class="col-xl-6">
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
							<form action="<?= base_url('saveDuration') ?>" id="durationForm" name="durationForm" method="POST">
								<input type="hidden" id="id_dur" name="id_dur" ng-model="id_dur">
								<div class="row justify-content-center">
									<div class="col">
										<div class="form-group form-label-group">
											<label for="auth_dept_id" class="small">Pilih Departement</label>
											<select name="auth_dept_id" id="auth_dept_id" ng-model="auth_dept_id" class="form-control text-center show-menu-arrow" data-header="Pilih Departement" ng-options="dept.id as dept.name for dept in depts"></select>
										</div>
									</div>
								</div>
								<div class="row justify-content-center">
									<div class="col-lg-6">
										<div class="form-group form-label-group">
											<label for="late_allowed" class="small">Diperbolehkan Terlambat</label>
											<input type="time" name="late_allowed" id="late_allowed" ng-model="late_allowed" class="form-control text-center bg-light input-time">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group form-label-group">
											<label for="out_allowed" class="small">Diperbolehkan Keluar</label>
											<input type="time" name="out_allowed" id="out_allowed" ng-model="out_allowed" class="form-control text-center bg-light input-time">
										</div>
									</div>
									<span class="text-danger px-3 notif-edit d-none">*kosongkan jika tidak ingin merubah data</span>
								</div>
							</form>
						</div>
					</div>
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
		<div class="col-xl-9">
			<div class="card border-0 shadow-sm mb-4 rounded">
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
							<button type="button" class="btn btn-primary btn-sm" ng-click="newDuration()"><i class="fas fa-fw fa-plus-circle"></i> New</button>
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
								<table class="table table-striped table-hover align-middle shadow-sm" id="dataTable" data-source="<?= base_url('setup/dt_duration') ?>">
									<thead class="thead-light">
										<tr>
											<th width="5%" class="text-center">No</th>
											<th>Departement</th>
											<th>Late Allowed</th>
											<th>Out Allowed</th>
											<th width="10%">Action</th>
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