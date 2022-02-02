const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const table_sum = angular.element('#sumTable');
const source_sum = table_sum.data('source');
const tableDetRecap = angular.element('#detRecapTable');
const sourceDetRecap = tableDetRecap.data('source');
const tableDetHistory = angular.element('#detHistoryTable');
const sourceDetHistory = tableDetHistory.data('source');
var shift;
const app = angular.module('attEmployee', []);
app.controller('attEmployee',($scope,$http) => {
	$http.get(base+'attendance').then((res) => {
		$scope.getAttDate = res.data.date;
	});
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
			"targets": [ 0,1,-1 ],
			"orderable": false
		}]
	});
	table_sum.DataTable({
		"sDom" : 'tipr',
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
		"sDom" : 'tipr',
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"order": [],
		"ajax": {
			"url": sourceDetRecap,
			"type": "POST"
		},
		"columnDefs": [{ 
			"targets": [],
			"orderable": false
		}]
	});
	// tableDetHistory.DataTable({
	// 	"sDom" : 'tipr',
	// 	"processing": true,
	// 	"serverSide": true,
	// 	"responsive": true,
	// 	"order": [],
	// 	"ajax": {
	// 		"url": sourceDetHistory,
	// 		"type": "POST"
	// 	},
	// 	"columnDefs": [{ 
	// 		"targets": [],
	// 		"orderable": false
	// 	}]
	// });
	$scope.getShift = () => {
		// table.DataTable().column(4).search($scope.shiftList).draw();
		if ($scope.shiftList == '') {
			shift = '0';
		} else {
			shift = $scope.shiftList;
		}
		console.log(shift);
		$http.get(base+'attendance/set_shift/'+shift).then((res) => {
			table.DataTable().ajax.reload();
		});
	}
	$scope.search = () => {
		table.DataTable().search($scope.searchInTable).draw();
	}
	$scope.reloadTable = () => {
		table.DataTable().ajax.reload();
	}
	$scope.closeShow = () => {
		angular.element('.card-show').addClass('d-none');
	}
	$scope.show = (nik) => {
		$scope.attId = nik;
		$scope.getNIK = nik;
		$http.get(angular.element('.card-attendance').data('source')+$scope.attId).then((res) => {
			angular.element('.scroll-to-top').click();
			$scope.getName = res.data.getName;
			$scope.getSearchDate = res.data.getSearchDate;
			// reload tab
			table_sum.DataTable().ajax.reload();
			tableDetRecap.DataTable().ajax.reload();
			// tableDetHistory.DataTable().ajax.reload();
		});
		angular.element('.card-show').removeClass('d-none');
	}
	$scope.getAttYesterday = () => {
		$http.get(base+'attendance/att_yesterday_emp_detail').then((res) => {
			$scope.getSearchDate = res.data.date;
			// reload tab
			table_sum.DataTable().ajax.reload();
			tableDetRecap.DataTable().ajax.reload();
			// tableDetHistory.DataTable().ajax.reload();
		});
	}
	$scope.getAttToday = () => {
		$http.get(base+'attendance/att_today_emp_detail').then((res) => {
			$scope.getSearchDate = res.data.date;
			// reload tab
			table_sum.DataTable().ajax.reload();
			tableDetRecap.DataTable().ajax.reload();
			// tableDetHistory.DataTable().ajax.reload();
		});
	}
	$scope.getAttTomorrow = () => {
		$http.get(base+'attendance/att_tomorrow_emp_detail').then((res) => {
			$scope.getSearchDate = res.data.date;
			// reload tab
			table_sum.DataTable().ajax.reload();
			tableDetRecap.DataTable().ajax.reload();
			// tableDetHistory.DataTable().ajax.reload();
		});
	}
	$scope.getYesterday = () => {
		$http.get(base+'attendance/att_yesterday_emp').then((res) => {
			$scope.getAttDate = res.data.date;
			$scope.getSearchDate = res.data.date;
			// reload tab
			table.DataTable().ajax.reload();
			table_sum.DataTable().ajax.reload();
			tableDetRecap.DataTable().ajax.reload();
			// tableDetHistory.DataTable().ajax.reload();
		});
	}
	$scope.getToday = () => {
		$http.get(base+'attendance/att_today_emp').then((res) => {
			$scope.getAttDate = res.data.date;
			$scope.getSearchDate = res.data.date;
			// reload tab
			table.DataTable().ajax.reload();
			table_sum.DataTable().ajax.reload();
			tableDetRecap.DataTable().ajax.reload();
			// tableDetHistory.DataTable().ajax.reload();
		});
	}
	$scope.getTomorrow = () => {
		$http.get(base+'attendance/att_tomorrow_emp').then((res) => {
			$scope.getAttDate = res.data.date;
			$scope.getSearchDate = res.data.date;
			// reload tab
			table.DataTable().ajax.reload();
			table_sum.DataTable().ajax.reload();
			tableDetRecap.DataTable().ajax.reload();
			// tableDetHistory.DataTable().ajax.reload();
		});
	}
});