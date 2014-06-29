<?php
  
  require_once '../php/config.php';
  require_once '../system/function.valpost.php';
  
  header('Content-Type: application/json');
  
  try {
    if (!valpost($_FILES, array('homework'))) {
      throw new Exception('No has adjuntado tu tarea o es demasiado grande.', 1);
    }
    
    require_once '../system/class.Session.php';
    
    $session = Session :: get();
    
    if ($session == null) {
      throw new Exception('Debes iniciar sesión para poder enviar tu tarea.', 2);
    }
    
    require_once '../php/connection.php';
    
    if (!$mysqli) {
      throw new Exception('Base de datos no disponible.', 3);
    }
    
    require_once '../system/class.Homework.php';
    
    Homework :: $mysqli = $mysqli;
    $homework = new Homework();
    $homework->userId = $session->id;
    $homework->send($_FILES['homework']);
    
    echo json_encode(array(
      'errno' => 0, 
      'message' => 'Tu tarea ha sido enviada correctamente.'
    ));
  } catch (Exception $ex1) {
    echo json_encode(array(
      'errno' => 1, 
      'message' => utf8_encode($ex1->getMessage())
    ));
  }