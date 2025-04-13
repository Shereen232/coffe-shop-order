<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/product/(:num)', 'Home::show/$1');
$routes->group('api', function($routes) {
    $routes->get('categories', 'CategoryController::index');
    $routes->get('products', 'ProductController::index');
    $routes->post('(:num)/addcart', 'ProductController::addcart/$1');
    $routes->get('(:num)/getcart', 'ProductController::getcart/$1');
    $routes->post('(:num)/deleteitem', 'ProductController::deleteItem/$1');
});
// Menampilkan kategori produk pada /admin/category-product

$routes->group('admin', function($routes) {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('products', 'Admin\ProductController::index');
    $routes->get('product/create', 'Admin\ProductController::create');
    $routes->post('product/store', 'Admin\ProductController::store');
    $routes->get('product/edit/(:num)', 'Admin\ProductController::edit/$1');
    $routes->post('product/update/(:num)', 'Admin\ProductController::update/$1');
    $routes->post('product/delete/(:num)', 'Admin\ProductController::delete/$1');

    $routes->get('category-product', 'Admin\CategoryController::index');
    $routes->get('category-product/create', 'Admin\CategoryController::create');
    $routes->post('category-product/store', 'Admin\CategoryController::store');
    $routes->get('category-product/edit/(:num)', 'Admin\CategoryController::edit/$1');
    $routes->post('category-product/edit/(:num)', 'Admin\CategoryController::edit/$1');
    $routes->post('category-product/delete/(:num)', 'Admin\CategoryController::delete/$1');
    
    $routes->get('tables', 'Admin\TableController::index');
    $routes->get('tables/create', 'Admin\TableController::create');
    $routes->post('tables/store', 'Admin\TableController::store');
    $routes->get('tables/edit/(:num)', 'Admin\TableController::edit/$1');
    $routes->post('tables/update/(:num)', 'Admin\TableController::update/$1');
    $routes->post('tables/delete/(:num)', 'Admin\TableController::delete/$1');


});

