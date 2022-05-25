const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const refreshPerMin = 0.3;
const app = angular.module('dashboardApp', []);
app.controller('dashboardController',($scope,$http) => {
	table.DataTable({
		"sDom":"tir",
		"bSort": true,
		"pageLength": 50,
		"processing": false,
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
		angular.element('.text-load').text('Loading......');
		angular.element('.btn-load').removeClass('btn-primary');
		angular.element('.btn-load').addClass('btn-secondary');
		angular.element('.icon-load').addClass('fa-clock');
		angular.element('.icon-load').removeClass('fa-sync');
		$http.get(base+'countEmpVis').then((res) => {
			$scope.countEmployee = res.data.countEmployee;
			$scope.countEmployeeTotal = res.data.countEmployeeTotal;
			$scope.countVisitor = res.data.countVisitor;
			$scope.countOffice = res.data.countOffice;
			$scope.countOfficeTotal = res.data.countOfficeTotal;
			$scope.countEmployeeMinyak = res.data.countEmployeeMinyak;
			$scope.countEmployeeMinyakTotal = res.data.countEmployeeMinyakTotal;
			$scope.countEmployeeSayur = res.data.countEmployeeSayur;
			$scope.countEmployeeSayurTotal = res.data.countEmployeeSayurTotal;
			$scope.countEmployeeBumbu = res.data.countEmployeeBumbu;
			$scope.countEmployeeBumbuTotal = res.data.countEmployeeBumbuTotal;
			$scope.countEmployeeBawang = res.data.countEmployeeBawang;
			$scope.countEmployeeBawangTotal = res.data.countEmployeeBawangTotal;
			$scope.countOfficeAccounting = res.data.countOfficeAccounting;
			$scope.countOfficeAccountingTotal = res.data.countOfficeAccountingTotal;
			$scope.countOfficeHR = res.data.countOfficeHR;
			$scope.countOfficeHRTotal = res.data.countOfficeHRTotal;
			$scope.countOfficeQC = res.data.countOfficeQC;
			$scope.countOfficeQCTotal = res.data.countOfficeQCTotal;
			$scope.countOfficePPIC = res.data.countOfficePPIC;
			$scope.countOfficePPICTotal = res.data.countOfficePPICTotal;
			$scope.countOfficeTechnic = res.data.countOfficeTechnic;
			$scope.countOfficeTechnicTotal = res.data.countOfficeTechnicTotal;
			$scope.countOfficeWarehouse = res.data.countOfficeWarehouse;
			$scope.countOfficeWarehouseTotal = res.data.countOfficeWarehouseTotal;
			$scope.countOfficePurchasing = res.data.countOfficePurchasing;
			$scope.countOfficePurchasingTotal = res.data.countOfficePurchasingTotal;
			angular.element('.show-count').removeClass('d-none');
			angular.element('.text-load').text(' Reload Data ');
			angular.element('.btn-load').addClass('btn-primary');
			angular.element('.btn-load').removeClass('btn-secondary');
			angular.element('.icon-load').removeClass('fa-clock');
			angular.element('.icon-load').addClass('fa-sync');
		});
	}
	$scope.dataScan = (param) => {
		if (param == 'show') {
			angular.element('.btn-scan').addClass('d-none');
			angular.element('.btn-scan-hide').removeClass('d-none');
			angular.element('.table-scan').removeClass('d-none');
		}
		if (param == 'hide') {
			angular.element('.btn-scan').removeClass('d-none');
			angular.element('.btn-scan-hide').addClass('d-none');
			angular.element('.table-scan').addClass('d-none');
		}
	}
	$scope.getCount();
	$scope.reloadAll = () => {
		$scope.getCount();
		$scope.reloadTable();
	}
	setInterval(() => {
		$scope.reloadAll();
	}, refreshPerMin * 60000);
});