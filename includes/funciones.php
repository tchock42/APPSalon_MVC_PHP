<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo(string $actual, string $proximo): bool{
    if($actual !== $proximo){
        return true; //ya se ha llegado al ultimo servicio de la cita actual
    }
    return false; //aun no llega al final de los servicios
}

//funcion que revisa que el usuario está autenticado
function isAuth() : void{ //void porque no va a retornar nada
    if( !isset($_SESSION['login'] )){ //si no está iniciada la sesión
        header('Location: /');
    }
}
function isAdmin(): void{
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }

}