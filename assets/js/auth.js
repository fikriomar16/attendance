const base = angular.element('body').data('home');
const url = angular.element('#loginForm').attr('action');
const app = angular.module('loginPage', []);
app.controller('loginController',($scope,$http) => {
	$scope.auth = () => {
		$http({
			method:"POST",
			url: url,
			data: {
				'username' : $scope.username,
				'password' : $scope.password
			}
		}).then((res) => {
			if (res.data.error) {
				$scope.errorMsg = res.data.error;
				angular.element('.alert-danger').removeClass('d-none');
				angular.element('.alert-success').addClass('d-none');
			} else {
				$scope.successMsg = res.data.success;
				angular.element('#loginForm').addClass('d-none');
				angular.element('.alert-danger').addClass('d-none');
				angular.element('.alert-success').removeClass('d-none');
				window.location.href = base+'dashboard';
			}
		});
	}
});
app.directive('toPassword',() => {
	return {
		restrict: 'A',
		link: ($scope,elem,attrs) => {
			elem.bind('keydown', (e) => {
				let code = e.keyCode || e.which;
				if (code === 13) {
					e.preventDefault();
					angular.element('#password').focus();
				}
			});
		}
	}
});
app.directive('toLogin',() => {
	return {
		restrict: 'A',
		link: ($scope,elem,attrs) => {
			elem.bind('keydown', (e) => {
				let code = e.keyCode || e.which;
				if (code === 13) {
					e.preventDefault();
					angular.element('.btn-user').click();
				}
			});
		}
	}
});