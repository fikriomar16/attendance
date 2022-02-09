const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const refreshPerSec = 1;
const app = angular.module('dashboardApp', []);
app.controller('dashboardController',($scope,$http) => {
	setTimeout(() => {
		angular.element('.show-count').removeClass('d-none');
	},1000);
	table.DataTable({
		"sDom":"tir",
		"pageLength": 50,
		"processing": true,
		"serverSide": true,
		"order": [],
		"ajax": {
			"url": source,
			"type": "POST"
		},
		"columnDefs": [{ 
			"targets": [2],
			"orderable": false
		}]
	});
	$scope.search = () => {
		table.DataTable().search($scope.searchInTable).draw();
	}
	$scope.reloadTable = () => {
		table.DataTable().ajax.reload();
	}
	$scope.getCount = () => {
		$http.get(base+'countEmpVis').then((res) => {
			$scope.countEmployee = res.data.countEmployee;
			$scope.countVisitor = res.data.countVisitor;
		});
	}
	$scope.getCount();
	$scope.reloadAll = () => {
		$scope.getCount();
		$scope.reloadTable();
		$scope.reloadTable2();
	}
});