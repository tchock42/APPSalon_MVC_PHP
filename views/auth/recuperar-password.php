<h1 class="nombre-pagina">Recuperar Contraseña</h1>

<p class="descripcion-pagina">Coloca tu nueva contraseña a continuación</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?><?php include_once __DIR__ . "/../templates/alertas.php" ?>
<?php if($error) return; ?>
<form method="POST" class="formulario"> <!--Idealmente tendría que ponerse un action=recuperar-password-->
                                    <!--pero esto haría que se borre el token en la url de la pagina-->
    <div class="campo">
        <label for="password">Contraseña</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            placeholder="Tu nueva contraseña">
    </div>
    <input type="submit" class="boton" value= "Guardar nueva contraseña">
</form>
<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? ¡Crea una!</a>
</div>