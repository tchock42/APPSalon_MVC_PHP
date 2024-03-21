<?php
namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController{
    public static function login(Router $router){
        $alertas = [];
        $auth = new Usuario; //objeto auth vacio para el autocompletado
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // debuguear($_POST);
            $auth = new Usuario($_POST);
            $alertas=$auth->validarLogin();
            // debuguear($auth);

            if(empty($alertas)){ //el usuario dio su correo y password
                 //comprobar que existe el usuario por su columna y valor
                 $usuario = Usuario::where('email', $auth->email);
                //  ;
                if($usuario){
                    //verificar usuario
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        //autenticar usuario
                        if(!isset($_SESSION)) { //si no está la sesion abierta la abre
                            session_start();
                        }
                        //guardar datos en la superglobal SESSIOM
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        // debuguear($usuario->admin);
                        //redireccionamiento
                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else{
                            header('Location: /cita');
                        }
                        // debuguear($_SESSION);
                    } 
                }else{
                    //No se encuentra en la base de datos
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
        $alertas = Usuario::getAlertas(); //recupera la alerta 
        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }
    public static function logout(){
        if(!isset($_SESSION)){
            session_start();
        }

        $_SESSION = [];
        header('Location: /');
    }
    public static function olvide(Router $router){
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            // debuguear($auth);
            $alertas = $auth->validarEmail();
            // debuguear($alertas);

            if(empty($alertas)){ //si el usuario teclea un correo
                $usuario = Usuario::where('email', $auth->email); // busca en columna email el valor de auth
                
                if($usuario && $usuario->confirmado === "1"){
                    //Generar un token
                    $usuario->crearToken(); //asigna un nuevo token al usuario
                    // debuguear($usuario); 
                    $usuario->guardar(); //realiza un update en la base de datos

                    //Enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token); //construct($nombre, $email, $token
                    $email->enviarInstrucciones(); //mandar correo con instrucciones
                    //Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                    // $alertas = Usuario::getAlertas();
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado'); //agrega un error
                    // $alertas = Usuario::getAlertas(); //recupera el error
                }
            }
        }
        //se obtienen las alertas del if y del else anterior
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);

        //Buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no Válido');
            $error = true;
        }
        // debuguear($usuario);


        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            //Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $password->validarPassword();
            // debuguear($password);
            if(empty($alertas)){ //se copia la nueva contraseña al objeto $usuario
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword(); //metodo solo se usa con clase Usuario
                $usuario->token = null;
                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    public static function crear(Router $router){
        $usuario = new Usuario; //objeto vacio para llenar el formulario

        //alertas vacias
        $alertas=[]; //arreglo vacio para cuando se entra por primera vez a crear
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas=$usuario->validarNuevaCuenta();
            // debuguear($alertas);

            //revisar que alerta esté vacío
            if(empty($alertas)){
                //verificar que el usuario no esté registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas(); //recupera los errores
                }else{
                    //el usuario no está registrado
                    //se hashea el password
                    $usuario->hashPassword();
                    //generar un token unico
                    $usuario->crearToken();
                    //crea una instancia de Email para enviar el correo
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    
                    $email->enviarConfirmacion();

                    //crear el usuario
                    $resultado = $usuario->guardar();
                    // debuguear($usuario);
                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario, 
            'alertas' => $alertas
        ]);
        
    }
    public static function mensaje(Router $router){
        $router->render('auth/mensaje', [

        ]);
    }
    public static function confirmar(Router $router){
        $alertas = []; //se inicializa el arreglo de alertas que se modifica en setalertas
        $token = s($_GET['token']);
        // debuguear($token);
        $usuario = Usuario::where('token', $token); //busca un registro en la columna token el valor de $token

        if(empty($usuario)){
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido');
        }else{
            //Modificar a usuario confirmado
            $usuario->confirmado="1"; //cambia a conrimado
            $usuario->token = null; //borra token
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuanta comprobada correctamente');
        }

        $alertas = Usuario::getAlertas(); //obtener alertas
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}