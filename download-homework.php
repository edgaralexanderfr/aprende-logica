<?php
  
  require_once './php/config.php';
  require_once './system/function.valpost.php';
  
  try {
    if (!valpost($_GET, array('id'))) {
      throw new Exception('Parámetros inválidos.', 1);
    }
    
    require_once './system/class.Session.php';
    
    $session = Session :: get();
    
    if ($session == null || !$session->admin) {
      throw new Exception('No estás autorizado para ésto.', 2);
    }
    
    require_once './php/connection.php';
    
    if (!$mysqli) {
      throw new Exception('Base de datos no disponible.', 3);
    }
    
    require_once './system/class.Homework.php';
    
    Homework :: $mysqli = $mysqli;
    $homework = new Homework();
    $homework->setId($_GET['id']);
    $homework->download();
    
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
      <?= $ex1->getMessage() ?> 
      <a href="./?uri=homeworks">Volver</a>
    </body>
  </html>