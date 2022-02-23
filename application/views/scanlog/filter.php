<div class="container-fluid" ng-app="ScanLog" ng-controller="ScanLog">
	<div class="row justify-content-center">
		<div class="col">
			<div class="card border-0 border-bottom-primary shadow-sm mb-4 rounded">
				<div class="card-header border-0">
					<div class="row justify-content-between">
						<div class="col-auto">
							<span class="text-primary font-weight-bold px-3"><?= $title ?? 'Data Scan Log' ?></span>
						</div>
						<div class="col-auto">
							<button class="btn btn-primary btn-rounded btn-sm" type="button" ng-click="reloadTable()">
								<i class="fas fa-fw fa-sync"></i> Reload
							</button>
						</div>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="alert alert-primary alert-loading text-center font-weight-bold d-none" role="alert">
						<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div> Sedang Memuat Data
					</div>
					<form action="<?= base_url('scanlog/getFilterData') ?>" id="formFilter" class="d-inline">
						<div class="row justify-content-center my-3 no-gutters">
							<div class="col-lg-2 col-md-6">
								<div class="form-group form-label-group sh">
									<label for="masuk" class="px-3">Tanggal & Jam Masuk</label>
									<input type="text" name="masuk" id="masuk" ng-model="masuk" class="form-control text-center bg-white rounded-0 input-time">
								</div>
							</div>
							<div class="col-lg-2 col-md-6">
								<div class="form-group form-label-group">
									<label for="pulang" class="px-3">Tanggal & Jam Pulang</label>
									<input type="text" name="pulang" id="pulang" ng-model="pulang" class="form-control text-center bg-white rounded-0 input-time">
								</div>
							</div>
							<div class="col-lg-3 col-md-10">
								<div class="form-group form-label-group">
									<label for="pin" class="px-3">Pilih Karyawan</label>
									<select id="pin" name="pin" ng-model="pin" class="form-control text-center show-menu-arrow bg-white rounded-0" data-header="Pilih Karyawan" data-live-search="true" data-style="border bg-white rounded-0">
										<option ng-repeat="emp in emps" value="{{emp.pin}}">{{emp.pin}} - {{emp.name}}</option>
									</select>
								</div>
							</div>
							<div class="col-auto my-auto mx-md-0 mx-3">
								<button type="button" class="btn btn-success btn-filter rounded-0 shadow-sm mt-3" ng-click="getFilterData(this)"><i class="fas fa-fw fa-filter"></i>&nbsp; Filter Data</button>
							</div>
						</div>
					</form>
					<div class="row justify-content-center divFilterTab d-none">
						<div class="col-lg-8">
							<div class="table-responsive">
								<table class="table table-striped table-hover align-middle shadow-sm" id="dataFilterTable" data-source="<?= base_url('scanlog/dt_filter') ?>">
									<thead class="thead-light">
										<tr>
											<th>NIK</th>
											<th>Name</th>
											<th>Scan Time</th>
											<th>Gate</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-4 divResumeTab d-none">
							<div class="table-responsive my-2">
								<table class="table table-striped table-hover align-middle shadow-sm" id="dataResumeTable" data-source="<?= base_url('scanlog/getResume') ?>">
									<thead class="thead-light">
										<tr>
											<th>First Scan</th>
											<th>Last Scan</th>
										</tr>
									</thead>
									<tbody id="fillResumeTab"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>