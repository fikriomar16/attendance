<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// $route['default_controller'] = 'welcome';
// $route['404_override'] = '';
// $route['translate_uri_dashes'] = FALSE;

$route = [
	'default_controller' => 'AdminController',
	'404_override' => 'Custom404',
	'translate_uri_dashes' => FALSE,
	
	'test' => 'TestingController',
	'reset_test' => 'TestingController/tr_all_tier',

	'login' => 'AuthController',
	'auth' => 'AuthController/check_auth',
	'logout' => 'AuthController/logout',

	'dashboard' => 'AdminController',
	'late' => 'AdminController/late_page',

	'attendance' => 'AttendanceController',
	'attendance/employee' => 'AttendanceController/employee',
	'attendance/internal' => 'AttendanceController/internal',
	'attendance/visitor' => 'AttendanceController/visitor',
	'attendance/dt_employee' => 'AttendanceController/dt_employee',
	'attendance/dt_internal' => 'AttendanceController/dt_internal',
	'attendance/dt_visitor' => 'AttendanceController/dt_visitor',
	'attendance/sch_employee' => 'AttendanceController/sch_employee',
	'attendance/sch_internal' => 'AttendanceController/sch_internal',
	'attendance/sch_visitor' => 'AttendanceController/sch_visitor',
	'attresume_emp/(:any)' => 'AttendanceController/attresume_emp/$1',

	'management' => 'ManagementController',
	'management/employee' => 'ManagementController/employee',
	'management/internal' => 'ManagementController/internal',
	'management/visitor' => 'ManagementController/visitor',
	'management/departement' => 'ManagementController/departement',
	'management/dt_dept' => 'ManagementController/dt_dept',
	'management/dt_employee' => 'ManagementController/dt_employee',
	'management/dt_internal' => 'ManagementController/dt_internal',
	'management/dt_visitor' => 'ManagementController/dt_visitor',

	'schedule' => 'ScheduleController',
	'schedule/employee' => 'ScheduleController/employee',
	'schedule/internal' => 'ScheduleController/internal',
	'schedule/dt_employee' => 'ScheduleController/dt_employee',
	'schedule/dt_internal' => 'ScheduleController/dt_internal',
	'schedule/sch_employee' => 'ScheduleController/sch_employee',
	'schedule/sch_internal' => 'ScheduleController/sch_internal',
	'sch_emp_ytd' => 'ScheduleController/get_yesterday_emp',
	'sch_emp_tmr' => 'ScheduleController/get_tomorrow_emp',
	'sch_emp_tdy' => 'ScheduleController/get_today_emp',
	'sch_int_ytd' => 'ScheduleController/get_yesterday_int',
	'sch_int_tmr' => 'ScheduleController/get_tomorrow_int',
	'sch_int_tdy' => 'ScheduleController/get_today_int',
	'getresume_emp/(:any)' => 'ScheduleController/getresume_emp/$1',
	'getresume_int/(:any)' => 'ScheduleController/getresume_int/$1',

	'setup' => 'SetupController',
	'setup/gate' => 'SetupController/gate',
	'setup/card' => 'SetupController/card',
	'setup/duration' => 'SetupController/duration',
	'setup/menu' => 'SetupController/menu',
	'setup/dt_gate' => 'SetupController/dt_gate',
	'setup/dt_card' => 'SetupController/dt_card',
	'setup/dt_duration' => 'SetupController/dt_duration',
];