<?php
  
  require_once '../php/config.php';
  require_once '../system/function.valpost.php';
  
  header('Content-Type: application/json');
  
  try {
    if (!valpost($_FILES, array('assignment'))) {
      throw new Exception('No ha seleccionado ning�n archivo o es demasiado grande.', 1);
    }
    
    require_once '../system/class.Session.php';
    
    $session = Session :: get();
    
    if ($session == null || !$session->admin) {
      throw new Exception('No est�s autorizado para �sto.', 2);
    }
    
    require_once '../php/connection.php';
    
    if (!$mysqli) {
      throw new Exception('Base de datos no disponible.', 3);
    }
    
    require_once '../system/class.Assignment.php';
    
    Assignment :: $mysqli = $mysqli;
    $assignment = new Assignment();
    $assignment->publish($_FILES['assignment']);
    
    echo json_encode(array(
      'errno' => 0, 
      'message' => utf8_encode('La asignaci�n ha sido publicada correctamente.')
    ));
  } catch (Exception $ex1) {
    echo json_encode(array(
      'errno' => 1, 
      'message' => utf8_encode($ex1->getMessage())
    ));
  }