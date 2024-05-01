<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body>
    <div class="contenedor-app">
        <div class="imagen"></div> <!--aqui va la imagen-->
        <div class="app">
            <div>
                <p class="copyright">Powered by Jacob Gómez Carrillo <?php echo date('Y') ?></p>
            </div>
            <?php echo $contenido; ?>

            
        </div>
        
    </div>
    
<?php echo $script ?? ''; ?> <!-- Imprime script o nada, definida en index.php -->
            
</body>
</html>