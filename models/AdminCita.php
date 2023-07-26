<?php
namespace Model;

//esta clase accede a la consulta que usa los join para unir varias tablas usando ActiveRecord
class AdminCita extends ActiveRecord{
    protected static $tabla = 'citasServicios'; //a esta tabla se accede mayormente
    protected static $columnasDB = ['id', 'hora', 'cliente', 'email', 'telefono', 'servicio', 'precio']; //cliente y servicio son un alias

    public $id;
    public $hora;
    public $cliente;
    public $email;
    public $telefono;
    public $servicio;
    public $precio;

    public function __construct()
    {
        $this->id = $args['id'] ?? null;
        $this->hora = $args['hora'] ?? '';
        $this->cliente = $args['cliente'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->servicio = $args['servicio'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }
}