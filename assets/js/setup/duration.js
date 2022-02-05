const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const url = angular.element('#durationForm').attr('action');
const app = angular.module('setDur', []);
let config = {
	locale: "id",
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
	$scope.checkDept = () => {
		$http.get(base+'setup/deptList').then((res) => {
			$scope.depts = res.data;
		});
	}
	$scope.newDuration = () => {
		$scope.checkDept();
		angular.element('.card-new').removeClass('d-none');
		angular.element('.notif-edit').addClass('d-none');
	}
	$scope.closeAdd = () => {
		angular.element('.card-new').addClass('d-none');
		angular.element('#durationForm')[0].reset();
	}
	$scope.saveDuration = () => {
		$http({
			method:"POST",
			url:url,
			data:{
				'id':$scope.id_dur,
				'auth_dept_id':$scope.auth_dept_id,
				'late_allowed':$scope.late_allowed,
				'out_allowed':$scope.out_allowed
			}
		}).then((res) => {
			if (res.data.error) {
				errorNotif(res.data.error);
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
		angular.element('.notif-edit').removeClass('d-none');
		$scope.id_dur = id;
		angular.element('.alert-loading').removeClass('d-none');
		$http.get(base+'setup/deptListExcept/'+id).then((res) => {
			angular.element('.alert-loading').addClass('d-none');
			angular.element('.card-new').removeClass('d-none');
			$scope.depts = res.data;
			$http.get(base+'get_by_id_duration/'+id).then((result) => {
				$scope.late_allowed = result.data.late_allowed;
				$scope.out_allowed = result.data.out_allowed;
				document.getElementById('late_allowed').value = result.data.late_allowed;
				document.getElementById('out_allowed').value = result.data.out_allowed;
				$scope.auth_dept_id = result.data.auth_dept_id;
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
		})
	}
	table.DataTable({
		"sDom" : 'tipr',
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
	const flatpickr = angular.element('.input-time').flatpickr(config);
});