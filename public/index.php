<?php 
require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\APIController;
use Controllers\CitaController;
use Controllers\AdminController;
use Controllers\LoginController;
use Controllers\ServicioController;

$router = new Router();

//iniciar sesion
$router->get('/', [LoginController::class, 'login']); //pagina principal para iniciar sesion
$router->post('/', [LoginController::class, 'login']); //post de login
$router->get('/logout', [LoginController::class, 'logout']); //pagina para cerrar sesión

//recuperar password
$router->get('/olvide', [LoginController::class, 'olvide']);    //selecciona olvida contraseña
$router->post('/olvide', [LoginController::class, 'olvide']);   //post de olvide    
$router->get('/recuperar', [LoginController::class, 'recuperar']);  //aqui recupera contraseñña
$router->post('/recuperar', [LoginController::class, 'recuperar']);     //post de recuperar

//crear cuenta
$router->get('/crear-cuenta', [LoginController::class, 'crear']); //crear cuenta
$router->post('/crear-cuenta', [LoginController::class, 'crear']);    //post de crear

//confirmar cuenta
$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar']); //pagina donde se confirma la cuenta con el token en url
$router->get('/mensaje', [LoginController::class, 'mensaje']);

//area privada - sesion iniciada
$router->get('/cita', [CitaController::class, 'index']);
$router->get('/admin', [AdminController::class, 'index']);

//API de citas
$router->get('/api/servicios', [APIController::class, 'index']); //endpoint para importar los servicios a elegir
$router->post('/api/citas', [APIController::class, 'guardar']); //endpoint para enviar los servicios seleccionados
$router->post('/api/eliminar', [APIController::class, 'eliminar']);

//CRUD de servicios
$router->get('/servicios',[ServicioController::class, 'index']);
$router->get('/servicios/crear', [ServicioController::class, 'crear']);
$router->post('/servicios/crear', [ServicioController::class, 'crear']);
$router->get('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/eliminar', [ServicioController::class, 'eliminar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();