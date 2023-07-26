<?php
namespace Model;

class Servicio extends ActiveRecord{ //esta clase consulta la DB
    //base de datos
    protected static $tabla = 'servicios';
    protected  static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }
    public function validar(){
        if(!$this->nombre){ //si no se ingresa el nombre
            self::$alertas['error'][] = 'El nombre del Servicio es obligatorio.';
        }
        if(!$this->precio){ //si no se ingresa el precio
            self::$alertas['error'][] = 'El precio del Servicio es obligatorio.';
        }
        if(!is_numeric($this->precio)){ ////si no se ingresa el precio numericamente
            self::$alertas['error'][] = 'El precio del Servicio no es válido.';
        }
        return self::$alertas;
    }
}