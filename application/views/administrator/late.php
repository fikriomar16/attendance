<div class="container-fluid" ng-app="lateApp" ng-controller="lateController">
	<div class="row justify-content-center">
		<div class="col-xl-9 col-lg col-md">
			<div class="card border-0 border-bottom-warning shadow mb-4 rounded">
				<div class="card-header border-0 shadow-sm h-100 px-4">
					<div class="row justify-content-between my-0 pt-2">
						<div class="col-auto">
							<p class="text-primary font-weight-bold h6">Late Notice</p>
						</div>
						<div class="col-auto">
							<button class="btn btn-primary btn-rounded btn-sm" type="button" ng-click="reloadTable()">
								<i class="fas fa-fw fa-sync"></i> Reload
							</button>
						</div>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="row justify-content-between my-3">
						<div class="col-auto my-1">
						</div>
						<div class="col-lg-5 my-1">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text bg-primary border-0">
										<i class="fas fa-search text-white"></i>
									</div>
								</div>
								<input type="text" class="form-control border-0 bg-light" id="searchInTable" placeholder="Cari Data..." ng-keyup="search()" ng-model="searchInTable">
							</div>
						</div>
					</div>
					<div class="table-responsive mx-1">
						<table class="table table-striped table-hover align-middle shadow-sm" id="dataTable" data-source="<?= base_url('dt_late') ?>">
							<thead class="thead-light">
								<tr>
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