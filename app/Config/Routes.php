<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');
$routes->get('/', 'Home::index');
$routes->get('/contact', 'Home::contact');
$routes->get('/taber', 'Taber::index');
$routes->get('/admin', 'Taber::admin');
// $routes->get('/midtrans', 'snap::index');
// $routes->get('/taber/midtrans', 'snap::index/$1/$2');
// $routes->post('/snap/finish', 'snap::finish');
$routes->get('/taber/grup', 'Taber::grup');
$routes->get('/taber/saldo', 'Taber::saldo');
$routes->get('/taber/payout', 'Taber::payout');
$routes->post('/taber/payout/request', 'Taber::payoutrequest');
$routes->get('/taber/payout/bayar/(:segment)', 'Taber::payoutbayar/$1');
$routes->get('/taber/payout/tolak/(:segment)', 'Taber::payouttolak/$1');
$routes->get('/taber/keluargrup', 'Taber::keluargrup');
$routes->get('/taber/grup/create', 'Taber::create');
$routes->get('/taber/terima/(:segment)', 'Taber::terima/$1');
$routes->get('/taber/tolak/(:segment)', 'Taber::tolak/$1');
$routes->get('/taber/bayar/(:num)/(:num)', 'snap::index/$1/$2');
$routes->get('/taber/grup/join/(:any)', 'Taber::join/$1');
$routes->get('/taber/grup/(:any)', 'Taber::detailgrup/$1');

// $routes->get('/afterpayment', 'AfterPayment::index');
$routes->resource('AfterPayment');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
