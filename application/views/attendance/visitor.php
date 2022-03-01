<div class="container-fluid" ng-app="visTrace" ng-controller="visTrace">
	<div class="row justify-content-between">
		<div class="col">
			<div class="card border-0 border-left-primary shadow rounded mt-1 mb-5 card-show d-none">
				<div class="card-header">
					<div class="row justify-content-between">
						<div class="col-auto">
							<span class="text-primary font-weight-bold px-3">Nama : {{getName}} / PIN : {{getPIN}}</span>
						</div>
						<div class="col-auto">
						</div>
						<div class="col-auto">
							<span class="text-primary font-weight-bold px-3">Tanggal : {{getSearchDate}}</span>
						</div>
					</div>
				</div>
				<input type="hidden" ng-model="attId">
				<div class="card-body p-3">
					<div class="row justify-content-center mx-1 my-3">
						<div class="col-lg-8">
							<div class="row justify-content-between my-2">
								<div class="col-auto">
									<span class="text-primary font-weight-bold px-3">Data Riwayat Scan</span>
								</div>
								<div class="col-auto">
									<a type="button" class="btn btn-sm btn-primary shadow-sm d-inline mx-4" href="<?= base_url('historyScanVisitor') ?>" target="_blank"><i class="fas fa-fw fa-print"></i> Print</a>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col">
									<div class="table-responsive px-1">
										<table class="table table-striped table-hover align-middle shadow-sm" id="detHistoryTable" data-source="<?= base_url('attendance/att_hist_scan_vis') ?>">
											<thead class="thead-light">
												<tr>
													<th width="5%">#</th>
													<th>Scan Time</th>
													<th>Gate</th>
													<th width="10%">In / Out</th>
													<th width="10%">Photo</th>
												</tr>
											</thead>
											<tbody id="tblHistory"></tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 p-2">
							<div class="my-2 row justify-content-center">
								<div class="col-lg-8">
									<img src="<?= base_url('assets/img/undraw_profile_2.svg') ?>" alt="Visitor" class="img-fluid vis-photo" id="vis-photo">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<button type="button" class="btn btn-danger btn-rounded shadow-sm col-lg-2 col-md-3 col-6" ng-click="closeShow()"><i class="fas fa-fw fa-times-circle"></i> Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-between mt-1 mb-3">
		<div class="col-auto">
			<div class="form-group form-label-group">
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text border-0 shadow-sm bg-white text-material-blue"><i class="fas fa-fw fa-calendar-alt"> </i>&nbsp; Search </div>
					</div>
					<input type="text" name="selectDate" id="selectDate" ng-model="selectDate" ng-change="select_date()" class="form-control text-center bg-material-blue text-white selectDate border-0 shadow-sm col-5">
				</div>
			</div>
		</div>
		<div class="col-3">
			<span class="font-weight-bold h6 get-date d-none">{{getDate}}</span>
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
	<div class="row justify-content-center my-1">
		<div class="col">
			<div class="card border-0 border-bottom-info shadow mb-4 rounded card-visitor " data-source="<?= base_url('attresume_vis/') ?>">
				<div class="card-header">
					<div class="row justify-content-between">
						<div class="col-auto">
							<span class="text-info font-weight-bold px-3"><?= $title ?> Data</span>
						</div>
						<div class="col-auto">
							<button class="btn btn-info btn-rounded btn-sm" type="button" ng-click="reloadTable()">
								<i class="fas fa-fw fa-sync"></i> Reload
							</button>
						</div>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="row justify-content-between my-1">
						<div class="col-auto my-1">
							<div class="btn-group" role="group">
								<a type="button" class="btn btn-primary d-inline" href="<?= base_url('printAttendanceVis') ?>" target="_blank"><i class="fas fa-fw fa-print"></i> Print</a>
								<a type="button" class="btn btn-success d-inline" href="<?= base_url('exportCSV_vis') ?>"><i class="fas fa-fw fa-file-csv"></i> Export CSV</a>
							</div>
						</div>
						<div class="col-4 my-1">
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
					<div class="row justify-content-center my-2">
						<div class="col">
							<div class="table-responsive px-1">
								<table class="table table-striped table-hover align-middle shadow-sm" id="dataTable" data-source="<?= base_url('attendance/dt_visitor') ?>">
									<thead class="thead-light">
										<tr>
											<th width="7%">No</th>
											<th>Status</th>
											<th>Personnel</th>
											<th>First Scan</th>
											<th>Last Scan</th>
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