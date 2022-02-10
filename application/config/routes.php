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
	'dt_dashboard' => 'AdminController/dt_dashboard',
	'dt_dashboard2' => 'AdminController/dt_dashboard2',
	'merged_db' => 'AdminController/merged_db',
	'countEmpVis' => 'AdminController/countEmpVis',
	'late' => 'AdminController/late_page',

	'attendance' => 'AttendanceController',
	'attendance/employee' => 'AttendanceController/employee',
	'attendance/visitor' => 'AttendanceController/visitor',
	'attendance/sch_employee' => 'AttendanceController/sch_employee',
	// set Date emp
	'attendance/att_yesterday_emp' => 'AttendanceController/att_yesterday_emp',
	'attendance/att_today_emp' => 'AttendanceController/att_today_emp',
	'attendance/att_tomorrow_emp' => 'AttendanceController/att_tomorrow_emp',
	'attendance/att_yesterday_emp_detail' => 'AttendanceController/att_yesterday_emp_detail',
	'attendance/att_today_emp_detail' => 'AttendanceController/att_today_emp_detail',
	'attendance/att_tomorrow_emp_detail' => 'AttendanceController/att_tomorrow_emp_detail',
	// set Date vis
	'attendance/att_yesterday_vis' => 'AttendanceController/att_yesterday_vis',
	'attendance/att_today_vis' => 'AttendanceController/att_today_vis',
	'attendance/att_tomorrow_vis' => 'AttendanceController/att_tomorrow_vis',

	'attendance/set_shift/(:any)' => 'AttendanceController/set_shift/$1',
	'attresume_emp/(:any)' => 'AttendanceController/attresume_emp/$1',
	'attresume_vis/(:any)' => 'AttendanceController/attresume_vis/$1',
	// dataTable
	'attendance/dt_employee' => 'AttendanceController/dt_employee',
	'attendance/dt_visitor' => 'AttendanceController/dt_visitor',
	'attendance/att_sum_emp' => 'AttendanceController/att_sum_emp',
	'attendance/att_det_recap_emp' => 'AttendanceController/att_det_recap_emp',
	'attendance/att_hist_scan_emp' => 'AttendanceController/att_hist_scan_emp',
	'attendance/att_hist_scan_vis' => 'AttendanceController/att_hist_scan_vis',
	// report
	'printAttendanceEmp' => 'AttendanceController/printAttendanceEmp',
	'printAttendanceVis' => 'AttendanceController/printAttendanceVis',

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
	'getresume_emp/(:any)' => 'ScheduleController/getresume_emp/$1',
	'schedule/empList' => 'ScheduleController/empList',
	'get_by_id_employee_sch/(:any)' => 'ScheduleController/get_by_id_employee_sch/$1',
	'saveSchedule' => 'ScheduleController/saveSchedule',
	'deleteSchedule/(:any)' => 'ScheduleController/deleteSchedule/$1',

	'setup' => 'SetupController',
	'setup/duration' => 'SetupController/duration',
	'setup/deptList' => 'SetupController/deptList',
	'setup/deptListExcept/(:any)' => 'SetupController/deptListExcept/$1',
	'get_by_id_duration/(:any)' => 'SetupController/get_by_id_duration/$1',
	'saveDuration' => 'SetupController/saveDuration',
	'deleteDuration/(:any)' => 'SetupController/deleteDuration/$1',
	'setup/menu' => 'SetupController/menu',
	'setup/dt_duration' => 'SetupController/dt_duration',

	'scanlog' => 'ScanlogController',
	'scanlog/dt_scanlog' => 'ScanlogController/dt_scanlog',
];