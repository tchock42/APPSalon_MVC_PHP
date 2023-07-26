<?php 

namespace Controllers;

use Model\Servicio;
use MVC\Router;
class ServicioController{ //controlador de adminitraciones de servicios
    //muestra la pgina principal de admin de servicios
    public static function index(Router $router){ 
        //revisa si está la sesión abierta
        if(!isset($_SESSION['nombre'])){
            session_start(); //y si no, la abre
        }
        isAdmin();
        $servicios =Servicio::all();
        $router->render('/servicios/index', [ //se le pasa la ruta del archivo
            'nombre' => $_SESSION['nombre'], //pasa el nombre del usuario
            'servicios' => $servicios
        ]);
    }
    public static function crear(Router $router){
        if(!isset($_SESSION['nombre'])){
            session_start();
        }
        isAdmin();
        $servicio = new Servicio; //se crea nueva instancia de servicio vacío
        $alertas = []; 
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('/servicios/crear', [ //se le pasa la ruta del archivo
            'nombre' => $_SESSION['nombre'], 
            'servicio' => $servicio,
            'alertas'=> $alertas
        ]);
    }

    public static function actualizar(Router $router){

        if(!isset($_SESSION['nombre'])){
            session_start();
        }
        isAdmin();
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT); //filtro del GET
        if(!is_numeric($id)){ //si no es numero e
            header('Location: /servicios');
        }
        $servicio = Servicio::find($id); //se crea nueva instancia buscando el $id
        $alertas = []; 
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio->sincronizar($_POST); //crea otra instancia pero con POST
            $alertas = $servicio->validar();

            if(empty($alertas)){ //si no hay errores, guarda y redirecciona 
                $servicio->guardar();
                header('Location: /servicios');
            }
        }
        $router->render('/servicios/actualizar', [ //se le pasa la ruta del archivo
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }
    public static function eliminar(){
        if(!isset($_SESSION['nombre'])){
            session_start();
        }
        isAdmin();
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            // debuguear($servicio);
            $servicio->eliminar();
            header('Location: /servicios');
            
        }
    }
}