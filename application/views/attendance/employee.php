<div class="container-fluid" ng-app="attEmployee" ng-controller="attEmployee">
	<div class="row justify-content-between">
		<div class="col">
			<div class="card border-0 border-left-primary shadow rounded mt-1 mb-5 card-show d-none">
				<div class="card-header">
					<div class="row justify-content-between">
						<div class="col-auto">
							<span class="text-primary font-weight-bold px-3">Nama : {{getName}} / NIK : {{getNIK}}</span>
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
					<div class="row justify-content-center my-2 px-1">
						<div class="col-auto my-1">
							<button type="button" class="btn btn-primary btn-sm" ng-click="getAttYesterday()"><i class="fas fa-fw fa-chevron-left"></i> Hari Sebelum</button>
						</div>
						<div class="col-auto my-1">
							<button class="btn btn-sm btn-primary shadow-sm" ng-click="getAttToday()">
								<span class="font-weight-bold px-2">Hari Ini</span>
							</button>
						</div>
						<div class="col-auto my-1">
							<button type="button" class="btn btn-primary btn-sm" ng-click="getAttTomorrow()">Hari Sesudah <i class="fas fa-fw fa-chevron-right"></i></button>
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col">
							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
								<li class="nav-item" role="presentation">
									<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#att_summary" role="tab" aria-controls="pills-home" aria-selected="true"><i class="fas fa-fw fa-clock"></i> Summary</a>
								</li>
								<li class="nav-item" role="presentation">
									<a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#att_detail" role="tab" aria-controls="pills-profile" aria-selected="false"><i class="fas fa-fw fa-calendar-alt"></i> Detail</a>
								</li>
							</ul>
							<div class="tab-content" id="pills-tabContent">
								<div class="tab-pane fade show active" id="att_summary" role="tabpanel" aria-labelledby="pills-home-tab">
									<div class="row justify-content-center card-summary">
										<div class="col-xl-9">
											<div class="row justify-content-center mx-1 my-3">
												<div class="col">
													<button type="button" class="btn btn-primary shadow-sm">
														<i class="fas fa-fw fa-print"></i> Print
													</button>
												</div>
											</div>
											<div class="table-responsive px-1">
												<table class="table table-striped table-hover align-middle shadow-sm" id="sumTable" data-source="<?= base_url('attendance/att_sum_emp') ?>">
													<thead class="thead-light">
														<tr>
															<th>Date</th>
															<th>Status</th>
															<th>Shift</th>
															<th>First Scan</th>
															<th>Last Scan</th>
															<th>Late Duration</th>
															<th>Out Duration</th>
															<th>In Duration</th>
														</tr>
													</thead>
													<tbody></tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="att_detail" role="tabpanel" aria-labelledby="pills-profile-tab">
									<div class="row justify-content-center mx-1 card-detail">
										<div class="col">
											<div class="table-responsive px-1">
												<table class="table table-striped table-hover align-middle shadow-sm" id="detRecapTable" data-source="<?= base_url('attendance/att_det_recap_emp') ?>">
													<thead class="thead-light">
														<tr>
															<th width="5%">#</th>
															<th>Date</th>
															<th>Shift</th>
															<th>In Time</th>
															<th>Out Time</th>
															<th>First Scan</th>
															<th>Last Scan</th>
															<th>Late Duration</th>
															<th>Out Duration</th>
															<th>In Duration</th>
														</tr>
													</thead>
													<tbody></tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="row justify-content-center mx-1 my-3">
										<div class="col-lg-8">
											<div class="row justify-content-between my-2">
												<div class="col-auto">
													<span class="text-primary font-weight-bold px-3">Data Riwayat Scan</span>
												</div>
												<div class="col-auto">
													<button type="button" class="btn btn-sm btn-primary shadow-sm">
														<i class="fas fa-fw fa-print"></i> Print
													</button>
												</div>
											</div>
											<div class="row justify-content-center">
												<div class="col">
													<div class="table-responsive px-1">
														<table class="table table-striped table-hover align-middle shadow-sm" id="detHistoryTable" data-source="<?= base_url('attendance/att_hist_scan_emp') ?>">
															<thead class="thead-light">
																<tr>
																	<th width="5%">#</th>
																	<th>Scan Time</th>
																	<th>Gate</th>
																	<th>Shift</th>
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
													<img src="<?= base_url('assets/img/undraw_profile_2.svg') ?>" alt="Employee" class="img-fluid emp-photo" id="emp-photo">
												</div>
											</div>
										</div>
									</div>
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
			<span class="font-weight-bold h6 get-date d-none">{{getAttDate}}</span>
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
			<div class="card border-0 border-bottom-primary shadow mb-4 rounded card-attendance" data-source="<?= base_url('attresume_emp/') ?>">
				<div class="card-header">
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
							<div class="btn-group" role="group">
								<a type="button" class="btn btn-primary d-inline" href="<?= base_url('printAttendanceEmp') ?>" target="_blank"><i class="fas fa-fw fa-print"></i> Print</a>
								<a type="button" class="btn btn-success d-inline" href="<?= base_url('exportCSV_emp') ?>"><i class="fas fa-fw fa-file-csv"></i> Export CSV</a>
							</div>
						</div>
						<div class="col-xl-6 col-md-9 my-1">
							<div class="input-group shadow-sm">
								<div class="input-group-prepend">
									<div class="input-group-text bg-primary border-0 shadow">
										<i class="fas fa-search text-white"></i>
									</div>
								</div>
								<select class="form-control border-0 col-3 bg-light selectpicker" name="shift_filter" id="shift_filter" ng-change="getShift()" ng-model="shiftList" data-style="btn-light font-weight-bold">
									<option value="">All Shift</option>
									<option value="1">Shift 1</option>
									<option value="2">Shift 2</option>
									<option value="3">Shift 3</option>
								</select>
								<input type="text" class="form-control border-0 bg-light" id="searchInTable" placeholder="Cari Data..." ng-keyup="search()" ng-model="searchInTable">
							</div>
						</div>
					</div>
					<div class="row justify-content-center my-2">
						<div class="col">
							<div class="table-responsive px-1">
								<table class="table table-striped table-hover align-middle shadow-sm" id="dataTable" data-source="<?= base_url('attendance/dt_employee') ?>">
									<thead class="thead-light">
										<tr>
											<th width="5%">No</th>
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