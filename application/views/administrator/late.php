<div class="container-fluid" ng-app="lateApp" ng-controller="lateController">
	<div class="row justify-content-center">
		<div class="col-xl-9 col-lg col-md">
			<div class="card border-0 shadow mb-4 rounded">
				<div class="card-header border-0 shadow-sm h-100 px-4">
					<div class="row justify-content-between my-0 pt-2">
						<div class="col-auto my-1">
							<button type="button" class="btn btn-primary btn-sm shadow-sm" ng-click="tvview()">
								<i class="fas fa-desktop"></i> &nbsp; Smart TV View&nbsp;
							</button>
							<button type="button" class="btn btn-info btn-sm shadow-sm" ng-click="tvsplit()">
								<i class="fas fa-desktop"></i> &nbsp; Smart TV Splitted&nbsp;
							</button>
						</div>
						<div class="col-lg-5 my-1">
							<div class="input-group shadow-sm">
								<div class="input-group-prepend">
									<div class="input-group-text bg-primary border-0 shadow-sm">
										<i class="fas fa-search text-white"></i>
									</div>
								</div>
								<input type="text" class="form-control border-0" id="searchInTable" placeholder="Cari Data...">
							</div>
						</div>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="table-responsive">
						<table width="100%" class="table table-striped table-hover align-middle" id="dataTable">
							<thead class="thead-light">
								<tr>
									<th>Name</th>
									<th width="20%">NIK</th>
									<th width="20%">Departement</th>
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