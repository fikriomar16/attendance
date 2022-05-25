<div class="container-fluid" ng-app="accessPage" ng-controller="accessPage">
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
								<button type="button" class="btn btn-primary btn-sm" ng-click="newAcc()"><i class="fas fa-fw fa-plus-circle"></i> New Account</button>
							</div>
						</div>
						<div class="col-xl-4 col-md-6 my-1">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text bg-primary border-0">
										<i class="fas fa-search text-white"></i>
									</div>
								</div>
								<input type="text" class="form-control" id="searchInTable" placeholder="Cari Data..." ng-change="search()" ng-model="searchInTable">
							</div>
						</div>
					</div>
					<div class="row justify-content-center my-2">
						<div class="col">
							<div class="table-responsive px-1">
								<table class="table table-striped table-hover align-middle shadow-sm" id="dataTable" data-source="<?= base_url('auth/dt_auth') ?>">
									<thead class="thead-light">
										<tr>
											<th width="5%" class="text-center">No</th>
											<th class="text-center">Name</th>
											<th class="text-center">Username</th>
											<th class="text-center">Department</th>
											<th class="text-center">Active</th>
											<th class="text-center">Action</th>
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
	<div class="modal fade" id="modalManage" tabindex="-1" aria-labelledby="modalManageLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content border-0">
				<div class="modal-header bg-light shadow-sm border-0">
					<h5 class="modal-title text-primary font-weight-bold" id="modalManageLabel">{{getTitle}}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="authForm" action="<?= base_url('saveAccount') ?>" method="POST" enctype="multipart/form-data">
						<div class="row justify-content-center">
							<div class="col-md-5">
								<div class="form-group form-label-group">
									<label for="deptList" class="small font-weight-bold">Department</label>
									<select class="form-control custom-select border-0 bg-light btn-light font-weight-bold" name="deptList" id="deptList" ng-model="deptList" data-style="btn-light font-weight-bold" data-header="Pilih Department">
										<?php foreach ($lists as $list): ?>
											<option value="<?= $list->id ?>"><?= $list->name ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group form-label-group">
									<label for="name" class="small font-weight-bold">Nama</label>
									<input type="text" name="name" id="name" ng-model="name" class="form-control text-center bg-light">
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group form-label-group">
									<label for="username" class="small font-weight-bold">Username</label>
									<input type="text" name="username" id="username" ng-model="username" class="form-control text-center bg-light" ng-change="convertUser()">
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group form-label-group">
									<label for="password" class="small font-weight-bold">Password</label>
									<input type="password" name="password" id="password" ng-model="password" class="form-control text-center bg-light">
								</div>
							</div>
						</div>
						<div class="row justify-content-center my-2">
							<div class="col-auto">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="active" name="active" ng-model="active" ng-change="checkActive()" checked>
									<label class="custom-control-label" for="active">Status Akun: {{activeStatus}}</label>
								</div>
							</div>
						</div>
						<div class="row justify-content-center my-3">
							<div class="col-auto">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="is_spv" name="is_spv" ng-model="is_spv" ng-change="checkSpv()">
									<label class="custom-control-label" for="is_spv">Tipe Akun: {{spvStatus}}</label>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer border-0">
					<button type="button" class="btn btn-sm btn-rounded btn-primary shadow-sm m-2" ng-click="saveAcc()"><i class="fas fa-fw fa-save"></i> Simpan Data</button>
					<button type="button" class="btn btn-sm btn-rounded btn-secondary shadow-sm m-2" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i> Batal</button>
				</div>
			</div>
		</div>
	</div>
</div>