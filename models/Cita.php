<?php
namespace Model;

//clase para manejar el esquema de citas
class Cita extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'usuarioId'];
    //estos datos tienen que coincidir con el append
    public $id; //id de la cita  
    public $fecha;  //feccha de la cita
    public $hora;   //hora de la cita
    public $usuarioId;  //id del usuario

    public function __construct ($args=[])
    {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->usuarioId = $args['usuarioId'] ?? '';
     }
}