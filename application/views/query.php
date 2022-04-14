<div class="container-fluid" ng-app="QueryRead" ng-controller="QueryRead">
	<div class="row justify-content-center">
		<div class="col">
			<div class="card border-0 border-bottom-primary shadow-sm mb-4 rounded">
				<div class="card-header border-0">
					<div class="row justify-content-between">
						<div class="col-auto">
							<span class="text-primary font-weight-bold px-3"><?= $title ?? 'Import SQL File' ?></span>
						</div>
						<div class="col-auto d-none">
							<button class="btn btn-primary btn-rounded btn-sm" type="button" ng-click="reloadTable()">
								<i class="fas fa-fw fa-sync"></i> Reload
							</button>
						</div>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="row justify-content-center my-1">
						<div class="col-lg-4">
							<form action="<?= base_url('importSQL') ?>" id="formImportSQL" enctype="multipart/form-data">
								<div class="custom-file my-3">
									<input type="file" class="custom-file-input" id="import_sql" name="import_sql" ng-model="import_sql" accept=".sql" onchange="angular.element(this).scope().importButton(this.files)">
									<label class="custom-file-label" for="import_sql">Import SQL File</label>
								</div>
							</form>
						</div>
					</div>
					<div class="row justify-content-center my-4">
						<div class="col">
							<div class="mx-2"><pre id="resultSQL"></pre></div>
							<button class="btn btn-rounded btn-primary btnExec d-none m-4" id="btnExec" ng-click="execQuery()"><i class="fas fa-fw fa-terminal"></i>&nbsp; Execute Query</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>