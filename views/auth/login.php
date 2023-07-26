<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<form class="formulario" method="POST" action="/"> <!--ACTION referencia a / -->
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email" 
            id="email" placeholder="Tu email"
            name="email" 
            value = "<?php echo s($auth->email); ?>" >
    </div>
    <div class="campo">
        <label for="password">Contraseña</label>
        <input 
            type="password" 
            id="password" placeholder="Tu contraseña"
            name="password"> <!-- para leerse con $_post['email] -->
    </div>
    <input type="submit" class="boton" value="Iniciar Sesión">
</form>
<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes cuenta? Crear cuenta</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>