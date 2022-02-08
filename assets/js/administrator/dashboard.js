const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const table2 = angular.element('#dataTable2');
const source2 = table2.data('source');
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
			"targets": [],
			"orderable": false
		}]
	});
	table2.DataTable({
		"sDom":"tir",
		"pageLength": 50,
		"processing": true,
		"serverSide": true,
		"order": [],
		"ajax": {
			"url": source2,
			"type": "POST"
		},
		"columnDefs": [{ 
			"targets": [],
			"orderable": false
		}]
	});
	$scope.search = () => {
		table.DataTable().search($scope.searchInTable).draw();
	}
	$scope.reloadTable = () => {
		table.DataTable().ajax.reload();
	}
	$scope.reloadTable2 = () => {
		table2.DataTable().ajax.reload();
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