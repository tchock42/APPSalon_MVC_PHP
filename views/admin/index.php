<h1 class="nombre-pagina">Panel de Administración</h1>

<?php include_once __DIR__ . '/../templates/barra.php'; ?>
<h2>Buscar citas</h2>
<div class="busqueda">
    <form action="" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>

<?php 
    if(count($citas) === 0){
        echo "<h2>No hay citas en esta fecha</h2>";
    }

?>

<div class="citas-admin">
    <ul class="citas">
    <?php 
        $idCita = 0; //para que no marque undefined
        
        foreach( $citas as $key => $cita) {
            if($idCita !== $cita->id){ //siempre que sea diferente el id de la cita al id anterior se imprime los valores de cada cita
                $total = 0; //aqui inicia porque el siguiente codigo se ejecuta una vez
        ?>  
        <li>      
                <p>ID: <span><?php  echo $cita->id; ?></span></p>
                <p>Hora: <span><?php  echo $cita->hora; ?></span></p>
                <p>Cliente: <span><?php  echo $cita->cliente; ?></span></p>    
                <p>Email: <span><?php  echo $cita->email; ?></span></p>    
                <p>Telefono: <span><?php  echo $cita->telefono; ?></span></p>    
                <h3>Servicios</h3>
        <?php
            $idCita = $cita->id; 
            } //fin de if 
            $total+=$cita->precio; //despues del if porque la suma se tiene que hacer cada iteracion de los elementos de cada servicio
        ?> 
        <p class="servicio"> <?php echo $cita->servicio . " " . $cita->precio; ?></p>
        <!-- </li> -->
        <?php 
            $actual = $cita->id; //id de cita actual
            $proximo = $citas[$key + 1]->id ?? 0;  // id de cita siguiente
            if(esUltimo($actual, $proximo)){  //retorna true si es el ultimo
                //echo "es ultimo"; //es la ultima cita ?> 
                <p class="total">Total: <span> $ <?php echo $total; // si es el ultimo ?> </span></p>
                <!--formulario para borrar cita-->
                <form action="/api/eliminar" method="POST"> <!--envpia al endpoint de eliminar con metodo post-->
                    <input type="hidden" name="id" value="<?php echo $cita->id; ?>"> <!-- boton oculto con el id de la cita-->
                    <input type="submit" class="boton-eliminar" value="Eliminar">
                </form>
            <?php } //fin del if?> 
        <?php }  //fin de foreach?> 
    </ul>
</div>

<?php 
    $script = "<script src ='build/js/buscador.js'></script>"
?>