<?php
  
  require_once './php/config.php';
  require_once './php/connection.php';
  
  try {
    if (!$mysqli) {
      throw new Exception('Base de datos no disponible.', 1);
    }
    
    require_once './system/class.Assignment.php';
    
    Assignment :: $mysqli = $mysqli;
    Assignment :: downloadLast();
    
    exit();
  } catch (Exception $ex1) {
    
  }
  
?>
<!doctype html>
  <html>
    <head>
      <title>Error de descarga</title>
    </head>
    <body>
      <?= $ex1->getMessage(); ?> 
      <a href="./?uri=options">Volver</a>
    </body>
  </html>