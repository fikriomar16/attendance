const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const url = angular.element('#durationForm').attr('action');
const app = angular.module('setDur', []);
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
app.controller('setDur',($scope,$http) => {
	const fp = flatpickr('.input-time',config);
	fp[0];
	$scope.checkDept = () => {
		$http.get(base+'setup/deptList').then((res) => {
			$scope.depts = res.data;
		});
	}
	$scope.checkDept();
	$scope.newDuration = () => {
		angular.element('#durationForm')[0].reset();
		$scope.id_dur = '';
		$scope.auth_dept_id = '';
		$scope.late_allowed = '';
		$scope.out_allowed = '';
		$scope.out_allowed_friday = '';
		$scope.out_allowed_saturday = '';
		$scope.checkDept();
		$scope.getTitle = 'Add New Duration';
		angular.element('.card-new').removeClass('d-none');
		angular.element('.notif-edit').addClass('d-none');
		fp[0].clear();
		flatpickr('.input-time',config);
	}
	$scope.closeAdd = () => {
		angular.element('.card-new').addClass('d-none');
		angular.element('#durationForm')[0].reset();$scope.id_dur = '';
	}
	$scope.saveDuration = () => {
		$http({
			method:"POST",
			url:url,
			data:{
				'id':$scope.id_dur,
				'auth_dept_id':$scope.auth_dept_id,
				'late_allowed':$scope.late_allowed,
				'out_allowed':$scope.out_allowed,
				'out_allowed_friday':$scope.out_allowed_friday,
				'out_allowed_saturday':$scope.out_allowed_saturday
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
		$scope.id_dur = id;
		angular.element('.notif-edit').removeClass('d-none');
		angular.element('.alert-loading').removeClass('d-none');
		angular.element('.scroll-to-top').click();
		$http.get(base+'setup/deptListExcept/'+id).then((res) => {
			angular.element('.alert-loading').addClass('d-none');
			angular.element('.card-new').removeClass('d-none');
			$scope.depts = res.data;
			$http.get(base+'get_by_id_duration/'+id).then((result) => {
				$scope.auth_dept_id = result.data.auth_dept_id;
				$scope.late_allowed = result.data.late_allowed;
				$scope.out_allowed = result.data.out_allowed;
				$scope.out_allowed_friday = result.data.out_allowed_friday;
				$scope.out_allowed_saturday = result.data.out_allowed_saturday;
				document.getElementById('late_allowed').value = result.data.late_allowed;
				document.getElementById('out_allowed').value = result.data.out_allowed;
				document.getElementById('out_allowed_friday').value = result.data.out_allowed_friday;
				document.getElementById('out_allowed_saturday').value = result.data.out_allowed_saturday;
				flatpickr('.input-time',config);
			});
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
				$http.get(base+'deleteDuration/'+id).then((res) => {
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
	table.DataTable({
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
		table.DataTable().search($scope.searchInTable).draw();
	}
	$scope.reloadTable = () => {
		table.DataTable().ajax.reload();
	}
});