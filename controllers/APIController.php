<?php 
namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio; //modelos y metodos derivados de ActiveRecord

class APIController{
    public static function index(){
        $servicios = Servicio::all();
        // debuguear($servicios); //imprime un arreglo de arreglos asociativos
        /** Convertir a JSON  */
        echo json_encode($servicios); //imprime los servicios en formato json   
    }
    public static function guardar(){
        // $respuesta = [  //arreglo asociativo. 
        //     // 'mensaje' => 'Todo Ok'
        //     'datos' => $_POST
        // ];
        // debuguear($_POST);   
        //almacena la Cita y devuelve el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar(); //se guarda la cita en tabla citas, retorna resultado y el id del usuario 
        $id = $resultado['id']; //extrae el id de la cita para pasarlo a la instancia de citaServicio
        // debuguear($resultado);
        // $respuesta = [  //se pasan los parámetros del objeto cita
        //     'cita' => $cita 
        // ];

        //almacena los Servicios con el Id de la cita
        $idServicios = explode(",", $_POST['servicios']); //separa los id de los servicios donde haya comas
        foreach($idServicios as $idServicio){
            $args = [ //el args se va a pasar al constructor de citaServicio
                'citaId' => $id, //id de la cita, traido de la consulta de $cita->guardar
                'servicioId' => $idServicio //el id del servicio actual
            ]; 
            $citaServicio = new CitaServicio($args); //crea la instancia por cada servicio agendado
            $citaServicio->guardar();
        }
        //retornamos una respuesta
        echo json_encode(['resultado'=>$resultado]); //trae el resultado y el id de Cita
    }
    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] ==='POST'){
            $id = $_POST['id'];
            
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']); //redirige a /admin?fecha=2023-07-24
        }
    }
}
