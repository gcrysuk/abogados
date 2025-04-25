<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('estados', function ($routes) {
    $routes->get('/', 'EstadosController::index');
    $routes->get('nuevo', 'EstadosController::nuevo');
    $routes->post('guardar', 'EstadosController::guardar');
    $routes->get('editar/(:num)', 'EstadosController::editar/$1');
    $routes->post('actualizar/(:num)', 'EstadosController::actualizar/$1');
    $routes->get('eliminar/(:num)', 'EstadosController::eliminar/$1');
    $routes->post('verificar-estado', 'EstadosController::verificarEstado');
});

$routes->group('juzgados', function ($routes) {
    $routes->get('/', 'JuzgadosController::index');
    $routes->get('fuero/(:any)', 'JuzgadosController::porFuero/$1');
    $routes->get('nuevo', 'JuzgadosController::nuevo');
    $routes->post('guardar', 'JuzgadosController::guardar');
    $routes->get('editar/(:num)', 'JuzgadosController::editar/$1');
    $routes->post('actualizar/(:num)', 'JuzgadosController::actualizar/$1');
    $routes->get('eliminar/(:num)', 'JuzgadosController::eliminar/$1');
});

$routes->group('objetos', function ($routes) {
    $routes->get('/', 'ObjetoController::index');
    $routes->get('buscar', 'ObjetoController::buscar');
    $routes->get('nuevo', 'ObjetoController::nuevo');
    $routes->post('guardar', 'ObjetoController::guardar');
    $routes->get('editar/(:num)', 'ObjetoController::editar/$1');
    $routes->post('actualizar/(:num)', 'ObjetoController::actualizar/$1');
    $routes->get('eliminar/(:num)', 'ObjetoController::eliminar/$1');
    $routes->get('select', 'ObjetoController::obtenerParaSelect');
    $routes->post('verificar-nombre', 'ObjetoController::verificarNombre');
});

$routes->group('peritos', function ($routes) {
    $routes->get('/', 'PeritosController::index');
    $routes->get('materia/(:any)', 'PeritosController::porMateria/$1');
    $routes->get('nuevo', 'PeritosController::nuevo');
    $routes->post('guardar', 'PeritosController::guardar');
    $routes->get('editar/(:num)', 'PeritosController::editar/$1');
    $routes->post('actualizar/(:num)', 'PeritosController::actualizar/$1');
    $routes->get('eliminar/(:num)', 'PeritosController::eliminar/$1');
    $routes->post('verificar-email', 'PeritosController::verificarEmail');
    $routes->get('buscar-materia', 'PeritosController::buscarPorMateriaApi');
});

$routes->group('situaciones', function ($routes) {
    $routes->get('/', 'SituacionController::index');
    $routes->get('nuevo', 'SituacionController::nuevo');
    $routes->post('guardar', 'SituacionController::guardar');
    $routes->get('editar/(:num)', 'SituacionController::editar/$1');
    $routes->post('actualizar/(:num)', 'SituacionController::actualizar/$1');
    $routes->get('eliminar/(:num)', 'SituacionController::eliminar/$1');
    $routes->get('opciones', 'SituacionController::getOpciones');
    $routes->post('verificar', 'SituacionController::verificarSituacion');
});

$routes->group('usuarios', function ($routes) {
    $routes->get('/', 'UsuariosController::index');
    $routes->get('nuevo', 'UsuariosController::nuevo');
    $routes->post('guardar', 'UsuariosController::guardar');
    $routes->get('editar/(:num)', 'UsuariosController::editar/$1');
    $routes->post('actualizar/(:num)', 'UsuariosController::actualizar/$1');
    $routes->get('cambiar-estado/(:num)', 'UsuariosController::cambiarEstado/$1');
    $routes->get('por-rol/(:any)', 'UsuariosController::porRol/$1');
    $routes->get('abogados-select', 'UsuariosController::abogadosSelect');
    $routes->post('verificar-username', 'UsuariosController::verificarUsername');
    // Agregar estas rutas junto con las de usuarios
    $routes->match(['get', 'post'], 'login', 'AuthController::login');
    $routes->post('procesar-login', 'AuthController::procesarLogin');
    $routes->get('logout', 'AuthController::logout');
    $routes->match(['get', 'post'], 'forgot-password', 'AuthController::forgotPassword');
    $routes->post('procesar-recuperacion', 'AuthController::procesarRecuperacion');
});

// Ruta para login (fuera del grupo usuarios)
$routes->match(['get', 'post'], 'login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// Agrega estas rutas adicionales
$routes->get('reset-password/(:any)', 'AuthController::resetPassword/$1');
$routes->post('procesar-reset-password', 'AuthController::procesarResetPassword');
