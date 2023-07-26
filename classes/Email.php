<?php
//clase separada de models para el envío de correos
namespace Classes;

//importa clase de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;

class Email{
    //creacion de atributos
    public $email;
    public $nombre;
    public $token;

    public function __construct($nombre, $email, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    public function enviarConfirmacion(){

        //crear el objeto de mail- Configuracion de servidor
        //configuracion obtenida de mailtrap.io
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_POST'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        //recipientes
        $mail->setFrom('cuentas@appsalon.com'); //quien lo envías. 
        $mail->addAddress('cuentas@appsalon.com', 'Appsalon'); //hosting contratado
        $mail->Subject = 'Confirma tu cuenta';

        //set HTML format 
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';
        $contenido = '<html>';
        $contenido .= "<p><strong> Hola " . $this->nombre . "</strong> Has creado tu cuenta en Appsalon, solo debes confirmarla presionando el siguiente enlace. </p>";
        $contenido .= "<p> Presiona aquí <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar  Cuenta</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        //enviar el mail
        $mail->send();
    }

    public function enviarInstrucciones(){
         //crear el objeto de mail- Configuracion de servidor
        //configuracion obtenida de mailtrap.io
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_POST'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        //recipientes
        $mail->setFrom('cuentas@appsalon.com'); //quien lo envías. 
        $mail->addAddress('cuentas@appsalon.com', 'Appsalon'); //hosting contratado
        $mail->Subject = 'Reestablece tu Contraseña';

        //set HTML format 
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';
        $contenido = '<html>';
        $contenido .= "<p><strong> Hola " . $this->nombre . "</strong> Has solicitado reestablecer tu contraseña, sigue el siguiente enlace para realizarlo </p>";
        $contenido .= "<p> Presiona aquí <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . "'>Reestablecer Contraseña</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        //enviar el mail
        $mail->send();
    }
}