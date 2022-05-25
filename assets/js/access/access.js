const base = angular.element('body').data('home');
const source =  angular.element('#dataTable').data('source');
const url = angular.element('#authForm').attr('action');
const app = angular.module('accessPage', []);

app.controller('accessPage',($scope,$http) => {
	$scope.checkActive = () => {
		if ($scope.active == true) {
			$scope.activeStatus = 'Aktif';
		} else {
			$scope.activeStatus = 'Tidak Aktif';
		}
	}
	$scope.checkSpv = () => {
		if ($scope.is_spv == true) {
			$scope.spvStatus = 'HR & SPV Up';
		} else {
			$scope.spvStatus = 'Regular';
		}
	}
	$scope.checkSwitch = () => {
		$scope.checkSpv();
		$scope.checkActive();
	}
	$scope.checkSwitch();
	$scope.newAcc = () => {
		angular.element('#authForm')[0].reset();
		$scope.id = '';
		$scope.deptList = '';
		$scope.name = '';
		$scope.username = '';
		$scope.password = '';
		$scope.active = true;
		$scope.is_spv = '';
		$scope.getTitle = 'Add New Account';
		angular.element('#modalManage').modal('show');
		$scope.checkSwitch();
	}
	const table = angular.element('#dataTable').DataTable({
		"sDom" : 'tipr',
		"pageLength": 25,
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

	$scope.isAlreadyExist = username => {
		if (username != '') {
			$http.get(base+'checkUsername/'+username).then((result) => {
				if (result.data.error) {
					errorNotif(result.data.error);
					$scope.username = '';
				}
			});
		}
	}
	$scope.convertUser = () => {
		const re = /^[a-zA-Z0-9_]+$/;
		if (!re.test($scope.username)) {
			const char = $scope.username.substr(0, $scope.username.length - 1);
			$scope.username = char;
		}
		$scope.isAlreadyExist($scope.username);
	}

	$scope.saveAcc = () => {
		$http({
			method:"POST",
			url:url,
			data: {
				id: $scope.id,
				auth_dept_id: $scope.deptList,
				nama: $scope.name,
				username: $scope.username,
				password: $scope.password,
				active: $scope.active,
				is_spv: $scope.is_spv,
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
				angular.element('#modalManage').modal('hide');
			}
		}),(err) => {
			console.error(err);
			errorNotif('Terjadi Sebuah Kesalahan');
		};
	}
	$scope.setSwitch = (elm,val) => {
		if (val == 1) {
			angular.element(elm).prop('checked',true);
		} else if (val == 0) {
			angular.element(elm).prop('checked',false);
		}
	}
	$scope.edit = (id) => {
		$scope.getTitle = 'Edit Account';
		$http.get(base+'get_by_id_acc/'+id).then((result) => {
			$scope.active = (result.data.active == 1) ? true : false;
			$scope.is_spv = (result.data.is_spv == 1) ? true : false;
			$scope.checkSwitch();
			$scope.setSwitch('#active',result.data.active);
			$scope.setSwitch('#is_spv',result.data.is_spv);
			$scope.id = id;
			$scope.name = result.data.nama;
			$scope.username = result.data.username;
			$scope.password = result.data.password;
			$scope.deptList = result.data.auth_dept_id;
			angular.element('#modalManage').modal('show');
		});
	}
	$scope.delete = (id) => {
		Swal.fire({
			title: 'Yakin ingin menghapus?',
			text: "Proses ini tidak bisa dibatalkan!",
			icon: 'warning',
			showCancelButton: true,
			cancelButtonText: 'Batalkan',
			confirmButtonText: 'Ya, hapus!'
		}).then((result) => {
			if (result.isConfirmed) {
				$http.get(base+'deleteAccount/'+id).then((res) => {
					if (res.data.error) {
						errorNotif(res.data.error);
					} else if (res.data.success) {
						$scope.reloadTable();
						successPopUp(res.data.success);
					}
				});
			}
		});
	}
});