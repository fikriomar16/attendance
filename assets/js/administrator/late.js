const base = angular.element('body').data('home');
const table = angular.element('#dataTable');
const source = table.data('source');
const refreshPerSec = 1;
const app = angular.module('lateApp', []);
app.controller('lateController',($scope,$http) => {});