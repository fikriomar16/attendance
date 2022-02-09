const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const app = angular.module('visTrace', []);
app.controller('visTrace',($scope,$http) => {
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
		// reload tab
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
			console.log(res.data);
		});
	}
});