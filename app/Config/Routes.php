<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->get('/', 'Dashboard::index');

    $routes->get('cms', 'CmsItem::index');
    $routes->post('cms/save', 'CmsItem::save');

    $routes->get('programs', 'Program::index');
    $routes->post('programs/save', 'Program::save');

    $routes->get('texts', 'WebsiteText::index');
    $routes->post('texts/save', 'WebsiteText::save');

    $routes->get('board', 'BoardMember::index');
    $routes->post('board/save', 'BoardMember::save');
    $routes->get('board/delete-photo/(:num)', 'BoardMember::deletePhoto/$1');

    $routes->get('hero-slides', 'HeroSlideCMS::index');
    $routes->post('hero-slides/save', 'HeroSlideCMS::save');
    $routes->get('hero-slides/delete/(:num)', 'HeroSlideCMS::delete/$1');

    $routes->get('pages', 'PageCMS::index');
    $routes->get('pages/ajaxData', 'PageCMS::ajaxData');
    $routes->get('pages/create', 'PageCMS::create');
    $routes->get('pages/edit/(:num)', 'PageCMS::edit/$1');
    $routes->post('pages/save', 'PageCMS::save');
    $routes->get('pages/delete/(:num)', 'PageCMS::delete/$1');

    $routes->get('sections', 'SectionCMS::index');
    $routes->get('sections/ajax', 'SectionCMS::ajaxData');
    $routes->get('sections/create', 'SectionCMS::create');
    $routes->get('sections/edit/(:num)', 'SectionCMS::edit/$1');
    $routes->post('sections/save', 'SectionCMS::save');

    $routes->get('section-links', 'SectionLinkCMS::index');
    $routes->get('section-links/create', 'SectionLinkCMS::create');
    $routes->get('section-links/edit/(:num)', 'SectionLinkCMS::edit/$1');
    $routes->post('section-links/save', 'SectionLinkCMS::save');
    $routes->get('section-links/delete/(:num)', 'SectionLinkCMS::delete/$1');
});

// Tambahkan di bagian definisi routes
// $routes->get('struktur-pengurus', 'Home::struktur');
$routes->get('halaman', 'Page::index');
$routes->get('(:segment)', 'Page::show/$1');

$routes->get('lang/(:segment)', 'LanguageController::switchLanguage/$1');
$routes->get('agenda/(:num)', 'Agenda::detail/$1');


$routes->set404Override(function () {
    return view('frontend/404');
});
