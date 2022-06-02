<div class="container-fluid" ng-app="dashboardApp" ng-controller="dashboardController">
	<?php ($this->session->userdata('user')->is_spv == 1) ? $hide="" : $hide="d-none"; ?>
	<?php ($this->session->userdata('user')->is_spv == 1) ? $show="d-none" : $show=""; ?>
	<div class="row justify-content-end mb-2 mt-0">
		<div class="col-auto">
			<button class="btn btn-primary btn-rounded btn-sm text-xs btn-load shadow-sm" type="button" ng-click="getCount()">
				<i class="fas fa-fw fa-sync icon-load"></i> <span class="text-load"> Reload Data </span>
			</button>
		</div>
	</div>
	<?php
	if (substr($this->session->userdata('user')->dept_name,0,4) == "Prod") {
		$color = 'success';
		$icon = 'fingerprint';
	} else {
		$color = 'primary';
		$icon = 'building';
	}
	?>
	<div class="row justify-content-center my-3 <?= $show ?>">
		<div class="col-lg-6">
			<div class="card border-left-<?= $color ?> shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-<?= $color ?> text-uppercase mb-1">
								Kehadiran <?= $this->session->userdata('user')->dept_name ?> Hari Ini 
							</div>
							<div class="row no-gutters align-items-center p-3">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800 show-count d-none">{{countCurrentDept}} / {{countCurrentDeptTotal}}</div>
								</div>
								<div class="col">
									<div class="progress progress-bar-animated progress-bar-striped mr-2">
										<div class="progress-bar bg-<?= $color ?>" id="progress-dws" role="progressbar" style="width: 0%"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-<?= $icon ?> fa-2x text-<?= $color ?>"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center <?= $hide ?>">
		<div class="col-md-4 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1"> Kehadiran Employee Hari Ini</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800 show-count d-none">{{countEmployee}} / {{countEmployeeTotal}}</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-fingerprint fa-2x text-success"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-4">
			<div class="card border-left-primary shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1"> Kehadiran Office Hari Ini </div>
							<div class="row no-gutters align-items-center">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800 show-count d-none">{{countOffice}} / {{countOfficeTotal}}</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-building fa-2x text-primary"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4 mb-4">
			<div class="card border-left-info shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1"> Kehadiran Visitor Hari Ini </div>
							<div class="row no-gutters align-items-center">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800 show-count d-none">{{countVisitor}}</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-credit-card fa-2x text-info"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center <?= $hide ?>">
		<div class="col-lg-4 col-md-6 container mb-2">
			<ul class="list-group shadow" id="attProd">
				<li class="list-group-item">
					<div class="row">
						<div class="col-auto w-100">
							<div class="row justify-content-between my-0">
								<div class="col-auto">
									<div class="text-xs font-weight-bold text-dark text-uppercase">
										<i class="fas fa-fingerprint fa-fw text-success"></i>&nbsp; Prod. Minyak
									</div>
								</div>
								<div class="col-auto">
									<div class="h6 font-weight-bold text-gray-800 show-count d-none mb-0">{{countEmployeeMinyak}} / {{countEmployeeMinyakTotal}}</div>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row">
						<div class="col-auto w-100">
							<div class="row justify-content-between my-0">
								<div class="col-auto">
									<div class="text-xs font-weight-bold text-dark text-uppercase">
										<i class="fas fa-fingerprint fa-fw text-success"></i>&nbsp; Prod. Sayur
									</div>
								</div>
								<div class="col-auto">
									<div class="h6 font-weight-bold text-gray-800 show-count d-none mb-0">{{countEmployeeSayur}} / {{countEmployeeSayurTotal}}</div>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row">
						<div class="col-auto w-100">
							<div class="row justify-content-between my-0">
								<div class="col-auto">
									<div class="text-xs font-weight-bold text-dark text-uppercase">
										<i class="fas fa-fingerprint fa-fw text-success"></i>&nbsp; Prod. Bumbu
									</div>
								</div>
								<div class="col-auto">
									<div class="h6 font-weight-bold text-gray-800 show-count d-none mb-0">{{countEmployeeBumbu}} / {{countEmployeeBumbuTotal}}</div>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row">
						<div class="col-auto w-100">
							<div class="row justify-content-between my-0">
								<div class="col-auto">
									<div class="text-xs font-weight-bold text-dark text-uppercase">
										<i class="fas fa-fingerprint fa-fw text-success"></i>&nbsp; Prod. Bawang Goreng
									</div>
								</div>
								<div class="col-auto">
									<div class="h6 font-weight-bold text-gray-800 show-count d-none mb-0">{{countEmployeeBawang}} / {{countEmployeeBawangTotal}}</div>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="col-lg-4 col-md-6 container mb-2">
			<ul class="list-group shadow" id="attOff">
				<li class="list-group-item">
					<div class="row">
						<div class="col-auto w-100">
							<div class="row justify-content-between my-0">
								<div class="col-auto">
									<div class="text-xs font-weight-bold text-dark text-uppercase">
										<i class="fas fa-building fa-fw text-primary"></i>&nbsp; Accounting
									</div>
								</div>
								<div class="col-auto">
									<div class="h6 font-weight-bold text-gray-800 show-count d-none mb-0">{{countOfficeAccounting}} / {{countOfficeAccountingTotal}} </div>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row">
						<div class="col-auto w-100">
							<div class="row justify-content-between my-0">
								<div class="col-auto">
									<div class="text-xs font-weight-bold text-dark text-uppercase">
										<i class="fas fa-building fa-fw text-primary"></i>&nbsp; Human Resources
									</div>
								</div>
								<div class="col-auto">
									<div class="h6 font-weight-bold text-gray-800 show-count d-none mb-0">{{countOfficeHR}} / {{countOfficeHRTotal}}</div>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row">
						<div class="col-auto w-100">
							<div class="row justify-content-between my-0">
								<div class="col-auto">
									<div class="text-xs font-weight-bold text-dark text-uppercase">
										<i class="fas fa-building fa-fw text-primary"></i>&nbsp; Quality Control
									</div>
								</div>
								<div class="col-auto">
									<div class="h6 font-weight-bold text-gray-800 show-count d-none mb-0">{{countOfficeQC}} / {{countOfficeQCTotal}}</div>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row">
						<div class="col-auto w-100">
							<div class="row justify-content-between my-0">
								<div class="col-auto">
									<div class="text-xs font-weight-bold text-dark text-uppercase">
										<i class="fas fa-building fa-fw text-primary"></i>&nbsp; PPIC
									</div>
								</div>
								<div class="col-auto">
									<div class="h6 font-weight-bold text-gray-800 show-count d-none mb-0">{{countOfficePPIC}} / {{countOfficePPICTotal}}</div>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row">
						<div class="col-auto w-100">
							<div class="row justify-content-between my-0">
								<div class="col-auto">
									<div class="text-xs font-weight-bold text-dark text-uppercase">
										<i class="fas fa-building fa-fw text-primary"></i>&nbsp; Technic
									</div>
								</div>
								<div class="col-auto">
									<div class="h6 font-weight-bold text-gray-800 show-count d-none mb-0">{{countOfficeTechnic}} / {{countOfficeTechnicTotal}}</div>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row">
						<div class="col-auto w-100">
							<div class="row justify-content-between my-0">
								<div class="col-auto">
									<div class="text-xs font-weight-bold text-dark text-uppercase">
										<i class="fas fa-building fa-fw text-primary"></i>&nbsp; Warehouse
									</div>
								</div>
								<div class="col-auto">
									<div class="h6 font-weight-bold text-gray-800 show-count d-none mb-0">{{countOfficeWarehouse}} / {{countOfficeWarehouseTotal}}</div>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div class="row">
						<div class="col-auto w-100">
							<div class="row justify-content-between my-0">
								<div class="col-auto">
									<div class="text-xs font-weight-bold text-dark text-uppercase">
										<i class="fas fa-building fa-fw text-primary"></i>&nbsp; Purchasing
									</div>
								</div>
								<div class="col-auto">
									<div class="h6 font-weight-bold text-gray-800 show-count d-none mb-0">{{countOfficePurchasing}} / {{countOfficePurchasingTotal}}</div>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<div class="col-md-4"></div>
	</div>
	<div class="row justify-content-center my-4 <?= $hide ?>">
		<div class="col-auto">
			<button class="btn btn-primary btn-rounded btn-sm text-xs btn-scan shadow-sm" type="button" ng-click="dataScan('show')">
				<i class="fas fa-fw fa-list-alt"></i> <span class=""> Tampilkan Data Scan</span>
			</button>
			<button class="btn btn-danger btn-rounded btn-sm text-xs btn-scan-hide shadow-sm d-none" type="button" ng-click="dataScan('hide')">
				<i class="fas fa-fw fa-times-circle"></i> <span class=""> Sembunyikan Data Scan</span>
			</button>
		</div>
	</div>
	<div class="row justify-content-center table-scan d-none" id="table-scan">
		<div class="col-xl-12">
			<div class="card border-0 border-bottom-primary shadow mb-4 rounded">
				<div class="card-header border-0 shadow-sm h-100 px-4">
					<div class="row justify-content-between my-0 pt-2">
						<div class="col-auto">
							<p class="text-primary font-weight-bold h6">Data Scan Terakhir pada Hari Ini</p>
						</div>
						<div class="col-auto">
							<button class="btn btn-primary btn-rounded btn-sm" type="button" ng-click="reloadTable()">
								<i class="fas fa-fw fa-sync"></i> Reload Table
							</button>
						</div>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="table-responsive mx-1">
						<table class="table table-sm table-striped table-hover align-middle shadow-sm" id="dataTable" data-source="<?= base_url('merged_db') ?>">
							<thead class="thead-light">
								<tr>
									<th width="15%">Scan Time</th>
									<th>User</th>
									<th width="15%">User Type</th>
									<th width="10%">In / Out</th>
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