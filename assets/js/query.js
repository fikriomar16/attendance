const base = angular.element('body').data('home');
const url = angular.element('#formImportSQL').attr('action');
const app = angular.module('QueryRead', []);
app.controller('QueryRead',($scope,$http) => {
	$scope.importButton = (files) => {
		angular.element('#btnExec').addClass('d-none');
		angular.element('#resultSQL').html('');
		var fd = new FormData();
		fd.append("import_sql", files[0]);
		$http.post(url,fd,{
			withCredentials: true,
			headers: {'Content-Type': undefined },
			transformRequest: angular.identity
		}).then((res) => {
			if (res.data.error) {
				angular.element('#resultSQL').html(res.data.error);
			}
			if (res.data.success) {
				$scope.queryRes = res.data.data;
				successPopUp(res.data.success);
				angular.element('#resultSQL').html(res.data.data);
				angular.element('#btnExec').removeClass('d-none');
			}
		});
		angular.element('#formImportSQL')[0].reset();
		document.getElementById("import_sql").value = "";
	}
	$scope.execQuery = () => {
		$http({
			method:"POST",
			url:base+'processImportSQL',
			data:$scope.queryRes
		}).then((res) => {
			if (res.data.error) {
				errorNotif(res.data.error);
			} else if (res.data.success) {
				successPopUp(res.data.success);
				angular.element('#btnExec').addClass('d-none');
				angular.element('#resultSQL').html(JSON.stringify(res.data.query,null,4));
				$scope.queryRes = '';
			}
		}),(err) => {
			console.log(err);
		};
	}
});