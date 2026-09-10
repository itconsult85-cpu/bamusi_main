<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Rute untuk CMS Backend
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->get('cms', 'CmsItem::index');
    $routes->post('cms/save', 'CmsItem::save');
});
