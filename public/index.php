<?php

require_once '../vendor/autoload.php'; 
require_once '../app/core/Autoload.php';

require_once '../config/config.php';
require_once '../app/helpers/helpers.php';

// Assuming Router.php is in /app/core
require_once '../app/core/Router.php';

// Run the router
// Webs Routes
$router = new Router();
$router->addRoute('GET', '/', 'HomeController@index');
$router->addRoute('GET', '/home', 'HomeController@index');
$router->addRoute('GET', '/student', 'StudentController@index');
$router->addRoute('GET', '/student/import', 'StudentController@view_import_data');
$router->addRoute('GET', '/carnet', 'CarnetController@index');
$router->addRoute('GET', '/attendance', 'AttendanceController@index');
$router->addRoute('GET', '/attendance/openAttendance', 'AttendanceController@open_attendance');
$router->addRoute('GET', '/attendance/searchStudentByCode', 'AttendanceController@open_attendance');
$router->addRoute('GET', '/report/student', 'ReportController@view_student_report');
$router->addRoute('GET', '/report/group', 'ReportController@view_group_report');
$router->addRoute('GET', '/report/resume/month', 'ReportController@view_resume_month_report');
$router->addRoute('GET', '/report/resume/daily', 'ReportController@view_resume_day_report');
$router->addRoute('GET', '/setting', 'SettingController@index');
$router->addRoute('GET', '/backup', 'BackupController@index');




$router->addRoute('GET', '/details', 'HomeController@details');
$router->addRoute('GET', '/param/{id}', 'HomeController@param');
$router->addRoute('GET', '/user/{tipo}/buscar/{nivel}/{id}', 'HomeController@detalle');


//Api Endpoints
$router->addRoute('GET', '/student/getAll', 'StudentController@show');
$router->addRoute('POST', '/student/readExcel', 'StudentController@read_from_Excel');
$router->addRoute('POST', '/student/importData', 'StudentController@import_data_file');
$router->addRoute('POST', '/student/searchByDniOrName', 'StudentController@search_by_dni_or_name');
$router->addRoute('GET', '/student/getTotalStudents', 'StudentController@get_total_students');


$router->addRoute('GET', '/carnet/generateCarnet', 'CarnetController@generate_carnet_pdf');
$router->addRoute('GET', '/carnet/previewCarnet', 'CarnetController@preview_single_carnet_pdf');

$router->addRoute('POST', '/attendance/registerAttendance', 'AttendanceController@register_attendance');
$router->addRoute('POST', '/attendance/openNewDay', 'AttendanceController@register_new_day');
$router->addRoute('POST', '/attendance/getConfig', 'AttendanceController@get_config_attendance');
$router->addRoute('POST', '/attendance/getListRegisteredLastDay', 'AttendanceController@get_list_attendance_last_day');
$router->addRoute('POST', '/attendance/getListStatusAttendance', 'AttendanceController@get_list_status_attendance');
$router->addRoute('POST', '/attendance/EditIfRegistered', 'AttendanceController@edit_attendance_registered');
$router->addRoute('POST', '/attendance/saveAttendance', 'AttendanceController@save_attendance');

$router->addRoute('POST', '/report/RecordByStudent', 'ReportController@record_by_student');
$router->addRoute('POST', '/report/RecordByGroup', 'ReportController@record_by_group');
$router->addRoute('GET', '/report/getGroupFilterOptions', 'ReportController@load_data_for_group_filter');
$router->addRoute('GET', '/report/RecordByResumeDay', 'ReportController@record_by_day_report');

$router->addRoute('POST', '/setting/getConfig', 'SettingController@load_data_setting');
$router->addRoute('POST', '/setting/saveConfig', 'SettingController@save_setting');

$router->addRoute('GET', '/backup/export', 'BackupController@export');
$router->addRoute('POST', '/backup/import', 'BackupController@import');

$router->run()

?>
