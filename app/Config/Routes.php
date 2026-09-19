<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('berita', 'News::index');
$routes->get('artikel', 'Article::index');
$routes->get('artikel/(:num)', 'Article::show/$1');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->post('logout', 'Auth::logout');

$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'adminauth'], function ($routes) {
    $routes->get('/', 'Dashboard::index');

    $routes->get('cms-items', 'CmsItem::index');
    $routes->get('cms-items/ajaxData', 'CmsItem::ajaxData');
    $routes->get('cms-items/create', 'CmsItem::create');
    $routes->get('cms-items/edit/(:num)', 'CmsItem::edit/$1');
    $routes->post('cms-items/save', 'CmsItem::save');
    $routes->get('cms-items/delete/(:num)', 'CmsItem::delete/$1');

    $routes->get('programs', 'Program::index');
    $routes->get('programs/ajaxData', 'Program::ajaxData');
    $routes->get('programs/create', 'Program::create');
    $routes->get('programs/edit/(:num)', 'Program::edit/$1');
    $routes->post('programs/save', 'Program::save');
    $routes->get('programs/delete/(:num)', 'Program::delete/$1');

    $routes->get('settings', 'SettingCMS::index');
    $routes->get('settings/ajaxData', 'SettingCMS::ajaxData');
    $routes->get('settings/create', 'SettingCMS::create');
    $routes->get('settings/edit/(:num)', 'SettingCMS::edit/$1');
    $routes->post('settings/save', 'SettingCMS::save');
    $routes->get('settings/delete/(:num)', 'SettingCMS::delete/$1');
    $routes->get('settings/bulkTranslate', 'SettingCMS::bulkTranslate');

    $routes->get('texts', 'WebsiteText::index');
    $routes->get('texts/ajaxData', 'WebsiteText::ajaxData');
    $routes->get('texts/create', 'WebsiteText::create');
    $routes->get('texts/edit/(:num)', 'WebsiteText::edit/$1');
    $routes->post('texts/save', 'WebsiteText::save');
    $routes->get('texts/delete/(:num)', 'WebsiteText::delete/$1');
    $routes->get('texts/bulkTranslate', 'WebsiteText::bulkTranslate');

    $routes->get('board', 'BoardMember::index');
    $routes->get('board/ajaxData', 'BoardMember::ajaxData');
    $routes->get('board/create', 'BoardMember::create');
    $routes->get('board/edit/(:num)', 'BoardMember::edit/$1');
    $routes->post('board/save', 'BoardMember::save');
    $routes->get('board/delete/(:num)', 'BoardMember::delete/$1');

    $routes->get('hero-slides', 'HeroSlideCMS::index');
    $routes->get('hero-slides/ajaxData', 'HeroSlideCMS::ajaxData');
    $routes->get('hero-slides/create', 'HeroSlideCMS::create');
    $routes->get('hero-slides/edit/(:num)', 'HeroSlideCMS::edit/$1');
    $routes->post('hero-slides/save', 'HeroSlideCMS::save');
    $routes->get('hero-slides/delete/(:num)', 'HeroSlideCMS::delete/$1');

    $routes->get('pages', 'PageCMS::index');
    $routes->get('pages/ajaxData', 'PageCMS::ajaxData');
    $routes->get('pages/create', 'PageCMS::create');
    $routes->get('pages/edit/(:num)', 'PageCMS::edit/$1');
    $routes->post('pages/save', 'PageCMS::save');
    $routes->post('pages/translate-all', 'PageCMS::translateAll');
    $routes->post('pages/delete/(:num)', 'PageCMS::delete/$1');

    $routes->get('menu', 'MenuCMS::index');
    $routes->get('menu/create', 'MenuCMS::create');
    $routes->get('menu/edit/(:num)', 'MenuCMS::edit/$1');
    $routes->post('menu/item/save', 'MenuCMS::saveItem');
    $routes->post('menu/item/delete/(:num)', 'MenuCMS::deleteItem/$1');
    $routes->post('menu/save', 'MenuCMS::save');

    $routes->get('sections', 'SectionCMS::index');
    $routes->get('sections/ajax', 'SectionCMS::ajaxData');
    $routes->get('sections/create', 'SectionCMS::create');
    $routes->get('sections/edit/(:num)', 'SectionCMS::edit/$1');
    $routes->post('sections/save', 'SectionCMS::save');
    $routes->get('sections/item/create/(:segment)', 'SectionCMS::itemCreate/$1');
    $routes->get('sections/item/edit/(:num)', 'SectionCMS::itemEdit/$1');
    $routes->post('sections/item/delete/(:num)', 'SectionCMS::itemDelete/$1');
    $routes->get('sections/block/create/(:segment)', 'SectionCMS::blockCreate/$1');
    $routes->get('sections/block/edit/(:num)', 'SectionCMS::blockEdit/$1');
    $routes->post('sections/block/save', 'SectionCMS::blockSave');
    $routes->post('sections/block/delete/(:num)', 'SectionCMS::blockDelete/$1');
    $routes->get('sections/text/create/(:segment)', 'SectionCMS::textCreate/$1');
    $routes->get('sections/text/edit/(:num)', 'SectionCMS::textEdit/$1');
    $routes->post('sections/text/save', 'SectionCMS::textSave');
    $routes->post('sections/text/delete/(:num)', 'SectionCMS::textDelete/$1');

    $routes->get('homepage-content', 'HomepageContentCMS::index');
    $routes->get('homepage-content/(:segment)', 'HomepageContentCMS::index/$1');
    $routes->get('homepage-content/create/(:segment)', 'HomepageContentCMS::create/$1');
    $routes->get('homepage-content/edit/(:num)', 'HomepageContentCMS::edit/$1');
    $routes->post('homepage-content/save', 'HomepageContentCMS::save');
    $routes->post('homepage-content/delete/(:num)', 'HomepageContentCMS::delete/$1');

    $routes->get('partners', 'PartnerCMS::index');
    $routes->get('partners/create', 'PartnerCMS::create');
    $routes->get('partners/edit/(:num)', 'PartnerCMS::edit/$1');
    $routes->post('partners/save', 'PartnerCMS::save');
    $routes->post('partners/delete/(:num)', 'PartnerCMS::delete/$1');

    $routes->get('about-values', 'AboutValueCMS::index');
    $routes->get('about-values/create', 'AboutValueCMS::create');
    $routes->get('about-values/edit/(:num)', 'AboutValueCMS::edit/$1');
    $routes->post('about-values/save', 'AboutValueCMS::save');
    $routes->post('about-values/delete/(:num)', 'AboutValueCMS::delete/$1');

    $routes->get('section-links', 'SectionLinkCMS::index');
    $routes->get('section-links/create', 'SectionLinkCMS::create');
    $routes->get('section-links/edit/(:num)', 'SectionLinkCMS::edit/$1');
    $routes->post('section-links/save', 'SectionLinkCMS::save');
    $routes->post('section-links/delete/(:num)', 'SectionLinkCMS::delete/$1');
});

$routes->get('lang/(:segment)', 'LanguageController::switchLanguage/$1');
$routes->get('agenda/(:num)', 'Agenda::detail/$1');
$routes->get('program', 'Program::index');
$routes->get('program/(:segment)', 'Program::detail/$1');

// Catch-all harus diletakkan paling akhir agar route dinamis di atas tidak tertangkap Page::show.
$routes->get('halaman', 'Page::index');
$routes->get('(:segment)', 'Page::show/$1');


$routes->set404Override(function () {
    return view('frontend/404');
});
