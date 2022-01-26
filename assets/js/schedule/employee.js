const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const table_sch = angular.element('#schTable');
const source = table.data('source');
const source_sch = table_sch.data('source');
const app = angular.module('schEmp', []);
app.controller('schEmp',($scope,$http) => {
	$scope.closeSch = () => {
		angular.element('.card-schedule').addClass('d-none');
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
	table_sch.DataTable({
		"sDom" : 'tipr',
		"processing": true,
		"serverSide": true,
		"responsive": true,
		"order": [],
		"ajax": {
			"url": source_sch,
			"type": "POST"
		},
		"columnDefs": [{ 
			"targets": [ 0,-1 ],
			"orderable": false
		}]
	});
	$scope.getsch = (nik) => {
		angular.element('.card-schedule').removeClass('d-none');
		$scope.schId = nik;
		$http.get(angular.element('.card-schedule').data('source')+$scope.schId).then((res) => {
			$scope.getSchName = res.data.getSchName;
			$scope.getSchDate= res.data.getSchDate;
			table_sch.DataTable().ajax.reload();
		});
	}
	$scope.getToday = () => {
		$http.get(base+'sch_emp_tdy').then((res) => {
			table_sch.DataTable().ajax.reload();
			$scope.getSchDate= res.data.date;
		});
	}
	$scope.getYesterday = () => {
		$http.get(base+'sch_emp_ytd').then((res) => {
			table_sch.DataTable().ajax.reload();
			$scope.getSchDate= res.data.date;
		});
	}
	$scope.getTomorrow = () => {
		$http.get(base+'sch_emp_tmr').then((res) => {
			table_sch.DataTable().ajax.reload();
			$scope.getSchDate= res.data.date;
		});
	}
});