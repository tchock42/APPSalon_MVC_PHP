<?php
namespace Controllers;

use MVC\Router;

class CitaController{
    public static function index(Router $router){
        if(!isset($_SESSION)) { //si no está la sesion abierta la abre
            session_start();
        }
        isAuth();
        
        // debuguear($_SESSION);
        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'], //en index.php se vuelve $nombre
            'id' => $_SESSION['id'] //id del usuario
        ]);
    }
}