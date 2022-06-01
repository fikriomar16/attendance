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
	'auth/dt_auth' => 'AuthController/dt_auth',
	'auth/manage' => 'AuthController/manage',
	'saveAccount' => 'AuthController/saveAccount',
	'deleteAccount/(:any)' => 'AuthController/deleteAccount/$1',
	'checkUsername/(:any)' => 'AuthController/checkUsername/$1',
	'get_by_id_acc/(:any)' => 'AuthController/get_by_id_acc/$1',
	'logout' => 'AuthController/logout',

	'sideToggle/(:any)' => 'AdminController/sideToggle/$1',

	'dashboard' => 'AdminController',
	'dt_dashboard' => 'AdminController/dt_dashboard',
	'dt_dashboard2' => 'AdminController/dt_dashboard2',
	'merged_db' => 'AdminController/merged_db',
	'countEmpVis' => 'AdminController/countEmpVis',
	'late' => 'AdminController/late_page',
	'dt_late' => 'AdminController/dt_late',
	'get_late' => 'AdminController/get_late',
	'out' => 'AdminController/out_page',
	'dt_out' => 'AdminController/dt_out',
	'get_out' => 'AdminController/get_out',
	'late/reportLate' => 'AdminController/reportLate',
	'out/reportOut' => 'AdminController/reportOut',
	'searchAttendance' => 'AdminController/searchAttendance',

	'attendance' => 'AttendanceController',
	'attendance/employee' => 'AttendanceController/employee',
	'attendance/office' => 'AttendanceController/office',
	'attendance/visitor' => 'AttendanceController/visitor',
	'attendance/getShiftList' => 'AttendanceController/getShiftList',
	// set Date emp
	'attendance/att_yesterday_emp' => 'AttendanceController/att_yesterday_emp',
	'attendance/att_today_emp' => 'AttendanceController/att_today_emp',
	'attendance/att_tomorrow_emp' => 'AttendanceController/att_tomorrow_emp',
	'attendance/att_yesterday_emp_detail' => 'AttendanceController/att_yesterday_emp_detail',
	'attendance/att_today_emp_detail' => 'AttendanceController/att_today_emp_detail',
	'attendance/att_tomorrow_emp_detail' => 'AttendanceController/att_tomorrow_emp_detail',
	'att_getDate_emp/(:any)' => 'AttendanceController/att_getDate_emp/$1',
	'recapSumEmp/(:any)' => 'AttendanceController/recapSumEmp/$1',
	// set Date vis
	'attendance/att_yesterday_vis' => 'AttendanceController/att_yesterday_vis',
	'attendance/att_today_vis' => 'AttendanceController/att_today_vis',
	'attendance/att_tomorrow_vis' => 'AttendanceController/att_tomorrow_vis',
	'att_getDate_vis/(:any)' => 'AttendanceController/att_getDate_vis/$1',
	// set Date Office
	'attendance/att_yesterday_off' => 'AttendanceController/att_yesterday_off',
	'attendance/att_today_off' => 'AttendanceController/att_today_off',
	'attendance/att_tomorrow_off' => 'AttendanceController/att_tomorrow_off',
	'attendance/att_yesterday_off_detail' => 'AttendanceController/att_yesterday_off_detail',
	'attendance/att_today_off_detail' => 'AttendanceController/att_today_off_detail',
	'attendance/att_tomorrow_off_detail' => 'AttendanceController/att_tomorrow_off_detail',
	'att_getDate_off/(:any)' => 'AttendanceController/att_getDate_off/$1',
	'recapSumOff/(:any)' => 'AttendanceController/recapSumOff/$1',
	// set session
	'attendance/set_shift/(:any)' => 'AttendanceController/set_shift/$1',
	'attendance/set_dept/(:any)' => 'AttendanceController/set_dept/$1',
	'attendance/set_shift_off/(:any)' => 'AttendanceController/set_shift_off/$1',
	'attendance/set_dept_off/(:any)' => 'AttendanceController/set_dept_off/$1',
	'attresume_emp/(:any)' => 'AttendanceController/attresume_emp/$1',
	'attresume_off/(:any)' => 'AttendanceController/attresume_off/$1',
	'attresume_vis/(:any)' => 'AttendanceController/attresume_vis/$1',
	// dataTable
	'attendance/dt_employee' => 'AttendanceController/dt_employee',
	'attendance/att_sum_emp' => 'AttendanceController/att_sum_emp',
	'attendance/att_det_recap_emp' => 'AttendanceController/att_det_recap_emp',
	'attendance/att_hist_scan_emp' => 'AttendanceController/att_hist_scan_emp',
	'attendance/dt_visitor' => 'AttendanceController/dt_visitor',
	'attendance/att_hist_scan_vis' => 'AttendanceController/att_hist_scan_vis',
	'attendance/dt_office' => 'AttendanceController/dt_office',
	'attendance/att_sum_off' => 'AttendanceController/att_sum_off',
	'attendance/att_det_recap_off' => 'AttendanceController/att_det_recap_off',
	'attendance/att_hist_scan_off' => 'AttendanceController/att_hist_scan_off',
	// report
	'printAttendanceEmp' => 'AttendanceController/printAttendanceEmp',
	'rekapBulananKaryawan' => 'AttendanceController/rekapBulananKaryawan',
	'historyScanKaryawan' => 'AttendanceController/historyScanKaryawan',
	'printAttendanceOff' => 'AttendanceController/printAttendanceOff',
	'rekapBulananOffice' => 'AttendanceController/rekapBulananOffice',
	'historyScanOffice' => 'AttendanceController/historyScanOffice',
	'printAttendanceVis' => 'AttendanceController/printAttendanceVis',
	'historyScanVisitor' => 'AttendanceController/historyScanVisitor',
	// export CSV
	'exportCSV_emp' => 'AttendanceController/exportCSV_emp',
	'exportCSV_off' => 'AttendanceController/exportCSV_off',
	'exportCSV_vis' => 'AttendanceController/exportCSV_vis',

	'schedule' => 'ScheduleController',
	'schedule/employee' => 'ScheduleController/employee',
	'schedule/dt_employee' => 'ScheduleController/dt_employee',
	'schedule/sch_employee' => 'ScheduleController/sch_employee',
	'sch_emp_ytd' => 'ScheduleController/get_yesterday_emp',
	'sch_emp_tmr' => 'ScheduleController/get_tomorrow_emp',
	'sch_emp_tdy' => 'ScheduleController/get_today_emp',
	'getDate_sch/(:any)' => 'ScheduleController/getDate_sch/$1',
	'getresume_emp/(:any)' => 'ScheduleController/getresume_emp/$1',
	'schedule/empList' => 'ScheduleController/empList',
	'schedule/shiftList' => 'ScheduleController/shiftList',
	'get_by_id_employee_sch/(:any)' => 'ScheduleController/get_by_id_employee_sch/$1',
	'saveSchedule' => 'ScheduleController/saveSchedule',
	'deleteSchedule/(:any)' => 'ScheduleController/deleteSchedule/$1',
	'exportSchTemplate' => 'ScheduleController/exportSchTemplate',
	// import CSV
	'importSchCSV' => 'ScheduleController/importSchCSV',
	'processImport' => 'ScheduleController/processImport',

	'setup' => 'SetupController',
	'setup/duration' => 'SetupController/duration',
	'setup/deptList' => 'SetupController/deptList',
	'setup/deptListExcept/(:any)' => 'SetupController/deptListExcept/$1',
	'get_by_id_duration/(:any)' => 'SetupController/get_by_id_duration/$1',
	'saveDuration' => 'SetupController/saveDuration',
	'deleteDuration/(:any)' => 'SetupController/deleteDuration/$1',
	'setup/dt_duration' => 'SetupController/dt_duration',

	'setup/shift' => 'SetupController/shift',
	'setup/dt_shift' => 'SetupController/dt_shift',
	'deleteShift/(:any)' => 'SetupController/deleteShift/$1',
	'get_by_id_shift/(:any)' => 'SetupController/get_by_id_shift/$1',
	'saveShift' => 'SetupController/saveShift',
	'importDWS' => 'SetupController/importDWS',
	'processImport_DWS' => 'SetupController/processImport_DWS',

	'scanlog' => 'ScanlogController',
	'scanlog/dt_scanlog' => 'ScanlogController/dt_scanlog',
	'scanlog/filter' => 'ScanlogController/filter',
	'scanlog/getPinName' => 'ScanlogController/getPinName',
	'scanlog/getResume' => 'ScanlogController/getResume',
	'scanlog/dt_filter' => 'ScanlogController/dt_filter',
	'scanlog/getFilterData' => 'ScanlogController/getFilterData',
	'scanlog/exportFilter' => 'ScanlogController/exportFilter',

	'import_sql' => 'QueryController',
	'importSQL' => 'QueryController/importSQL',
	'processImportSQL' => 'QueryController/processImportSQL',
];