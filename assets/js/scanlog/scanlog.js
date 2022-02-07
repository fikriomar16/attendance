const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const app = angular.module('ScanLog', []);
app.controller('ScanLog',($scope,$http) => {
	table.DataTable({
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
	$scope.search = () => {
		table.DataTable().search($scope.searchInTable).draw();
	}
	$scope.reloadTable = () => {
		table.DataTable().ajax.reload();
	}
});