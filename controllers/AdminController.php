<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{

    public static function index(Router $router){
        //inicia la sesion
        if(!isset($_SESSION)){
            session_start();
        }
        isAdmin();
        //debuguear($_GET['fecha']);
        $fecha = $_GET['fecha'] ?? date('Y-m-d'); //"2023-07-21", se tiene que pasar a m-d-y
        $fechas = explode('-', $fecha); //crea un array con los elementos de fecha que esté entre '-'
        //si no se encuentra una fecha válida, manda a página de error
        if(!checkdate($fechas[1], $fechas[2], $fechas[0])){ //checkdate(m, d, y)
            header('Location: /404');
        }//continua el código correctamente
        
        // $fecha = date('Y-m-d'); //año, mes, dia como en la base de datos
        // debuguear($fecha);
        //Consulta la base de datos
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasservicios ";
        $consulta .= " ON citasservicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasservicios.servicioId ";
        $consulta .= " WHERE fecha =  '$fecha' ";

        //accede al metodo de ActiveRecord hecho para consultas especiales
        $citas = AdminCita::SQL($consulta);
        //renderiza y manda el nombre de la sesion
        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);

    }
}