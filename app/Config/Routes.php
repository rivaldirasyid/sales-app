<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//login rutes
$routes->get('auth', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->get('auth/login', 'Auth::login');
$routes->post('/login/action', 'Auth::loginAction');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('logout', 'Auth::logout');
$routes->get('auth/register', 'Auth::register');
$routes->get('register', 'Auth::register');
$routes->post('auth/registerAction', 'Auth::registerAction');


$routes->group('dashboard', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('data', 'Dashboard::getDashboardData');
    $routes->get('data/(:num)', 'Dashboard::getDashboardData/$1');

});

$routes->group('performance', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('/', 'Performance::index');
    $routes->get('data', 'Performance::getPerformanceData'); // Sesuaikan jika menggunakan '/performance/data'
});

$routes->group('', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('/dashboard', 'Dashboard::index');
    $routes->get('/tables', 'Pages::tables');
    $routes->get('/customers', 'Pages::customers');
    $routes->get('/sales-presales', 'Pages::salesPresales');
    $routes->get('/stages', 'Pages::stages');
});

$routes->group('prospect', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('/', 'Prospect::index');                 // Tampilkan daftar prospek
    $routes->get('create', 'Prospect::create');           // Tampilkan form create
    $routes->post('store', 'Prospect::store');            // Simpan data prospek baru
    $routes->get('view/(:num)', 'Prospect::view/$1');     // Tampilkan detail prospek
    $routes->get('edit/(:num)', 'Prospect::edit/$1');     // Tampilkan form edit
    $routes->post('update/(:num)', 'Prospect::update/$1'); // Simpan perubahan prospek
    $routes->get('delete/(:num)', 'Prospect::delete/$1'); // Hapus prospek
    $routes->get('/export', 'Prospect::export');
});


$routes->group('financial', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('create/(:num)', 'Financial::create/$1'); // Form untuk membuat data financial baru berdasarkan prospect_id
    $routes->post('store', 'Financial::store'); // Proses penyimpanan data financial
});

$routes->group('milestone', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('create/(:num)/(:any)', 'Milestone::create/$1/$2'); // Form untuk menambah milestone baru
    $routes->post('store', 'Milestone::store'); // Proses penyimpanan milestone baru
});

$routes->group('financial', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('/', 'Financial::index'); // Tampilkan daftar financial
    $routes->get('create/(:num)', 'Financial::create/$1'); // Form untuk menambah data financial baru berdasarkan prospect_id
    $routes->post('store', 'Financial::store'); // Proses penyimpanan data financial
});

$routes->group('milestone', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('/', 'Milestone::index');                        // Daftar milestone
    $routes->get('create/(:num)/(:any)', 'Milestone::create/$1/$2'); // Form milestone baru
    $routes->post('store', 'Milestone::store');                   // Simpan milestone baru
    $routes->get('view/(:num)', 'Milestone::view/$1');            // Detail milestone
    $routes->get('edit/(:num)', 'Milestone::edit/$1');            // Form edit milestone
    $routes->post('updateProgress/(:num)', 'Milestone::updateProgress/$1'); // Update progress milestone
    $routes->get('addDocument/(:num)', 'Milestone::addDocument/$1');
    $routes->post('updateDocument/(:num)', 'Milestone::updateDocument/$1'); // Rute untuk menambah document
});

$routes->group('project', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('/', 'Project::index'); // Menampilkan daftar project yang sudah complete
    $routes->get('view/(:num)', 'Project::view/$1');
    $routes->get('/export', 'Project::export');
});

$routes->group('performance', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('/', 'Performance::index');
    $routes->get('data', 'Performance::getPerformanceData'); // Sesuaikan jika menggunakan '/performance/data'
});


$routes->post('customer/store', 'Customer::store');

// Sales Management
$routes->group('sales', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('/', 'SalesManagement::salesIndex');
    $routes->get('create', 'SalesManagement::salesCreate');
    $routes->post('store', 'SalesManagement::salesStore');
    $routes->get('edit/(:num)', 'SalesManagement::salesEdit/$1');
    $routes->post('update/(:num)', 'SalesManagement::salesUpdate/$1');
    $routes->get('delete/(:num)', 'SalesManagement::salesDelete/$1');
});

// Pre-Sales Management
$routes->group('pre-sales', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('/', 'SalesManagement::preSalesIndex');
    $routes->get('create', 'SalesManagement::preSalesCreate');
    $routes->post('store', 'SalesManagement::preSalesStore');
    $routes->get('edit/(:num)', 'SalesManagement::preSalesEdit/$1');
    $routes->post('update/(:num)', 'SalesManagement::preSalesUpdate/$1');
    $routes->get('delete/(:num)', 'SalesManagement::preSalesDelete/$1');
});

$routes->group('division', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('/', 'DivisionManagement::divisionIndex');
    $routes->get('create', 'DivisionManagement::divisionCreate');
    $routes->post('store', 'DivisionManagement::divisionStore');
    $routes->get('edit/(:num)', 'DivisionManagement::divisionEdit/$1');
    $routes->post('update/(:num)', 'DivisionManagement::divisionUpdate/$1');
    $routes->get('delete/(:num)', 'DivisionManagement::divisionDelete/$1');
});

$routes->group('reports', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('pipeline_summary', 'Reports::pipeline_summary');
    $routes->get('pipeline_summary/(:num)', 'Reports::pipeline_summary/$1');
    $routes->get('pipeline_summary/data/(:num)', 'Reports::getPipelineSummaryData/$1');
    $routes->get('exportPipelineSummary/(:num)', 'Reports::exportPipelineSummary/$1');
    $routes->get('exportAllProspectsByYear/(:num)', 'Reports::exportAllProspectsByYear/$1');

});

// RKAP Management Routes
$routes->group('rkap', ['filter' => 'authCheck'], function ($routes) {
    $routes->get('/', 'Rkap::index');                        // Tampilkan daftar RKAP
    $routes->get('create', 'Rkap::create');                  // Tampilkan form untuk membuat RKAP baru
    $routes->post('save', 'Rkap::save');                     // Simpan RKAP baru atau update RKAP
    $routes->get('edit/(:num)', 'Rkap::edit/$1');            // Tampilkan form edit RKAP
    $routes->post('update/(:num)', 'Rkap::save/$1');         // Proses penyimpanan update RKAP
    $routes->get('delete/(:num)', 'Rkap::delete/$1');        // Hapus RKAP berdasarkan ID
    $routes->post('calculateForYear/(:num)', 'Rkap::calculateRkapForYear/$1'); // Kalkulasi RKAP berdasarkan tahun
    $routes->get('exportRkap/(:num)', 'Rkap::exportRkap/$1');  // Export data RKAP ke Excel berdasarkan tahun
});















