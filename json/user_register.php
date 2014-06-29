<?php
  
  require_once '../php/config.php';
  require_once '../system/function.valpost.php';
  
  header('Content-Type: application/json');
  
  try {
    if (!valpost($_POST, array('alias', 'password', 'confirmPassword', 'cedula', 'name', 'lastName', 'email'))) {
      throw new Exception('Parámetros inválidos.', 1);
    }
    
    require_once '../system/class.User.php';
    
    $user = new User();
    $user->setAlias($_POST['alias']);
    $user->setPassword($_POST['password'], $_POST['confirmPassword']);
    $user->setCedula($_POST['cedula']);
    $user->setName($_POST['name']);
    $user->setLastName($_POST['lastName']);
    $user->setEmail($_POST['email']);
    
    require_once '../php/connection.php';
    
    if (!$mysqli) {
      throw new Exception('Base de datos no disponible.', 2);
    }
    
    User :: $mysqli = $mysqli;
    $user->register();
    
    echo json_encode(array(
      'errno' => 0, 
      'message' => 'Tu cuenta ha sido creada correctamente.'
    ));
  } catch (Exception $ex1) {
    echo json_encode(array(
      'errno' => 1, 
      'message' => utf8_encode($ex1->getMessage())
    ));
  }