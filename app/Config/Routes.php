<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('api/products', 'ProductController::index');
// Menampilkan kategori produk pada /admin/category-product

$routes->group('admin', function($routes) {
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('products', 'Admin\ProductController::index');
    $routes->get('product/create', 'Admin\ProductController::create');
    $routes->post('product/store', 'Admin\ProductController::store');
    $routes->get('product/edit/(:num)', 'Admin\ProductController::edit/$1');
    $routes->post('product/update/(:num)', 'Admin\ProductController::update/$1');
    $routes->get('product/delete/(:num)', 'Admin\ProductController::delete/$1');

    $routes->get('category-product', 'Admin\CategoryController::index');
    $routes->get('category-product/create', 'Admin\CategoryController::create');
    $routes->get('category-product/edit/(:num)', 'Admin\CategoryController::edit/$1');
    $routes->post('category-product/edit/(:num)', 'Admin\CategoryController::edit/$1');
    $routes->post('category-product/delete/(:num)', 'Admin\CategoryController::delete/$1');
    $routes->post('category-product/store', 'Admin\CategoryController::store');


});

