const table = angular.element('#dataTable');
const source = table.data('source');
const app = angular.module('setDur', []);
app.controller('setDur',($scope,$http) => {
	$scope.edit = (id) => {
	}
	$scope.delete = (id) => {
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
});