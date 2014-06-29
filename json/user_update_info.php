<?php
  
  require_once '../php/config.php';
  require_once '../system/function.valpost.php';
  
  header('Content-Type: application/json');
  
  try {
    if (!valpost($_POST, array('cedula', 'name', 'lastName', 'email'))) {
      throw new Exception('Parámetros inválidos.', 1);
    }
    
    require_once '../system/class.Session.php';
    
    $session = Session :: get();
    
    if ($session == null) {
      throw new Exception('Debes iniciar sesión para actualizar tus datos.', 1);
    }
    
    $session->setCedula($_POST['cedula']);
    $session->setName($_POST['name']);
    $session->setLastName($_POST['lastName']);
    $session->setEmail($_POST['email']);
    
    require_once '../php/connection.php';
    
    if (!$mysqli) {
      throw new Exception('Base de datos no disponible.', 2);
    }
    
    User :: $mysqli = $mysqli;
    $session->updateInfo();
    Session :: update();
    
    echo json_encode(array(
      'errno' => 0, 
      'message' => 'Datos actualizados correctamente.'
    ));
  } catch (Exception $ex1) {
    echo json_encode(array(
      'errno' => 1, 
      'message' => utf8_encode($ex1->getMessage())
    ));
  }