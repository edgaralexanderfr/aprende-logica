<?php
  
  /**
   * Copyright 2014 - Edgar Alexander Franco
   *
   * @author Edgar Alexander Franco
   * @version 1.0.0
   */
  
  require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SITE_PATH'] . 'system/class.JSONCache.php';
  
  class User {
    const MIN_ID = 1;
    const MAX_ID = 9999999999;
    const ALIAS_MIN_LENGTH = 4;
    const ALIAS_MAX_LENGTH = 100;
    const PASSWORD_MIN_LENGTH = 8;
    const MIN_CEDULA = 1000000;
    const MAX_CEDULA = 999999999;
    const NAME_MIN_LENGTH = 3;
    const NAME_MAX_LENGTH = 50;
    const LAST_NAME_MIN_LENGTH = 3;
    const LAST_NAME_MAX_LENGTH = 50;
    
    public static $mysqli = null;
    public $id = null;
    public $alias = null;
    public $passwordHash = null;
    public $salt = null;
    public $cedula = null;
    public $name = null;
    public $lastName = null;
    public $email = null;
    public $registrationDate = null;
    public $admin = null;
    
    /**
     * Sets and validate the id.
     *
     * @param {int} $id User's id.
     */
    public final function setId ($id) {
      if (!is_numeric($id) || $id < self :: ID_MIN || $id > self :: ID_MAX) {
        throw new Exception('Id inválido.', 1);
      }
      
      $this->id = (int) $id;
    }
    
    /**
     * Sets and validate the alias.
     *
     * @param {string} $alias User's alias.
     */
    public final function setAlias ($alias) {
      $length = strlen($alias);
      
      if ($length < self :: ALIAS_MIN_LENGTH) {
        throw new Exception('Alias muy corto.', 1);
      }
      
      if ($length > self :: ALIAS_MAX_LENGTH) {
        throw new Exception('Alias muy largo.', 2);
      }
      
      if (!ctype_alnum($alias)) {
        throw new Exception('Procura que tu alias sea alfanumérico.', 3);
      }
      
      $this->alias = (string) $alias;
    }
    
    /**
     * Sets and validate the password.
     *
     * @param {string} $password User's password.
     * @param {string} $confirmPassword Password confirmation.
     */
    public final function setPassword ($password, $confirmPassword) {
      if ($password != $confirmPassword) {
        throw new Exception('Las contraseñas no coincíden.', 1);
      }
      
      $length = strlen($password);
      
      if ($length < self :: PASSWORD_MIN_LENGTH) {
        throw new Exception('Tu contraseña es muy corta.', 2);
      }
      
      $this->salt = md5(mcrypt_create_iv(32));
      $this->passwordHash = sha1(sha1((string) $password) . $this->salt);
    }
    
    /**
     * Sets and validate the cedula.
     *
     * @param {string} $cedula User's cedula.
     */
    public final function setCedula ($cedula) {
      $cedula = str_replace('.', '', $cedula);
      
      if (!is_numeric($cedula)) {
        throw new Exception('La cédula debe ser un número.', 1);
      }
      
      $cedula = (int) $cedula;
      
      if ($cedula < self :: MIN_CEDULA || $cedula > self :: MAX_CEDULA) {
        throw new Exception('Cédula inválida.', 2);
      }
      
      $this->cedula = number_format((string) $cedula, 0, '', '.');
    }
    
    /**
     * Sets and validate the name.
     *
     * @param {string} $name User's name.
     */
    public final function setName ($name) {
      $length = strlen($name);
      
      if ($length < self :: NAME_MIN_LENGTH) {
        throw new Exception('Tu nombre es muy corto.', 1);
      }
      
      if ($length > self :: NAME_MAX_LENGTH) {
        throw new Exception('Tu nombre es muy largo.', 2);
      }
      
      $this->name = (string) $name;
    }
    
    /**
     * Sets and validate the lastName.
     *
     * @param {string} $lastName User's lastName.
     */
    public final function setLastName ($lastName) {
      $length = strlen($lastName);
      
      if ($length < self :: LAST_NAME_MIN_LENGTH) {
        throw new Exception('Tu apellido es muy corto.', 1);
      }
      
      if ($length > self :: LAST_NAME_MAX_LENGTH) {
        throw new Exception('Tu apellido es muy largo.', 2);
      }
      
      $this->lastName = (string) $lastName;
    }
    
    /**
     * Sets and validate the email.
     *
     * @param {string} $email User's email.
     */
    public final function setEmail ($email) {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Tu dirección de correo electrónico es inválida.', 1);
      }
      
      $this->email = (string) $email;
    }
    
    /**
     * Registers the user's account.
     *
     * Purges cache: session_login
     */
    public final function register () {
      $mysqli = self :: $mysqli;
      
      $alias = mysqli_real_escape_string($mysqli, $this->alias);
      $passwordHash = mysqli_real_escape_string($mysqli, $this->passwordHash);
      $salt = mysqli_real_escape_string($mysqli, $this->salt);
      $cedula = mysqli_real_escape_string($mysqli, $this->cedula);
      $name = mysqli_real_escape_string($mysqli, $this->name);
      $lastName = mysqli_real_escape_string($mysqli, $this->lastName);
      $email = mysqli_real_escape_string($mysqli, $this->email);
      
      $query = mysqli_query($mysqli, "call user_register('" . $alias . "', '" . $passwordHash . "', '" . $salt . "', '" . $cedula . "', '" . $name . "', '" . $lastName . "', '" . $email . "')");
      
      if (!$query) {
        throw new Exception('Error de base de datos.', 1);
      }
      
      $row = mysqli_fetch_assoc($query);
      mysqli_free_result($query);
      
      $errno = (int) $row['errno'];
      
      if ($errno == 1) {
        throw new Exception('Ya existe un usuario registrado con éste alias.', 2);
      }
      
      JSONCache :: purge('session_login/');
    }
    
    /**
     * Updates the basic info of the user.
     *
     * Purges cache: session_login, homework_get_corrected, homework_get_uncorrected
     */
    public final function updateInfo () {
      $mysqli = self :: $mysqli;
      
      $cedula = mysqli_real_escape_string($mysqli, $this->cedula);
      $name = mysqli_real_escape_string($mysqli, $this->name);
      $lastName = mysqli_real_escape_string($mysqli, $this->lastName);
      $email = mysqli_real_escape_string($mysqli, $this->email);
      
      $query = mysqli_query($mysqli, "call user_update_info(" . $this->id . ", '" . $cedula . "', '" . $name . "', '" . $lastName . "', '" . $email . "')");
      
      if (!$query) {
        throw new Exception('Error de base de datos.', 1);
      }
      
      JSONCache :: purge('session_login/');
      JSONCache :: purge('homework_get_pages/');
      JSONCache :: purge('homework_get_corrected/');
      JSONCache :: purge('homework_get_uncorrected/');
      JSONCache :: purge('homework_download/');
    }
    
    /**
     * Updates the password of the user.
     *
     * Purges cache: session_login
     */
    public final function updatePassword () {
      $mysqli = self :: $mysqli;
      
      $passwordHash = mysqli_real_escape_string($mysqli, $this->passwordHash);
      $salt = mysqli_real_escape_string($mysqli, $this->salt);
      
      $query = mysqli_query($mysqli, "call user_update_password(" . $this->id . ", '" . $passwordHash . "', '" . $salt . "')");
      
      if (!$query) {
        throw new Exception('Error de base de datos.', 1);
      }
      
      JSONCache :: purge('session_login/');
    }
  }