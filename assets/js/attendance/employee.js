const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const app = angular.module('attEmployee', []);
app.controller('attEmployee',($scope,$http) => {
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
	$scope.search = () => {
		table.DataTable().search($scope.searchInTable).draw();
	}
	$scope.reloadTable = () => {
		table.DataTable().ajax.reload();
	}
	$scope.show = (nik) => {}
	$scope.closeShow = () => {}
	$scope.getToday = () => {}
	$scope.getTomorrow = () => {}
	$scope.getYesterday = () => {}
	$scope.getAttToday = () => {}
	$scope.getAttTomorrow = () => {}
	$scope.getAttYesterday = () => {}
});