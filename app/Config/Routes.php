<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'NewAdmission::index');

// Auth routes
$routes->group('auth', ['namespace' => 'App\Controllers\User'], function ($routes) {
    $routes->get('login', 'UserAuth::login');
    $routes->get('google_login', 'UserAuth::googleAuth');
    $routes->get('google_callback', 'UserAuth::googleCallback');
    $routes->get('logout', 'UserAuth::logout');
});

// New Admission System
$routes->get('new-admission', 'NewAdmission::index');
$routes->get('new-admission/pre-check/(:num)', 'NewAdmission::pre_check/$1');
$routes->post('new-admission/check-id', 'NewAdmission::check_id_card');
$routes->get('new-admission/register/(:num)', 'NewAdmission::register/$1');
$routes->get('new-admission/status', 'NewAdmission::status');
$routes->post('new-admission/save', 'NewAdmission::save_register');

// Routes for editing admission data
$routes->get('admission/edit/(:num)', 'Admission::edit_student/$1');
$routes->post('admission/update', 'Admission::update_student');


$routes->get('login', 'Login::login_student');
$routes->get('loginAdmin', 'Login::login_admin');
$routes->get('loginGoogle', 'Login::login_admin');
$routes->get('CloseSystem', 'Login::CloseSystem');

// Main
$routes->get('Statistic/(:any)', 'Statistic::AllStatistic/$1');
$routes->get('RegStudent/(:num)/(:any)', 'Admission::reg_student/$1/$2');
$routes->get('CheckRegister', 'Welcome::CheckRegister');
$routes->post('CheckStudentRegister', 'Admission::CheckStudentRegister');
$routes->get('CheckOnOffRegis', 'Admission::CheckOnOffRegis');

// Student
$routes->get('StudentLogin', 'Students::StudentLogin');
$routes->post('StudentCheckLogin', 'Login::CheckLogin');
$routes->get('StudentHome', 'Students::StudentsHome');
$routes->get('StudentsEdit', 'Students::StudentsEdit');
$routes->get('StudentsStatus', 'Students::StudentsStatus');
$routes->get('Students/Logout', 'Students::logoutStudent');
$routes->get('Students/Print', 'Students::PDFForStudent');

// Confirm
$routes->get('Confirm', 'Confirm::StudentsConfirm');
$routes->get('Confirm/Logout', 'Login::Confirmlogout');
$routes->get('Confirm/Print', 'Confirm::PDFForStudent');

// Admin
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->get('/', 'AdmissionController::dashboard'); // New Admin Dashboard Route
    $routes->get('system/(:any)', 'Setting::AdminSystem/$1');
    
    // Print
    $routes->get('Print/(:any)', 'PrintController::PagePrintMain/$1');
    $routes->get('Print/(:any)/(:any)/(:num)', 'Admission::pdf_type_all/$1/$2/$3'); // Kept in Admission as per migration
    $routes->get('Print/DownloadPDF/(:any)/(:any)/(:any)/(:any)', 'PrintController::DownloadPDF/$1/$2/$3/$4');
    $routes->post('print/ChangeCouse', 'PrintController::ChangeCouse');

    // Admission / Recruitment
    $routes->get('Student/Update/(:any)', 'Admission::update_recruitstudent/$1'); // View might use this
    $routes->get('Recruitment/(:num)', 'Admission::index/$1');
    $routes->get('Recruitment/CheckData/(:any)', 'Admission::edit_recruitstudent/$1');
    // $routes->get('admission/add', 'Admission::add'); // Method not found in migration
    
    // Admission Actions (POST)
    $routes->post('admission/switch_regis', 'Admission::switch_regis');
    $routes->post('admission/switch_system', 'Admission::switch_system');
    $routes->post('admission/switch_report', 'Admission::switch_report');
    $routes->post('admission/quotaType', 'Admission::quotaType');
    $routes->post('admission/switch_year', 'Admission::switch_year');
    $routes->post('admission/update_recruitstudent/(:num)', 'Admission::update_recruitstudent/$1');
    $routes->post('admission/delete_recruitstudent/(:num)', 'Admission::delete_recruitstudent/$1');
    $routes->post('admission/confrim_report/(:num)', 'Admission::confrim_report/$1');
    $routes->post('admission/SchoolList', 'Admission::SchoolList');
    $routes->post('admission/SelectThailand', 'Admission::SelectThailand');
    $routes->post('admission/DataRecruitment', 'Admission::DataRecruitment');
    
    // Confirm
    $routes->get('PrintConfirm/(:any)', 'Confirm::PagePrintConnfirm/$1');
    $routes->get('Confirm/pdfConfirm/(:any)/(:any)', 'Confirm::pdfConfirm/$1/$2'); // Add this if missing

    // Surrender
    $routes->get('Surrender/(:any)', 'Surrender::PageSurrenderMain/$1');
    $routes->post('surrender/UpdateSurrender', 'Surrender::UpdateSurrender');

    // Quiz
    $routes->get('Quiz/(:any)', 'Quiz::PageQuizMain/$1');
    $routes->post('quiz/UpdateStatusQuiz', 'Quiz::UpdateStatusQuiz');

    // Statistic
    $routes->get('Statistic/(:any)', 'Statistic::statistic_student/$1');
    $routes->post('Statistic/ChartStudentsRecruitM1', 'Statistic::ChartStudentsRecruitM1');
    $routes->post('Statistic/ChartStudentsRecruitM4', 'Statistic::ChartStudentsRecruitM4');
    $routes->post('Statistic/ChartStudentsRecruitMOther', 'Statistic::ChartStudentsRecruitMOther');

    // News
    $routes->get('news', 'News::index');
    $routes->get('news/add', 'News::add');
    
    // Setting
    $routes->post('setting/UpdateQuotaInCourse', 'Setting::UpdateQuotaInCourse');
    $routes->post('setting/UpdateDatatimeOnoffRegis', 'Setting::UpdateDatatimeOnoffRegis');

    $routes->get('logout', 'Admission::logout');
});

// New Admin Panel
$routes->group('skjadmin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');

    // Recruit Management
    $routes->get('recruits', 'RecruitController::index');
    $routes->post('recruits/ajax', 'RecruitController::getRecruitsAjax');
    $routes->get('recruits/view/(:num)', 'RecruitController::view/$1');
    $routes->get('recruits/edit/(:num)', 'RecruitController::edit/$1');
    $routes->post('recruits/update/(:num)', 'RecruitController::update/$1');
    $routes->get('recruits/delete/(:num)', 'RecruitController::delete/$1');
    $routes->post('recruits/update-status', 'RecruitController::updateStatus');
    $routes->get('recruits/print/(:num)', 'RecruitController::print/$1');

    // Course Management
    $routes->get('courses', 'CourseController::index');
    $routes->get('courses/add', 'CourseController::add');
    $routes->post('courses/create', 'CourseController::create');
    $routes->get('courses/edit/(:num)', 'CourseController::edit/$1');
    $routes->post('courses/update/(:num)', 'CourseController::update/$1');
    $routes->get('courses/delete/(:num)', 'CourseController::delete/$1');

    // Quota Management
    $routes->get('quotas', 'QuotaController::index');
    $routes->get('quotas/add', 'QuotaController::add');
    $routes->post('quotas/create', 'QuotaController::create');
    $routes->get('quotas/edit/(:num)', 'QuotaController::edit/$1');
    $routes->post('quotas/update/(:num)', 'QuotaController::update/$1');
    $routes->post('quotas/updateStatus', 'QuotaController::updateStatus');
    $routes->get('quotas/delete/(:num)', 'QuotaController::delete/$1');

    // Settings
    $routes->get('settings', 'SettingController::index');
    $routes->post('settings/update_status', 'SettingController::update_status');
    $routes->post('settings/update_year', 'SettingController::update_year');
    $routes->post('settings/update_comment', 'SettingController::update_comment');

    // User Management
    $routes->get('users', 'UserController::index');
    $routes->get('users/search', 'UserController::search_personnel');
    $routes->post('users/create', 'UserController::create');
    $routes->get('users/delete/(:num)', 'UserController::delete/$1');

    // Reports
    $routes->get('reports', 'ReportController::index');
    $routes->get('reports/print_all', 'ReportController::print_all');

    // Surrender
    $routes->get('surrender', 'Surrender::index');
    $routes->post('surrender/update', 'Surrender::UpdateSurrender');
    $routes->get('surrender/print/(:num)', 'Surrender::print/$1');
});

// Compatibility / Legacy Routes (Mapping old CI3 controller names to new CI4 controllers)
$routes->post('admin/Control_admin_admission/DataRecruitment', 'Admin\Admission::DataRecruitment');
$routes->post('admin/Control_admin_admission/switch_regis', 'Admin\Admission::switch_regis');
// Add more if needed based on view inspection

// Public Form Submissions
$routes->post('login/confirm_student', 'Login::CheckLoginConfirmStudentNew');
$routes->post('admission/get_student_status', 'Admission::get_student_status');
$routes->post('control_admission/reg_insert', 'Admission::reg_insert');
$routes->post('control_admission/check_print', 'Admission::check_print');
$routes->post('control_admission/SchoolList', 'Admission::SchoolList');
$routes->post('control_admission/data_user', 'Admission::data_user');
$routes->post('control_login/validlogin', 'Login::validlogin');
