<div class="container-fluid" ng-app="dashboardApp" ng-controller="dashboardController">
	<div class="row justify-content-center">
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1"> Employee</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">0 </div>
						</div>
						<div class="col-auto">
							<i class="fas fa-fingerprint fa-2x text-success"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-primary shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1"> Internal</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
						</div>
						<div class="col-auto">
							<i class="fas fa-id-card fa-2x text-primary"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-md-6 mb-4">
			<div class="card border-left-info shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-info text-uppercase mb-1"> Visitor </div>
							<div class="row no-gutters align-items-center">
								<div class="col-auto">
									<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">6%</div>
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
	<div class="row justify-content-center">
		<div class="col-xl-9 col-lg col-md">
			<div class="card border-0 shadow mb-4 rounded">
				<div class="card-header border-0 shadow-sm h-100 px-4">
					<div class="row justify-content-between my-0 pt-2">
						<div class="col-auto">
							<p class="text-primary font-weight-bold h6">Last Scan Data</p>
						</div>
						<div class="col-auto">
							<button class="btn btn-primary btn-rounded btn-sm" type="button" ng-click="getDashboard()">
								<i class="fas fa-fw fa-sync"></i> Reload
							</button>
						</div>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="table-responsive">
						<table class="table table-striped table-hover align-middle" id="dataTable">
							<thead class="thead-light">
								<tr>
									<th width="20%">Scan Date</th>
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