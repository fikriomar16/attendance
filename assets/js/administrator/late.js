const base = angular.element('body').data('home');
const source = angular.element('#dataTable').data('source');
const refreshPerMin = 1;
const app = angular.module('lateApp', []);
app.controller('lateController',($scope,$http) => {
	$scope.showLoadingTab = () => {
		var html = '';
		html+='<tr class="tab-loading"><td colspan=4 class="text-primary font-weight-bold text-center"><div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>&nbsp; Sedang Memuat Data</td></tr>';
		angular.element('#tbodyDataTable').html(html);
	}
	$scope.getLate = () => {
		angular.element('.alert-loading').removeClass('d-none');
		// $scope.showLoadingTab();
		$http.get(source).then((res) => {
			angular.element('#tbodyDataTable').html(res.data.data);
			angular.element('.alert-loading').addClass('d-none');
		}),(err) => {
			console.error(err);
			errorNotif('Terjadi Sebuah Kesalahan');
		};
	}
	$scope.getLate();
	$scope.reloadTable = () => {
		$scope.getLate();
	}
	setInterval(() => {
		$scope.getLate();
	}, refreshPerMin * 60000);
});