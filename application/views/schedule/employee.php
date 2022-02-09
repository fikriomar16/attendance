<div class="container-fluid" ng-app="schEmp" ng-controller="schEmp">
	<div class="row justify-content-center">
		<div class="col-xl-6">
			<div class="alert alert-primary alert-loading text-center font-weight-bold d-none" role="alert">
				<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div> Sedang Memuat Data
			</div>
			<div class="card border-0 border-bottom-primary shadow mb-4 rounded card-new d-none">
				<div class="card-header border-0 shadow-sm">
					<span class="text-primary font-weight-bold px-3">{{getTitle}}</span>
				</div>
				<div class="card-body p-3">
					<form action="<?= base_url('saveSchedule') ?>" id="scheduleForm" name="scheduleForm">
						<div class="row justify-content-center">
							<div class="col-md-9 select-emp">
								<div class="form-group form-label-group">
									<label for="nik" class="small">Pilih Karyawan</label>
									<select name="nik" id="nik" ng-model="nik" class="form-control text-center show-menu-arrow" data-header="Pilih Karyawan" data-live-search="true">
										<option ng-repeat="emp in emps" value="{{emp.pin}}">{{emp.pin}} - {{emp.name}}</option>
									</select>
								</div>
							</div>
							<div class="col-md-9 my-auto info-emp text-center d-none">
								<span class="text-primary font-weight-bold">{{empNik}} - {{empName}}</span>
							</div>
							<div class="col-md-3">
								<div class="form-group form-label-group">
									<label for="shift" class="small">Pilih Shift</label>
									<select name="shift" id="shift" ng-model="shift" class="form-control text-center selectpicker show-menu-arrow" data-header="Pilih Shift">
										<option value="1">Shift 1</option>
										<option value="2">Shift 2</option>
										<option value="3">Shift 3</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row justify-content-center">
							<div class="col-lg-6">
								<div class="form-group form-label-group">
									<label for="masuk" class="small">Waktu Masuk</label>
									<input type="text" name="masuk" id="masuk" ng-model="masuk" class="form-control text-center bg-light input-time">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group form-label-group">
									<label for="pulang" class="small">Waktu Pulang</label>
									<input type="text" name="pulang" id="pulang" ng-model="pulang" class="form-control text-center bg-light input-time">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="card-footer border-0 shadow-sm">
					<div class="row justify-content-between">
						<div class="col-auto">
							<button type="button" class="bt btn-sm btn-primary btn-rounded shadow-sm" ng-click="saveSchedule()"><i class="fas fa-fw fa-save"></i> Simpan Data</button>
						</div>
						<div class="col-auto">
							<button type="button" class="btn btn-sm btn-danger btn-rounded shadow-sm" ng-click="closeAdd()"><i class="fas fa-fw fa-times-circle"></i> Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col">
			<div class="card border-0 border-left-primary shadow mb-4 rounded card-schedule d-none" data-source="<?= base_url('getresume_emp') ?>/">
				<div class="card-header border-0 shadow-sm">
					<div class="row justify-content-between">
						<div class="col-auto">
							<span class="text-primary font-weight-bold px-3">Nama : {{getSchName}} / NIK : {{getNIK}}</span>
						</div>
						<div class="col-auto">
							<span class="text-primary font-weight-bold px-3">Tanggal : {{getSchDate}}</span>
						</div>
					</div>
				</div>
				<div class="card-body p-3">
					<input type="hidden" ng-model="schId">
					<div class="row justify-content-center my-1 px-1">
						<div class="col-auto my-1">
							<button type="button" class="btn btn-primary btn-sm" ng-click="getYesterday()"><i class="fas fa-fw fa-chevron-left"></i> Hari Sebelum</button>
						</div>
						<div class="col-auto my-1">
							<button class="btn btn-sm btn-primary shadow-sm" ng-click="getToday()">
								<span class="font-weight-bold px-2">Hari Ini</span>
							</button>
						</div>
						<div class="col-auto my-1">
							<button type="button" class="btn btn-primary btn-sm" ng-click="getTomorrow()">Hari Sesudah <i class="fas fa-fw fa-chevron-right"></i></button>
						</div>
					</div>
					<div class="row justify-content-center my-2">
						<div class="col">
							<div class="table-responsive px-1">
								<table class="table table-striped table-hover align-middle shadow-sm" id="schTable" data-source="<?= base_url('schedule/sch_employee') ?>">
									<thead class="thead-light">
										<tr>
											<th>Hari</th>
											<th>Tanggal</th>
											<th>Shift</th>
											<th>Masuk</th>
											<th>Pulang</th>
											<th width="5%">Action</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer border-0">
					<button type="button" class="btn btn-danger btn-sm btn-rounded shadow-sm" ng-click="closeSch()"><i class="fas fa-fw fa-times-circle"></i> Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col">
			<div class="card border-0 border-bottom-primary shadow mb-4 rounded">
				<div class="card-header border-0">
					<div class="row justify-content-between">
						<div class="col-auto">
							<span class="text-primary font-weight-bold px-3"><?= $title ?> Data</span>
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
								<button type="button" class="btn btn-primary" ng-click="newSchedule()"><i class="fas fa-fw fa-plus-circle"></i> New</button>
								<button type="button" class="btn btn-success" ng-click="csvButton()"><i class="fas fa-fw fa-upload"></i> Import CSV</button>
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
								<table class="table table-striped table-hover align-middle shadow-sm" id="dataTable" data-source="<?= base_url('schedule/dt_employee') ?>">
									<thead class="thead-light">
										<tr>
											<th width="5%" class="text-center">No </th>
											<th>Name</th>
											<th>NIK</th>
											<th>Departement</th>
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