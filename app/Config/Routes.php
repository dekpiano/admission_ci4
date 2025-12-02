<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'User\UserControlNewAdmission::index');

// Auth routes
$routes->group('auth', ['namespace' => 'App\Controllers\User'], function ($routes) {
    $routes->get('login', 'UserControlAuth::login');
    $routes->get('google_login', 'UserControlAuth::googleAuth');
    $routes->get('google_callback', 'UserControlAuth::googleCallback');
    $routes->get('logout', 'UserControlAuth::logout');
});

$routes->get('new-admission', 'User\UserControlNewAdmission::index');
$routes->get('new-admission/manual', 'User\UserControlAdmission::manual');
$routes->get('new-admission/manual-report', 'User\UserControlAdmission::manual_report');
$routes->get('new-admission/pre-check/(:num)', 'User\UserControlNewAdmission::pre_check/$1');
$routes->post('new-admission/check-id', 'User\UserControlNewAdmission::check_id_card');
$routes->get('new-admission/register/(:num)', 'User\UserControlNewAdmission::register/$1');
$routes->get('new-admission/status', 'User\UserControlNewAdmission::status');
$routes->get('new-admission/school-search', 'User\UserControlNewAdmission::ajax_school_search'); // Route for Select2 school search
$routes->post('new-admission/save', 'User\UserControlNewAdmission::save_register');

// Image Proxy for remote images
$routes->get('image-proxy', 'User\ImageProxy::index');

// Routes for editing admission data
$routes->get('admission/edit/(:num)', 'User\UserControlAdmission::edit_student/$1');
$routes->post('admission/update', 'User\UserControlAdmission::update_student');


$routes->get('login', 'User\UserControlLogin::login_student');
$routes->get('loginAdmin', 'User\UserControlLogin::login_admin');
$routes->get('loginGoogle', 'User\UserControlLogin::login_admin');
$routes->get('CloseSystem', 'User\UserControlLogin::CloseSystem');

// Main
$routes->get('Statistic/(:any)', 'User\UserControlStatistic::AllStatistic/$1');
$routes->get('RegStudent/(:num)/(:any)', 'User\UserControlAdmission::reg_student/$1/$2');
$routes->get('CheckRegister', 'User\UserControlWelcome::CheckRegister');
$routes->post('CheckStudentRegister', 'User\UserControlAdmission::CheckStudentRegister');
$routes->get('CheckOnOffRegis', 'User\UserControlAdmission::CheckOnOffRegis');

// Student
$routes->get('StudentLogin', 'User\UserControlStudents::StudentLogin');
$routes->post('StudentCheckLogin', 'User\UserControlLogin::CheckLogin');
$routes->get('StudentHome', 'User\UserControlStudents::StudentsHome');
$routes->get('StudentsEdit', 'User\UserControlStudents::StudentsEdit');
$routes->get('StudentsStatus', 'User\UserControlStudents::StudentsStatus');
$routes->get('Students/Logout', 'User\UserControlStudents::logoutStudent');
$routes->get('Students/Print', 'User\UserControlStudents::PDFForStudent');

// Confirmation (Online Registration)
$routes->group('confirmation', ['namespace' => 'App\Controllers\User'], function ($routes) {
    $routes->get('/', 'UserControlConfirmation::login'); // Default to login
    $routes->get('login', 'UserControlConfirmation::login');
    $routes->post('check', 'UserControlConfirmation::checkStudent');
    $routes->get('form', 'UserControlConfirmation::form');
    $routes->post('save', 'UserControlConfirmation::save');
    $routes->get('save', 'UserControlConfirmation::form'); // Redirect GET requests to form to prevent 404
    $routes->get('pdf', 'UserControlConfirmation::pdf');
    $routes->get('logout', 'UserControlConfirmation::logout');
});

// Admin
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->get('/', 'AdminControlDashboard::index'); // Updated to use Dashboard controller directly
    // Print (Legacy/Admission)
    $routes->get('Print/(:any)/(:any)/(:num)', 'AdminControlAdmission::pdf_type_all/$1/$2/$3'); 

    // Admission / Recruitment
    $routes->get('Student/Update/(:any)', 'AdminControlAdmission::update_recruitstudent/$1'); 
    $routes->get('Recruitment/(:num)', 'AdminControlAdmission::index/$1');
    $routes->get('Recruitment/CheckData/(:any)', 'AdminControlAdmission::edit_recruitstudent/$1');
    
    // Admission Actions (POST)
    $routes->post('admission/switch_regis', 'AdminControlAdmission::switch_regis');
    $routes->post('admission/switch_system', 'AdminControlAdmission::switch_system');
    $routes->post('admission/switch_report', 'AdminControlAdmission::switch_report');
    $routes->post('admission/quotaType', 'AdminControlAdmission::quotaType');
    $routes->post('admission/switch_year', 'AdminControlAdmission::switch_year');
    $routes->post('admission/update_recruitstudent/(:num)', 'AdminControlAdmission::update_recruitstudent/$1');
    $routes->post('admission/delete_recruitstudent/(:num)', 'AdminControlAdmission::delete_recruitstudent/$1');
    $routes->post('admission/confrim_report/(:num)', 'AdminControlAdmission::confrim_report/$1');
    $routes->post('admission/SchoolList', 'AdminControlAdmission::SchoolList');
    $routes->post('admission/SelectThailand', 'AdminControlAdmission::SelectThailand');
    $routes->post('admission/DataRecruitment', 'AdminControlAdmission::DataRecruitment');
    
    // Surrender
    $routes->get('Surrender/(:any)', 'AdminControlSurrender::PageSurrenderMain/$1');
    $routes->post('surrender/UpdateSurrender', 'AdminControlSurrender::UpdateSurrender');
    $routes->get('surrender/print/(:num)', 'AdminControlSurrender::print/$1');

    // Quiz
    $routes->get('Quiz/(:any)', 'AdminControlQuiz::PageQuizMain/$1');
    $routes->post('quiz/UpdateStatusQuiz', 'AdminControlQuiz::UpdateStatusQuiz');

    // Statistic
    $routes->get('Statistic/(:any)', 'AdminControlStatistic::statistic_student/$1');
    $routes->post('Statistic/ChartStudentsRecruitM1', 'AdminControlStatistic::ChartStudentsRecruitM1');
    $routes->post('Statistic/ChartStudentsRecruitM4', 'AdminControlStatistic::ChartStudentsRecruitM4');
    $routes->post('Statistic/ChartStudentsRecruitMOther', 'AdminControlStatistic::ChartStudentsRecruitMOther');

    // News
    $routes->get('news', 'AdminControlNews::index');
    $routes->get('news/add', 'AdminControlNews::add');
    
    $routes->get('logout', 'AdminControlAdmission::logout');
});

// New Admin Panel
$routes->group('skjadmin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->get('/', 'AdminControlDashboard::index');
    $routes->get('dashboard', 'AdminControlDashboard::index');
    $routes->get('dashboard/(:num)', 'AdminControlDashboard::index/$1');

    // Recruit Management
    $routes->get('recruits', 'AdminControlRecruit::index');
    $routes->post('recruits/ajax', 'AdminControlRecruit::getRecruitsAjax');
    $routes->get('recruits/view/(:num)', 'AdminControlRecruit::view/$1');
    $routes->get('recruits/edit/(:num)', 'AdminControlRecruit::edit/$1');
    $routes->post('recruits/update/(:num)', 'AdminControlRecruit::update/$1');
    $routes->get('recruits/delete/(:num)', 'AdminControlRecruit::delete/$1');
    $routes->post('recruits/update-status', 'AdminControlRecruit::updateStatus');
    $routes->get('recruits/print/(:num)', 'AdminControlRecruit::print/$1');

    // Course Management
    $routes->get('courses', 'AdminControlCourse::index');
    $routes->get('courses/add', 'AdminControlCourse::add');
    $routes->post('courses/create', 'AdminControlCourse::create');
    $routes->get('courses/edit/(:num)', 'AdminControlCourse::edit/$1');
    $routes->post('courses/update/(:num)', 'AdminControlCourse::update/$1');
    $routes->get('courses/delete/(:num)', 'AdminControlCourse::delete/$1');

    // Quota Management
    $routes->get('quotas', 'AdminControlQuota::index');
    $routes->get('quotas/add', 'AdminControlQuota::add');
    $routes->post('quotas/create', 'AdminControlQuota::create');
    $routes->get('quotas/edit/(:num)', 'AdminControlQuota::edit/$1');
    $routes->post('quotas/update/(:num)', 'AdminControlQuota::update/$1');
    $routes->post('quotas/updateStatus', 'AdminControlQuota::updateStatus');
    $routes->get('quotas/delete/(:num)', 'AdminControlQuota::delete/$1');

    // Settings
    $routes->get('settings', 'AdminControlSetting::index');
    $routes->post('settings/update_status', 'AdminControlSetting::update_status');
    $routes->post('settings/update_year', 'AdminControlSetting::update_year');
    $routes->post('settings/update_comment', 'AdminControlSetting::update_comment');
    $routes->post('settings/update_dates', 'AdminControlSetting::update_dates');

    // User Management
    $routes->get('users', 'AdminControlUser::index');
    $routes->get('users/search', 'AdminControlUser::search_personnel');
    $routes->post('users/create', 'AdminControlUser::create');
    $routes->get('users/delete/(:num)', 'AdminControlUser::delete/$1');

    // Reports
    $routes->get('reports', 'AdminControlReport::index');
    $routes->get('reports/print_all', 'AdminControlReport::print_all');

    // Surrender
    $routes->get('surrender', 'AdminControlSurrender::index');
    $routes->post('surrender/update', 'AdminControlSurrender::UpdateSurrender');
    $routes->get('surrender/print/(:num)', 'AdminControlSurrender::print/$1');

    // Service Area Schools
    $routes->get('service-area-schools', 'AdminServiceAreaSchools::index');
    $routes->get('service-area-schools/search-all', 'AdminServiceAreaSchools::search_all_schools');
    $routes->post('service-area-schools/add', 'AdminServiceAreaSchools::add');
    $routes->post('service-area-schools/delete/(:num)', 'AdminServiceAreaSchools::delete/$1');

});

// Compatibility / Legacy Routes (Mapping old CI3 controller names to new CI4 controllers)
$routes->post('admin/Control_admin_admission/DataRecruitment', 'Admin\AdminControlAdmission::DataRecruitment');
$routes->post('admin/Control_admin_admission/switch_regis', 'Admin\AdminControlAdmission::switch_regis');
// Add more if needed based on view inspection

// Public Form Submissions
$routes->post('login/confirm_student', 'User\UserControlLogin::CheckLoginConfirmStudentNew');
$routes->post('admission/get_student_status', 'User\UserControlAdmission::get_student_status');
$routes->post('control_admission/reg_insert', 'User\UserControlAdmission::reg_insert');
$routes->post('control_admission/check_print', 'User\UserControlAdmission::check_print');
$routes->post('control_admission/SchoolList', 'User\UserControlAdmission::SchoolList');
$routes->get('control_admission/pdf/(:num)', 'User\UserControlAdmission::pdf/$1'); // Added PDF route
$routes->post('control_admission/data_user', 'User\UserControlAdmission::data_user');
$routes->post('control_login/validlogin', 'User\UserControlLogin::validlogin');
