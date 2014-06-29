<?php
  
  /**
   * Copyright 2014 - Edgar Alexander Franco.
   *
   * @author Edgar Alexander Franco
   * @version 1.0.0
   */
  
  require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SITE_PATH'] . '/system/function.xssfil.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SITE_PATH'] . '/system/class.User.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SITE_PATH'] . '/system/class.JSONCache.php';
  
  class Session {
    const SESSION_NAME = 'QsApYzSkJl12';
    
    public static $mysqli = null;
    public static $session = null;
    
    /**
     * Returns an User object with all the info of the session, null in case that there's no session.
     *
     * @return {User}
     */
    public static function get () {
      session_name(self :: SESSION_NAME);
      session_start();
      
      if (!isset($_SESSION['aprende_logica_id'])) {
        return null;
      }
      
      self :: $session = new User();
      self :: $session->id = $_SESSION['aprende_logica_id'];
      self :: $session->alias = $_SESSION['aprende_logica_alias'];
      self :: $session->passwordHash = $_SESSION['aprende_logica_passwordHash'];
      self :: $session->salt = $_SESSION['aprende_logica_salt'];
      self :: $session->cedula = $_SESSION['aprende_logica_cedula'];
      self :: $session->name = $_SESSION['aprende_logica_name'];
      self :: $session->lastName = $_SESSION['aprende_logica_lastName'];
      self :: $session->email = $_SESSION['aprende_logica_email'];
      self :: $session->registrationDate = $_SESSION['aprende_logica_registrationDate'];
      self :: $session->admin = $_SESSION['aprende_logica_admin'];
      
      return self :: $session;
    }
    
    /**
     * Updates the value of the global variables with the current state of the User object.
     */
    public static function update () {
      $_SESSION['aprende_logica_id'] = self :: $session->id;
      $_SESSION['aprende_logica_alias'] = self :: $session->alias;
      $_SESSION['aprende_logica_passwordHash'] = self :: $session->passwordHash;
      $_SESSION['aprende_logica_salt'] = self :: $session->salt;
      $_SESSION['aprende_logica_cedula'] = self :: $session->cedula;
      $_SESSION['aprende_logica_name'] = xssfil(utf8_decode(self :: $session->name));
      $_SESSION['aprende_logica_lastName'] = xssfil(utf8_decode(self :: $session->lastName));
      $_SESSION['aprende_logica_email'] = self :: $session->email;
      $_SESSION['aprende_logica_registrationDate'] = self :: $session->registrationDate;
      $_SESSION['aprende_logica_admin'] = self :: $session->admin;
    }
    
    /**
     * Starts a new session through the provided parameters.
     * Cache reference: session_login
     *
     * @param {string} $alias User's alias.
     * @param {string} $password User's password.
     */
    public static function login ($alias, $password) {
      $cacheRef = 'session_login/';
      $query = "call session_login('" . $alias . "', '" . $password . "')";
      $data = JSONCache :: get($query, $cacheRef);
      
      if ($data === null) {
        $mysqli = self :: $mysqli;
        
        $alias = mysqli_real_escape_string($mysqli, $alias);
        $password = mysqli_real_escape_string($mysqli, $password);
        
        $mysqliQuery = mysqli_query($mysqli, "call session_login('" . $alias . "', '" . $password . "')");
        
        if (!$mysqliQuery) {
          throw new Exception('Error de base de datos.', 1);
        }
        
        $data = ($row = mysqli_fetch_assoc($mysqliQuery)) ? $row : array() ;
        mysqli_free_result($mysqliQuery);
        
        JSONCache :: store($query, $data, $cacheRef);
      }
      
      if (!isset($data['id'])) {
        throw new Exception('Alias o contraseña inválidos.', 2);
      }
      
      session_name(self :: SESSION_NAME);
      session_start();
      session_regenerate_id(true);
      
      $_SESSION['aprende_logica_id'] = (int) $data['id'];
      $_SESSION['aprende_logica_alias'] = (string) $data['alias'];
      $_SESSION['aprende_logica_passwordHash'] = (string) $data['password_hash'];
      $_SESSION['aprende_logica_salt'] = (string) $data['salt'];
      $_SESSION['aprende_logica_cedula'] = $data['cedula'];
      $_SESSION['aprende_logica_name'] = xssfil(utf8_decode($data['name']));
      $_SESSION['aprende_logica_lastName'] = xssfil(utf8_decode($data['last_name']));
      $_SESSION['aprende_logica_email'] = $data['email'];
      $_SESSION['aprende_logica_registrationDate'] = $data['registration_date'];
      $_SESSION['aprende_logica_admin'] = (boolean) $data['admin'];
    }
    
    /**
     * Closes the current session deleting all the global variables.
     */
    public static function close () {
      session_name(self :: SESSION_NAME);
      session_start();
      session_regenerate_id(true);
      setCookie(self :: SESSION_NAME, '/', time() - 3600);
      session_destroy();
    }
  }