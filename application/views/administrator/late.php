<div class="container-fluid" ng-app="noticePage" ng-controller="noticePage">
	<div class="row justify-content-center">
		<div class="col-xl-12">
			<div class="card border-0 border-bottom-warning shadow mb-4 rounded">
				<div class="card-header border-0 shadow-sm h-100 px-4">
					<div class="row justify-content-between">
						<div class="col-auto my-auto">
							<p class="text-primary font-weight-bold h6"><?= $title ?? '' ?></p>
						</div>
						<div class="col-auto my-auto">
							<button class="btn btn-primary btn-rounded btn-sm text-xs" type="button" ng-click="reloadTable()">
								<i class="fas fa-fw fa-sync"></i> Reload Table
							</button>
						</div>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="row justify-content-between">
						<div class="col my-1">
							<div class="alert alert-primary alert-loading text-center font-weight-bold d-none" role="alert">
								<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>&nbsp; Sedang Memuat Data
							</div>
						</div>
					</div>
					<div class="row justify-content-lg-end my-2">
						<div class="col my-1">
							<form method="POST" enctype="multipart/form-data" action="<?= base_url('get_late') ?>" id="primeForm">
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-calendar-day fa-fw"></i>&nbsp;</span>
									</div>
									<input type="text" name="selectDate" id="selectDate" ng-model="selectDate" class="form-control text-center selectDate bg-white" placeholder="Tanggal Awal">
									<input type="text" name="selectDate2" id="selectDate2" ng-model="selectDate2" class="form-control text-center selectDate bg-white" placeholder="Tanggal Akhir">
									<?php if($this->session->userdata('user')->is_spv == 1): ?>
										<select class="form-control form-control-sm custom-select bg-light border-0 font-weight-bold selectpicker" name="deptList" id="deptList" ng-change="getDept()" ng-model="deptList" data-style="btn-light border font-weight-bold" data-header="Pilih Department">
											<option value="" selected>All Dept.</option>
											<?php foreach ($deptlists as $list): ?>
												<option value="<?= $list->name ?>"><?= $list->name ?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
									<div class="input-group-append">
										<button class="btn text-xs btn-outline-primary" type="button" id="button-addon" ng-click="getExcel('reload')"><i class="fas fa-search fa-fw"></i>&nbsp; Show</button>
									</div>
									<div class="input-group-append">
										<button class="btn text-xs btn-outline-secondary" type="button" id="button-addon2" ng-click="getExcel('export')"><i class="fas fa-file-excel fa-fw"></i>&nbsp; Print</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="row justify-content-center my-2 div-resume d-none">
						<div class="col-auto">
							<ul class="list-group list-group-horizontal">
								<li class="list-group-item">Tanggal: <br>{{resDateStart}} - {{resDateEnd}}</li>
								<li class="list-group-item">Departemen: <br>{{resDept}}</li>
								<li class="list-group-item" id="resTotal">Total Keterlambatan: {{resTotal}}</li>
							</ul>
						</div>
					</div>
					<div class="row justify-content-center my-2">
						<div class="col my-1">
							<div class="table-responsive mx-1 small">
								<table class="table table-sm table-striped table-hover align-middle shadow-sm" id="dataTable" data-source="<?= base_url('dt_late') ?>">
									<thead class="thead-light">
										<tr>
											<th class="text-center">NIK</th>
											<th class="text-center">Name</th>
											<th class="text-center">Shift</th>
											<th class="text-center">Date</th>
											<th class="text-center">Department</th>
											<th class="text-center">Status</th>
										</tr>
									</thead>
									<tbody id="tbodyDataTable"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>