<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default redirect to login
$routes->get('/', 'Auth::login');

// Authentication Routes (public) - MIT RATE LIMITING
$routes->group('', ['filter' => 'throttle'], function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login/authenticate', 'Auth::authenticate');
});

// Logout ohne Rate Limiting
$routes->get('logout', 'Auth::logout');

// Protected Routes (require login)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // Profile
    $routes->get('profile', 'Auth::profile');
    $routes->post('profile/update', 'Auth::updateProfile');

    // Persons
    $routes->get('persons', 'Persons::index');
    $routes->get('persons/view/(:num)', 'Persons::view/$1');
    $routes->get('persons/create', 'Persons::create');
    $routes->post('persons/store', 'Persons::store');
    $routes->get('persons/edit/(:num)', 'Persons::edit/$1');
    $routes->post('persons/update/(:num)', 'Persons::update/$1');
    $routes->get('persons/delete/(:num)', 'Persons::delete/$1');
    $routes->get('/persons/delete-photo/(:num)', 'Persons::deletePhoto/$1');

    // Relationships
    $routes->post('relationships/add-parent', 'Relationships::addParent');
    $routes->post('relationships/add-spouse', 'Relationships::addSpouse');
    $routes->post('relationships/add-child', 'Relationships::addChild');
    $routes->get('relationships/delete/(:num)', 'Relationships::delete/$1');
    $routes->get('relationships/get-persons', 'Relationships::getAvailablePersons');

    // Photos (später)
    $routes->get('photos', 'Photos::index');
    $routes->get('photos/view/(:num)', 'Photos::view/$1'); // Einzelansicht (optional)
    $routes->get('persons/set-primary-photo/(:num)', 'Persons::setPrimaryPhoto/$1');

    // Family Tree (später)
    $routes->get('tree', 'Tree::index');
    $routes->get('persons/tree', 'Persons::tree');
    $routes->get('persons/tree-data', 'Persons::getTreeData');

    // Global Search API
    $routes->get('persons/search', 'Persons::search');

    // Events
    $routes->post('persons/add-event', 'Persons::addEvent');
    $routes->get('persons/delete-event/(:num)', 'Persons::deleteEvent/$1');

    // Statistics
    $routes->get('statistics', 'Statistics::index');

    // Export
    $routes->get('export', 'Export::index');
    $routes->get('export/gedcom', 'Export::gedcom');

    // CSV Export Routes
    $routes->get('export/csv', 'Export::csv');
    $routes->get('export/csv-relationships', 'Export::csvRelationships');
    $routes->get('export/csv-events', 'Export::csvEvents');
    $routes->get('export/csv-statistics', 'Export::csvStatistics');
});

// ==========================================
// ADMIN ROUTES (Nur für Admins)
// ==========================================
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    // User Management
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->post('users/delete/(:num)', 'Admin\Users::delete/$1');
    $routes->post('users/toggle-admin/(:num)', 'Admin\Users::toggleAdmin/$1');
});

// ==============================================
// ÖFFENTLICHE HILFE (für Landing Page Besucher)
// ==============================================
$routes->get('hilfe', 'Help::publicIndex');
$routes->get('hilfe/(:segment)', 'Help::publicShow/$1');
// API (öffentlich)
$routes->get('api/help-topics', 'Help::apiIndex');
$routes->get('api/help-article/(:segment)', 'Help::apiArticle/$1');

// ==============================================
// PRIVATE HILFE (für eingeloggte App-User)
// ==============================================
$routes->get('help', 'Help::index', ['filter' => 'auth']);
$routes->get('help/(:segment)', 'Help::show/$1', ['filter' => 'auth']);

// ==============================================
// Route fuer den Newsletter cronjob
// ==============================================
$routes->get('cron/newsletter', 'Cron::newsletter');

// ==============================================
// Support-Anfrage (AJAX)
// ==============================================

$routes->post('help/send-support', 'Help::sendSupport', ['filter' => 'auth']);
// ODER ohne Auth, falls auch öffentlich zugänglich:
$routes->post('help/send-support', 'Help::sendSupport');