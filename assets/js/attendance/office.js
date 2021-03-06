const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const table_sum = angular.element('#sumTable');
const source_sum = table_sum.data('source');
const tableDetRecap = angular.element('#detRecapTable');
const sourceDetRecap = tableDetRecap.data('source');
const tableDetHistory = angular.element('#detHistoryTable');
const sourceDetHistory = tableDetHistory.data('source');
let config = {
	enableTime: false,
	dateFormat: "Y-m-d",
};
let configRecap = {
	plugins: [
	new monthSelectPlugin({
          shorthand: true, //defaults to false
          dateFormat: "m-Y", //defaults to "F Y"
          theme: "material-blue" // defaults to "light"
      })
	]
};
const app = angular.module('attOffice', []);
app.controller('attOffice',($scope,$http) => {
	const fp = flatpickr('.selectDate',config);
	fp[0];
	const fp2 = flatpickr('.recapDate',configRecap);
	fp2[0];
	$http.get(base+'attendance').then((res) => {
		$scope.getAttDate = res.data.date;
		angular.element('.get-date').removeClass('d-none');
	});
	table.DataTable({
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
	table_sum.DataTable({
		"sDom" : 'tirp',
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"order": [],
		"ajax": {
			"url": source_sum,
			"type": "POST"
		},
		"columnDefs": [{ 
			"targets": [],
			"orderable": false
		}]
	});
	tableDetRecap.DataTable({
		"sDom" : 'tr',
		"bSort" : false,
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"order": [],
		"ajax": {
			"url": sourceDetRecap,
			"type": "POST"
		},
		"columnDefs": [{ 
			"targets": [0],
			"orderable": false
		}]
	});
	tableDetHistory.DataTable({
		"sDom" : 'tirp',
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"order": [],
		"ajax": {
			"url": sourceDetHistory,
			"type": "POST"
		},
		"columnDefs": [{ 
			"targets": [0,-1],
			"orderable": false
		}]
	});
	$scope.select_date = () => {
		$http.get(base+'att_getDate_off/'+$scope.selectDate).then((res) => {
			$scope.getAttDate = res.data.date;
			$scope.getSearchDate = res.data.date;
			// reload tab
			table.DataTable().ajax.reload();
			$scope.refreshDetail();
		});
	}
	$scope.getShift = () => {
		var shift = $scope.shiftList;
		if ($scope.shiftList == '') {
			shift = 0;
		}
		$http.get(base+'attendance/set_shift_off/'+shift).then((res) => {
			table.DataTable().ajax.reload();
		});
	}
	$scope.getDept = () => {
		var dept = $scope.deptList;
		if ($scope.deptList == '') {
			dept = 0;
		}
		console.log(dept);
		$http.get(base+'attendance/set_dept_off/'+dept).then((res) => {
			table.DataTable().ajax.reload();
		});
	}
	$scope.getShiftList = () => {
		$http.get(base+'attendance/getShiftList').then((res) => {
			$scope.shiftLists = res.data.lists;
		});
	}
	$scope.search = () => {
		table.DataTable().search($scope.searchInTable).draw();
	}
	$scope.reloadTable = () => {
		table.DataTable().ajax.reload();
	}
	$scope.closeShow = () => {
		angular.element('.div-detail').addClass('d-none');
		angular.element('.div-att').removeClass('d-none');
	}
	$scope.refreshDetail = () => {
		// reload tab
		table_sum.DataTable().ajax.reload();
		tableDetRecap.DataTable().ajax.reload();
		tableDetHistory.DataTable().ajax.reload();
	}
	$scope.show = (nik) => {
		$scope.attId = nik;
		$scope.getNIK = nik;
		$http.get(angular.element('.card-attendance').data('source')+$scope.attId).then((res) => {
			angular.element('.scroll-to-top').click();
			$scope.getName = res.data.getName;
			$scope.getSearchDate = res.data.getSearchDate;
			angular.element('.div-detail').removeClass('d-none');
			angular.element('.div-att').addClass('d-none');
			$scope.refreshDetail();
		});
	}
	$scope.getAttYesterday = () => {
		$http.get(base+'attendance/att_yesterday_off_detail').then((res) => {
			$scope.getSearchDate = res.data.date;
			$scope.refreshDetail();
		});
	}
	$scope.getAttToday = () => {
		$http.get(base+'attendance/att_today_off_detail').then((res) => {
			$scope.getSearchDate = res.data.date;
			$scope.refreshDetail();
		});
	}
	$scope.getAttTomorrow = () => {
		$http.get(base+'attendance/att_tomorrow_off_detail').then((res) => {
			$scope.getSearchDate = res.data.date;
			$scope.refreshDetail();
		});
	}
	$scope.getYesterday = () => {
		$http.get(base+'attendance/att_yesterday_off').then((res) => {
			$scope.getAttDate = res.data.date;
			$scope.getSearchDate = res.data.date;
			$scope.searchDate = res.data.date;
			// reload tab
			table.DataTable().ajax.reload();
			$scope.refreshDetail();
			$scope.getShiftList();
		});
	}
	$scope.getToday = () => {
		$http.get(base+'attendance/att_today_off').then((res) => {
			$scope.getAttDate = res.data.date;
			$scope.getSearchDate = res.data.date;
			$scope.searchDate = res.data.date;
			// reload tab
			table.DataTable().ajax.reload();
			$scope.refreshDetail();
			$scope.getShiftList();
		});
	}
	$scope.getTomorrow = () => {
		$http.get(base+'attendance/att_tomorrow_off').then((res) => {
			$scope.getAttDate = res.data.date;
			$scope.getSearchDate = res.data.date;
			$scope.searchDate = res.data.date;
			// reload tab
			table.DataTable().ajax.reload();
			$scope.refreshDetail();
			$scope.getShiftList();
		});
	}
	$scope.showPhoto = (url) => {
		angular.element('.off-photo').attr('src',url);
	}
	$scope.recap_date = () => {
		$http.get(base+'recapSumOff/'+$scope.recapDate).then((res) => {
			table_sum.DataTable().ajax.reload();
		});
	}
});