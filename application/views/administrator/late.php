<div class="container-fluid" ng-app="lateApp" ng-controller="lateController">
	<div class="row justify-content-center">
		<div class="col-xl-9 col-lg col-md">
			<div class="card border-0 shadow mb-4 rounded">
				<div class="card-header border-0 shadow-sm h-100 px-4">
				</div>
				<div class="card-body p-3">
					<div class="row justify-content-between my-3">
						<div class="col-auto my-1">
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
					<div class="table-responsive">
						<table class="table table-striped table-hover align-middle" id="dataTable">
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