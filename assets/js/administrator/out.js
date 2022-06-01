const base = angular.element('body').data('home');
const source = angular.element('#dataTable').data('source');
const url = angular.element('#primeForm').attr('action');
const refreshPerMin = 0.3;
const app = angular.module('noticePage', []);
let config = {
	enableTime: false,
	dateFormat: "Y-m-d"
};
const fp = flatpickr('.selectDate',config);
fp[0];
app.controller('noticePage',($scope,$http) => {
	const table = angular.element('#dataTable').DataTable({
		// "sDom" : 'tipr',
		"pageLength": 50,
		"processing": false,
		"serverSide": true,
		"responsive": true,
		"order": [],
		"ajax": {
			"url": source,
			"type": "POST"
		},
		"columnDefs": [],
		"initComplete": () => {
		},
		"drawCallback": (res) => {
			$scope.resTotal = res.json.recordsTotal;
			angular.element('#resTotal').html(`Total Melebihi Out: <br>${res.json.recordsTotal}`);
		}
	});
	$scope.reloadTable = () => {
		table.ajax.reload();
	}
	$scope.getExcel = option => {
		$http({
			method:"POST",
			url:url,
			data:{
				'date_start':$scope.selectDate,
				'date_end':$scope.selectDate2,
				'dept':$scope.deptList,
				'option':option
			}
		}).then((res) => {
			if (res.data.error) {
				var list = '';
				var loop = 0;
				list+='<ul class="text-left">';
				while (loop < res.data.error.length) {
					list+='<li>';
					list+=res.data.error[loop];
					list+='</li>';
					loop++;
				}
				list+='</ul>';
				listErrorNotif(list);
			} else if (res.data.success) {
				$scope.reloadTable();
				$scope.resDateStart = res.data.date_start;
				$scope.resDateEnd = res.data.date_end;
				$scope.resDept = res.data.dept;
				if (res.data.option == 'reload') {
					successPopUp(res.data.success);
				}
				if (res.data.option == 'export') {
					successPopUp('Memproses File Excel.....');
					window.open(res.data.url,"_blank");
				}
				angular.element('.div-resume').removeClass('d-none');
			} else {
				console.log(res);
			}
		}),(err) => {
			errorNotif('Terjadi Sebuah Kesalahan');
			console.error(err);
		};
	}
	$scope.searchAtt = (nik,date,dept) => {
		$http({
			method:"POST",
			url:base+'searchAttendance',
			data:{
				'nik':nik,
				'date':date,
				'dept':dept.substring(0,4)
			}
		}).then((res) => {
			if (res.data.error) {
				var list = '';
				var loop = 0;
				list+='<ul class="text-left">';
				while (loop < res.data.error.length) {
					list+='<li>';
					list+=res.data.error[loop];
					list+='</li>';
					loop++;
				}
				list+='</ul>';
				listErrorNotif(list);
			} else if (res.data.success) {
				window.location.href = res.data.url;
			} else {
				console.log(res);
			}
		}),(err) => {
			errorNotif('Terjadi Sebuah Kesalahan');
			console.error(err);
		};
	}
	setInterval(() => {
		$scope.reloadTable();
	}, refreshPerMin * 60000);
});