const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const tableDetHistory = angular.element('#detHistoryTable');
const sourceDetHistory = tableDetHistory.data('source');
let config = {
	enableTime: false,
	dateFormat: "Y-m-d",
};
const app = angular.module('visTrace', []);
app.controller('visTrace',($scope,$http) => {
	const fp = flatpickr('.selectDate',config);
	fp[0];
	$http.get(base+'attendance').then((res) => {
		$scope.getDate = res.data.date;
		$scope.getSearchDate = res.data.date;
		angular.element('.get-date').removeClass('d-none');
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
	tableDetHistory.DataTable({
		"sDom" : 'tir',
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
	$scope.search = () => {
		table.DataTable().search($scope.searchInTable).draw();
	}
	$scope.reloadTable = () => {
		table.DataTable().ajax.reload();
	}
	$scope.closeShow = () => {
		angular.element('.card-show').addClass('d-none');
	}
	$scope.refreshDetail = () => {
		tableDetHistory.DataTable().ajax.reload();
	}
	$scope.show = (id) => {
		$scope.attId = id;
		$http.get(angular.element('.card-visitor').data('source')+$scope.attId).then((res) => {
			$scope.getName = res.data.getName;
			$scope.getPIN = res.data.getPIN;
			$scope.getSearchDate = res.data.getSearchDate;
			angular.element('.card-show').removeClass('d-none');
			angular.element('.scroll-to-top').click();
			$scope.refreshDetail();
		});
	}
	$scope.getYesterday = () => {
		$http.get(base+'attendance/att_yesterday_vis').then((res) => {
			table.DataTable().ajax.reload();
			$scope.getDate = res.data.date;
		});
	}
	$scope.getToday = () => {
		$http.get(base+'attendance/att_today_vis').then((res) => {
			table.DataTable().ajax.reload();
			$scope.getDate = res.data.date;
		});
	}
	$scope.getTomorrow = () => {
		$http.get(base+'attendance/att_tomorrow_vis').then((res) => {
			table.DataTable().ajax.reload();
			$scope.getDate = res.data.date;
		});
	}
	$scope.select_date = () => {
		$http.get(base+'att_getDate_vis/'+$scope.selectDate).then((res) => {
			table.DataTable().ajax.reload();
			$scope.getDate = res.data.date;
		});
	}
});