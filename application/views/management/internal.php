<div class="container-fluid" ng-app="intMan" ng-controller="intMan">
	<div class="row justify-content-center">
		<div class="col">
			<div class="card border-0 shadow mb-4 rounded">
				<div class="card-header border-0">
					<span class="text-primary font-weight-bold px-3">Internal Data</span>
				</div>
				<div class="card-body p-3">
					<div class="row justify-content-between my-1">
						<div class="col-auto my-1">
							<div class="btn-group btn-group-sm" role="group">
								<button type="button" class="btn btn-primary"><i class="fas fa-fw fa-plus-circle"></i> New</button>
								<button type="button" class="btn btn-success"><i class="fas fa-fw fa-upload"></i> Import CSV</button>
							</div>
						</div>
						<div class="col-4 my-1">
							<div class="input-group">
								<div class="input-group-prepend">
									<div class="input-group-text bg-primary border-0">
										<i class="fas fa-search text-white"></i>
									</div>
								</div>
								<input type="text" class="form-control" id="searchInTable" placeholder="Cari Data...">
							</div>
						</div>
					</div>
					<div class="row justify-content-center my-2">
						<div class="col">
							<div class="table-responsive">
								<table width="100%" class="table table-striped table-hover align-middle shadow-sm" id="dataTable">
									<thead class="thead-light">
										<tr>
											<th width="5%" class="text-center">
												<div class="custom-control custom-checkbox">
													<input type="checkbox" id="chk_boxes" class="custom-control-input">
													<label class="custom-control-label" for="chk_boxes"></label>
												</div>
											</th>
											<th>Nama</th>
											<th>NIK</th>
											<th>Departement</th>
											<th>Wihtelist</th>
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