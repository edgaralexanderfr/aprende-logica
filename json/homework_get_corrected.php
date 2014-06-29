<?php
  
  require_once '../php/config.php';
  require_once '../system/function.valpost.php';
  
  header('Content-Type: application/json');
  
  try {
    $params = $_POST;
    
    if (!valpost($params, array('page'))) {
      throw new Exception('Parámetros inválidos.', 1);
    }
    
    require_once '../system/class.Session.php';
    
    $session = Session :: get();
    
    if ($session == null || !$session->admin) {
      throw new Exception('No estás autorizado para ésto.', 2);
    }
    
    require_once '../php/connection.php';
    
    if (!$mysqli) {
      throw new Exception('Base de datos no disponible.', 3);
    }
    
    require_once '../system/class.Homework.php';
    
    Homework :: $mysqli = $mysqli;
    $rows = Homework :: getCorrected($params['page'], 5);
    $homeworks = array();
    $days = array(
      'Mon' => 'Lun', 
      'Tue' => 'Mar', 
      'Wed' => 'Mie', 
      'Thu' => 'Jue', 
      'Fri' => 'Vie', 
      'Sat' => 'Sab', 
      'Sun' => 'Dom'
    );
    
    foreach ($rows as $row) {
      $dt = new DateTime($row['send_date'], new DateTimeZone($_SERVER['IN_DTZ']));
      $dt->setTimezone(new DateTimeZone($_SERVER['OUT_DTZ']));
      $day = $days[ $dt->format('D') ];
      
      $homework = array(
        'id' => (int) $row['id'], 
        'cedula' => $row['cedula'], 
        'name' => xssfil($row['name']), 
        'lastName' => xssfil($row['last_name']), 
        'sendDate' => $day . $dt->format('. d/m/Y h:i a.')
      );
      
      $homeworks[] = $homework;
    }
    
    echo json_encode($homeworks);
  } catch (Exception $ex1) {
    echo '[]';
  }