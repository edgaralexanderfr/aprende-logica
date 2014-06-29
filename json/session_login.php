<?php
  
  require_once '../php/config.php';
  require_once '../system/function.valpost.php';
  
  header('Content-Type: application/json');
  
  try {
    if (!valpost($_POST, array('alias', 'password'))) {
      throw new Exception('Parámetros inválidos.', 1);
    }
    
    require_once '../php/connection.php';
    
    if (!$mysqli) {
      throw new Exception('Base de datos no disponible.', 2);
    }
    
    require_once '../system/class.Session.php';
    
    Session :: $mysqli = $mysqli;
    Session :: login($_POST['alias'], $_POST['password']);
    
    echo json_encode(array(
      'errno' => 0, 
      'message' => 'Has iniciado sesión correctamente.'
    ));
  } catch (Exception $ex1) {
    echo json_encode(array(
      'errno' => 1, 
      'message' => utf8_encode($ex1->getMessage())
    ));
  }