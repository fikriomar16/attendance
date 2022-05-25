const base = angular.element('body').data('home');
const source = angular.element('#dataTable').data('source');
const refreshPerMin = 0.3;
const app = angular.module('outApp', []);
app.controller('outController',($scope,$http) => {
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
		"columnDefs": []
	});
	$scope.reloadTable = () => {
		table.ajax.reload();
	}
	setInterval(() => {
		$scope.reloadTable();
	}, refreshPerMin * 60000);
});