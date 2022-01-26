<div class="container-fluid" ng-app="attEmployee" ng-controller="attEmployee">
	<div class="row justify-content-between pt-1 pb-3">
		<div class="col-auto"></div>
		<div class="col-auto">
			<span class="font-weight-bold h6">{{getdate}}</span>
		</div>
		<div class="col-auto">
			<button class="btn btn-sm btn-primary shadow-sm" ng-click="getToday()">
				<span class="font-weight-bold px-2">Today</span>
			</button>
			<div class="btn-group btn-group-sm mx-1 shadow-sm" role="group">
				<button type="button" class="btn btn-primary" ng-click="getYesterday()"><i class="fas fa-chevron-left"></i></button>
				<button type="button" class="btn btn-primary" ng-click="getTomorrow()"><i class="fas fa-chevron-right"></i></button>
			</div>
		</div>
	</div>
	<div class="row justify-content-center py-1">
		<div class="col">
			<div class="card border-0 shadow mb-4 rounded">
				<div class="card-header">
					<span class="text-primary font-weight-bold px-3"><?= $title ?> Data</span>
				</div>
				<div class="card-body p-3">
					<div class="row justify-content-between my-1">
						<div class="col-auto my-1">
							<div class="btn-group" role="group">
								<button type="button" class="btn btn-primary"><i class="fas fa-fw fa-print"></i></button>
								<button type="button" class="btn btn-success"><i class="fas fa-fw fa-file-csv"></i> Export CSV</button>
							</div>
						</div>
						<div class="col-6 my-1">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text bg-primary border-0 rounded-0">
										<i class="fas fa-search text-white"></i>
									</div>
								</div>
								<select class="form-control rounded-0 col-3" name="shift_filter" id="shift_filter">
									<option value="">All Shift</option>
									<option value="1">Shift 1</option>
									<option value="2">Shift 2</option>
									<option value="3">Shift 3</option>
								</select>
								<input type="text" class="form-control rounded-0" id="searchInTable" placeholder="Cari Data...">
							</div>
						</div>
					</div>
					<div class="row justify-content-center my-2">
						<div class="col">
							<div class="table-responsive">
								<table class="table table-striped table-hover align-middle shadow-sm" id="dataTable">
									<thead class="thead-light">
										<tr>
											<th width="10%">No</th>
											<th>Status</th>
											<th>Name</th>
											<th>NIK</th>
											<th>Shift</th>
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