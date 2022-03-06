const base = angular.element('body').data('home');
const source =  angular.element('#dataTable').data('source');
const url = angular.element('#shiftForm').attr('action');
const app = angular.module('setShift', []);
let config = {
	enableTime: true,
	enableSeconds: true,
	noCalendar: true,
	minuteIncrement: 1,
	dateFormat: "H:i:S",
	time_24hr: true,
	defaultHour: 0,
	allowInput: true,
	altFormat: "H:i:S",
	altInput: true
};
app.controller('setShift',($scope,$http) => {
	const fp = flatpickr('.input-time',config);
	fp[0];
	$scope.newDuration = () => {
		angular.element('#shiftForm')[0].reset();
		$scope.id_shift = '';
		$scope.shift_code = '';
		$scope.work_time = '';
		$scope.work_start = '';
		$scope.work_end = '';
		$scope.getTitle = 'Add New Shift';
		angular.element('.card-new').removeClass('d-none');
		angular.element('.notif-edit').addClass('d-none');
		fp[0].clear();
		flatpickr('.input-time',config);
	}
	$scope.closeAdd = () => {
		angular.element('.card-new').addClass('d-none');
		angular.element('#shiftForm')[0].reset();$scope.id_shift = '';
	}
	$scope.saveDuration = () => {
		$http({
			method:"POST",
			url:url,
			data:{
				'id':$scope.id_shift,
				'shift_code':$scope.shift_code,
				'work_time':$scope.work_time,
				'work_start':$scope.work_start,
				'work_end':$scope.work_end
			}
		}).then((res) => {
			if (res.data.error) {
				var list = '';
				var loop = 0;
				while (loop < res.data.error.length) {
					list+='<ul class="text-left">';
					list+='<li>';
					list+=res.data.error[loop];
					list+='</li>';
					list+='</ul>';
					loop++;
				}
				listErrorNotif(list);
			} else if (res.data.success) {
				$scope.reloadTable();
				successPopUp(res.data.success);
				$scope.closeAdd();
			}
		}),(err) => {
			console.error(err);
			errorNotif('Terjadi Sebuah Kesalahan');
		};
	}
	$scope.edit = (id) => {
		fp[0].destroy();
		$scope.getTitle = 'Edit Duration';
		$scope.id_shift = id;
		angular.element('.notif-edit').removeClass('d-none');
		angular.element('.alert-loading').removeClass('d-none');
		angular.element('.scroll-to-top').click();
		$http.get(base+'get_by_id_shift/'+id).then((result) => {
			angular.element('.alert-loading').addClass('d-none');
			angular.element('.card-new').removeClass('d-none');
			$scope.shift_code = result.data.shift_code;
			$scope.work_time = result.data.work_time;
			$scope.work_start = result.data.work_start;
			$scope.work_end = result.data.work_end;
			document.getElementById('shift_code').value = result.data.shift_code;
			document.getElementById('work_time').value = result.data.work_time;
			document.getElementById('work_start').value = result.data.work_start;
			document.getElementById('work_end').value = result.data.work_end;
			flatpickr('.input-time',config);
		});
	}
	$scope.delete = (id) => {
		$scope.closeAdd();
		Swal.fire({
			title: 'Yakin ingin menghapus?',
			text: "Proses ini tidak bisa dibatalkan!",
			icon: 'warning',
			showCancelButton: true,
			cancelButtonText: 'Batalkan',
			confirmButtonText: 'Ya, hapus!'
		}).then((result) => {
			if (result.isConfirmed) {
				$http.get(base+'deleteShift/'+id).then((res) => {
					if (res.data.error) {
						errorNotif(res.data.error);
					} else if (res.data.success) {
						$scope.reloadTable();
						successPopUp(res.data.success);
						$scope.closeAdd();
					}
				});
			}
		});
	}
	const table = angular.element('#dataTable').DataTable({
		"sDom" : 'tipr',
		"pageLength":15,
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"order": [],
		"ajax": {
			"url": source,
			"type": "POST"
		},
		"columnDefs": [{ 
			"targets": [ 0,-1 ],
			"orderable": false
		}]
	});
	$scope.search = () => {
		table.search($scope.searchInTable).draw();
	}
	$scope.reloadTable = () => {
		table.ajax.reload();
	}
	$scope.importButton = () => {
		angular.element('.import_dws').click();
	}
	$scope.doImportDWS = (files) => {
		var fd = new FormData();
		fd.append("import_dws", files[0]);
		$http.post(angular.element('#importForm').attr('action'),fd,{
			withCredentials: true,
			headers: {'Content-Type': 'multipart/form-data' },
			headers: {'Content-Type': undefined },
			transformRequest: angular.identity
		}).then((res) => {
			$scope.importData = [];
			if (res.data.error) {
				errorPopUp(res.data.error);
			}
			if (res.data.success) {
				successPopUp(res.data.success);
				if (res.data.collect.length == 0) {
					errorPopUp('Data Kosong atau Sudah Terimport');
				} else {
					var loop = 0;
					tbodyTr = '';
					while (loop < res.data.collect.length) {
						tbodyTr+= '<tr>';
						tbodyTr+= '<td class="text-primary font-weight-bold">';
						tbodyTr+= res.data.collect[loop].shift_code;
						tbodyTr+= '</td>';
						tbodyTr+= '<td>';
						tbodyTr+= res.data.collect[loop].work_time;
						tbodyTr+= '</td>';
						tbodyTr+= '<td>';
						tbodyTr+= res.data.collect[loop].work_start;
						tbodyTr+= '</td>';
						tbodyTr+= '<td>';
						tbodyTr+= res.data.collect[loop].work_end;
						tbodyTr+= '</td>';
						tbodyTr+= '</tr>';
						loop++;
					}
					$scope.importData = res.data.collect;
					angular.element('#table-import').html(tbodyTr);
					successPopUp(res.data.success);
					angular.element('#modalImport').modal('show');
				}
			}
		}),(err) => {
			console.log(err);
		};
		angular.element('#importForm')[0].reset();
		document.getElementById("import_dws").value = "";
	}
	$scope.importFromModal = () => {
		infoPopUp('Test Import');
		angular.element('#modalImport').modal('hide');
	}
});