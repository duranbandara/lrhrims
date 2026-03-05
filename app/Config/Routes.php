<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Auth
$routes->get('/',         'Auth::index');
$routes->get('login',     'Auth::index');
$routes->post('login',    'Auth::index');
$routes->get('logout',    'Auth::logout');
$routes->get('register',  'Auth::register');
$routes->post('register', 'Auth::register');

// Dashboard
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// Incoming Items
$routes->get('incomingitems',               'IncomingItems::index',         ['filter' => 'auth']);
$routes->get('incomingitems/add',           'IncomingItems::add',           ['filter' => 'auth']);
$routes->post('incomingitems/add',          'IncomingItems::add',           ['filter' => 'auth']);
$routes->get('incomingitems/delete/(:any)', 'IncomingItems::delete/$1',     ['filter' => 'auth']);
$routes->post('incomingitems/scan',         'IncomingItems::scan',          ['filter' => 'auth']);

// Outgoing Items
$routes->get('outgoingitems',               'OutgoingItems::index',         ['filter' => 'auth']);
$routes->get('outgoingitems/add',           'OutgoingItems::add',           ['filter' => 'auth']);
$routes->post('outgoingitems/add',          'OutgoingItems::add',           ['filter' => 'auth']);
$routes->get('outgoingitems/delete/(:any)', 'OutgoingItems::delete/$1',     ['filter' => 'auth']);
$routes->post('outgoingitems/scan',         'OutgoingItems::scan',          ['filter' => 'auth']);
$routes->post('outgoingitems/fefo_preview', 'OutgoingItems::fefoPreview',   ['filter' => 'auth']);

// Supplier
$routes->get('supplier',                'Supplier::index',      ['filter' => 'auth']);
$routes->get('supplier/add',            'Supplier::add',        ['filter' => 'auth']);
$routes->post('supplier/add',           'Supplier::add',        ['filter' => 'auth']);
$routes->get('supplier/edit/(:num)',    'Supplier::edit/$1',    ['filter' => 'auth']);
$routes->post('supplier/edit/(:num)',   'Supplier::edit/$1',    ['filter' => 'auth']);
$routes->get('supplier/delete/(:num)', 'Supplier::delete/$1',  ['filter' => 'auth']);

// Lab Sections
$routes->get('labsection',                'LabSection::index',    ['filter' => 'auth']);
$routes->get('labsection/add',            'LabSection::add',      ['filter' => 'auth']);
$routes->post('labsection/add',           'LabSection::add',      ['filter' => 'auth']);
$routes->get('labsection/edit/(:num)',    'LabSection::edit/$1',  ['filter' => 'auth']);
$routes->post('labsection/edit/(:num)',   'LabSection::edit/$1',  ['filter' => 'auth']);
$routes->get('labsection/delete/(:num)', 'LabSection::delete/$1',['filter' => 'auth']);

// Goods / Reagents
$routes->get('goods',                'Goods::index',     ['filter' => 'auth']);
$routes->get('goods/add',            'Goods::add',       ['filter' => 'auth']);
$routes->post('goods/add',           'Goods::add',       ['filter' => 'auth']);
$routes->get('goods/edit/(:any)',    'Goods::edit/$1',   ['filter' => 'auth']);
$routes->post('goods/edit/(:any)',   'Goods::edit/$1',   ['filter' => 'auth']);
$routes->get('goods/delete/(:any)', 'Goods::delete/$1', ['filter' => 'auth']);
$routes->get('goods/checkstock/(:any)', 'Goods::checkstock/$1', ['filter' => 'auth']);

// Units
$routes->get('unit',                'Unit::index',     ['filter' => 'auth']);
$routes->get('unit/add',            'Unit::add',       ['filter' => 'auth']);
$routes->post('unit/add',           'Unit::add',       ['filter' => 'auth']);
$routes->get('unit/edit/(:num)',    'Unit::edit/$1',   ['filter' => 'auth']);
$routes->post('unit/edit/(:num)',   'Unit::edit/$1',   ['filter' => 'auth']);
$routes->get('unit/delete/(:num)', 'Unit::delete/$1', ['filter' => 'auth']);

// Categories
$routes->get('category',                'Category::index',     ['filter' => 'auth']);
$routes->get('category/add',            'Category::add',       ['filter' => 'auth']);
$routes->post('category/add',           'Category::add',       ['filter' => 'auth']);
$routes->get('category/edit/(:num)',    'Category::edit/$1',   ['filter' => 'auth']);
$routes->post('category/edit/(:num)',   'Category::edit/$1',   ['filter' => 'auth']);
$routes->get('category/delete/(:num)', 'Category::delete/$1', ['filter' => 'auth']);

// Profile
$routes->get('profile',                 'Profile::index',          ['filter' => 'auth']);
$routes->get('profile/setting',         'Profile::setting',        ['filter' => 'auth']);
$routes->post('profile/setting',        'Profile::setting',        ['filter' => 'auth']);
$routes->get('profile/changepassword',  'Profile::changePassword', ['filter' => 'auth']);
$routes->post('profile/changepassword', 'Profile::changePassword', ['filter' => 'auth']);

// User Management
$routes->get('user',               'User::index',     ['filter' => 'auth']);
$routes->get('user/add',           'User::add',       ['filter' => 'auth']);
$routes->post('user/add',          'User::add',       ['filter' => 'auth']);
$routes->get('user/edit/(:num)',   'User::edit/$1',   ['filter' => 'auth']);
$routes->post('user/edit/(:num)',  'User::edit/$1',   ['filter' => 'auth']);
$routes->get('user/delete/(:num)','User::delete/$1', ['filter' => 'auth']);

// Report
$routes->get('report',  'Report::index', ['filter' => 'auth']);
$routes->post('report', 'Report::index', ['filter' => 'auth']);
