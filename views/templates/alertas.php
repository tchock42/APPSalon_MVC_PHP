<?php

    foreach($alertas as $key => $mensajes): //itera en cada key del arreglo asociativo
        // debuguear($mensajes); //imprime todos los mensajes
        // debuguear($key); //imprime los keys
        foreach($mensajes as $mensaje): //itera entre los mensajes
?>
    <div class="alerta <?php echo $key; ?>">
        <?php echo $mensaje; ?>
    </div>
<?php
        endforeach;
    endforeach;
?>