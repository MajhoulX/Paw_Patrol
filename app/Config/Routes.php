<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/login', 'Home::hi'); 
//$routes->post('/success', 'Home::success'); 
//same code
$routes->get('/', 'Home::index');

$routes->post('auth/signup', 'AuthController::signup');
$routes->post('auth/login', 'AuthController::login');
$routes->get('api/animals', 'AnimalController::getAnimals');
$routes->get('api/animals/(:num)', 'AnimalController::getAnimal/$1');
$routes->post('api/animals', 'AnimalController::createAnimals');
$routes->delete('api/animals/(:num)', 'AnimalController::deleteAnimals/$1'); 
$routes->put('api/animals/(:num)', 'AnimalController::updateAnimals/$1');



//tests
$routes->get('auth/hashPasswordForUser', 'AuthController::hashPasswordForUser');
$routes->get('test', function () {
    return json_encode(['status' => 'success', 'message' => 'API is reachable']);
});
$routes->get('auth/logout', 'AuthController::logout');




