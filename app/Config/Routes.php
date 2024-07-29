<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('user/fetch', 'UserController::fetch');
$routes->get('user/(:num)', 'UserController::getUser/$1');
$routes->get('user', 'UserController::index');
$routes->post('user', 'UserController::create');
$routes->put('user/(:num)', 'UserController::update/$1');
$routes->delete('user/(:num)', 'UserController::delete/$1');
