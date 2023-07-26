<h1 class="nombre-pagina">Si olvidaste tu contraseña</h1>
<p class="descripcion-pagin">Restablece tu contraseña escribiendo tu e-mail a continuación</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<form action="/olvide" class="formulario" method="POST">
    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="Tu email">
    </div>

    <input type="submit" value="Enviar instrucciones" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una!</a>
</div>