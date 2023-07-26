<?php

namespace Model;

class Usuario extends ActiveRecord{
    //base de datos
    protected static $tabla = 'usuarios'; //solo puede acceder a $tabla la clase Usuario
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    //mensajes de validacion para la creación de una cuenta
    public function validarNuevaCuenta(){ //public porque se llama desde LoginController
        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre del Cliente es obligatorio'; //ahora es un arreglo  de doble indice
        }
        if(!$this->apellido){
            self::$alertas['error'][] = 'El apellido del Cliente es obligatorio'; //ahora es un arreglo  de doble indice
        }
        if(!$this->email){
            self::$alertas['error'][] = 'El correo del Cliente es obligatorio'; //ahora es un arreglo  de doble indice
        }
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña del Cliente es obligatoria'; //ahora es un arreglo  de doble indice
        }
        if(strlen( $this->password) < 6 ){
            self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
        }
        if(!$this->telefono){
            self::$alertas['error'][] = 'El teléfono del Cliente es obligatorio'; //ahora es un arreglo  de doble indice
        }
        if(strlen( $this->password) > 11 ){
            self::$alertas['error'][] = 'La contraseña debe contener menos de 11 digitos';
        }
        return self::$alertas;
    }
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }
        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'El password debe tener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        
        $resultado = self::$db->query($query);  //metodo del objeto de base de datos
        if($resultado->num_rows){ //si existe una persona registrada con el correo dado
            self::$alertas['error'][] = 'El usuario ya esta registrado';
        }
                // debuguear($resultado);
        return $resultado;
    }
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    public function crearToken(){
        $this->token = uniqid(); //funcion que genera un codigo de 13 digitos
    }

    public function comprobarPasswordAndVerificado($password){
        $resultado = password_verify($password, $this->password);
        // debuguear($resultado);
        if(!$resultado || !$this->confirmado){ //si la contraseña no es correcta o
            self::$alertas['error'][] = 'Contraseña incorrecta o cuenta sin verificar';
        }else{ //si  la contraseña es correcta y el usuario está verificado
            return true;
        }
    }
}