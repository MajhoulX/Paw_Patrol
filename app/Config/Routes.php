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
    // Add a new animal
    $routes->post('api/animals', 'AnimalController::createAnimals');
    // Delete an animal by ID
    $routes->delete('api/animals/(:num)', 'AnimalController::deleteAnimals/$1');
    // Update an animal by ID
    $routes->put('api/animals/(:num)', 'AnimalController::updateAnimals/$1');



//
$routes->get('/users_view', 'TestC::index');
$routes->get('auth/hashPasswordForUser', 'AuthController::hashPasswordForUser');
$routes->get('test', function () {
    return json_encode(['status' => 'success', 'message' => 'API is reachable']);
});
$routes->get('auth/logout', 'AuthController::logout');

// To display animals in a view (HTML table)
$routes->get('/test-animals', 'TestC::listAnimals');



