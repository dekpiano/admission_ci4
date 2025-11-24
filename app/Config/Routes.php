<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'NewAdmission::index');

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
