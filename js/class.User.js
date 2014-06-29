/**
 * Copyright 2014 - Edgar Alexander Franco
 *
 * @author Edgar Alexander Franco
 * @version 1.0.0
 */

var User = (function () {
  var self = {};
  
  var ALIAS_MIN_LENGTH = 4;
  var ALIAS_MAX_LENGTH = 100;
  var PASSWORD_MIN_LENGTH = 8;
  var MIN_CEDULA = 1000000;
  var MAX_CEDULA = 999999999;
  var NAME_MIN_LENGTH = 3;
  var NAME_MAX_LENGTH = 50;
  var LAST_NAME_MIN_LENGTH = 3;
  var LAST_NAME_MAX_LENGTH = 50;
  
  /**
   * Validates the alias.
   *
   * @param {string} alias User's alias.
   */
  self.valAlias = function (alias) {
    var length = alias.length;
    
    if (length < ALIAS_MIN_LENGTH) {
      throw new ALException('Alias muy corto.', 1);
    }
    
    if (length > ALIAS_MAX_LENGTH) {
      throw new ALException('Alias muy largo.', 2);
    }
    
    if (!isalnum(alias)) {
      throw new ALException('Procura que tu alias sea alfanumérico.', 3);
    }
  }
  
  /**
   * Validates the password.
   *
   * @param {string} password User's password.
   * @param {string} confirmPassword Password confirmation.
   */
  self.valPassword = function (password, confirmPassword) {
    if (password != confirmPassword) {
      throw new ALException('Las contraseñas no coincíden.', 1);
    }
    
    var length = password.length;
    
    if (length < PASSWORD_MIN_LENGTH) {
      throw new ALException('Tu contraseña es muy corta.', 2);
    }
  }
  
  /**
   * Validates the cedula.
   *
   * @param {string} cedula User's cedula.
   */
  self.valCedula = function (cedula) {
    cedula = cedula.replace(/\./g, '');
    
    if (cedula == '' || isNaN(cedula)) {
      throw new ALException('La cédula debe ser un número.', 1);
    }
    
    if (cedula < MIN_CEDULA || cedula > MAX_CEDULA) {
      throw new ALException('Cédula inválida.', 2);
    }
  }
  
  /**
   * Validates the name.
   *
   * @param {string} name User's name.
   */
  self.valName = function (name) {
    var length = name.length;
    
    if (length < NAME_MIN_LENGTH) {
      throw new ALException('Tu nombre es muy corto.', 1);
    }
    
    if (length > NAME_MAX_LENGTH) {
      throw new ALException('Tu nombre es muy largo.', 2);
    }
  }
  
  /**
   * Validates the lastName.
   *
   * @param {string} lastName User's lastName.
   */
  self.valLastName = function (lastName) {
    var length = lastName.length;
    
    if (length < LAST_NAME_MIN_LENGTH) {
      throw new ALException('Tu apellido es muy corto.', 1);
    }
    
    if (length > LAST_NAME_MAX_LENGTH) {
      throw new ALException('Tu apellido es muy largo.', 2);
    }
  }
  
  /**
   * Validates and send the request to the server for register the user.
   *
   * @param {string} alias User's alias.
   * @param {string} password User's password.
   * @param {string} confirmPassword Password confirmation.
   * @param {string} cedula User's cedula.
   * @param {string} name User's name.
   * @param {string} lastName User's lastName.
   * @param {string} email User's email.
   * @param {function} callback Callback function that will handle the result.
   */
  self.register = function (alias, password, confirmPassword, cedula, name, lastName, email, callback) {
    try {
      self.valAlias(alias);
      self.valPassword(password, confirmPassword);
      self.valCedula(cedula);
      self.valName(name);
      self.valLastName(lastName);
    } catch (ex1) {
      callback(1, ex1.message);
      
      return;
    }
    
    $.post('./json/user_register.php', {
      alias : alias, 
      password : password, 
      confirmPassword : confirmPassword, 
      cedula : cedula, 
      name : name, 
      lastName : lastName, 
      email : email
    }, function (data) {
      try {
        var response = JSON.parse(data);
        callback(response.errno, response.message);
      } catch (ex2) {
        console.error(data);
        callback(1, 'No se pudo procesar el registro.');
      }
    });
  }
  
  /**
   * Validates and send the request to the server for update the user's info.
   *
   * @param {string} cedula User's cedula.
   * @param {string} name User's name.
   * @param {string} lastName User's lastName.
   * @param {string} email User's email.
   * @param {function} callback Callback function that will handle the result.
   */
  self.updateInfo = function (cedula, name, lastName, email, callback) {
    try {
      self.valCedula(cedula);
      self.valName(name);
      self.valLastName(lastName);
    } catch (ex1) {
      callback(1, ex1.message);
      
      return;
    }
    
    $.post('./json/user_update_info.php', {
      cedula : cedula, 
      name : name, 
      lastName : lastName, 
      email : email
    }, function (data) {
      try {
        var response = JSON.parse(data);
        callback(response.errno, response.message);
      } catch (ex1) {
        console.error(data);
        callback(1, 'No se pudo realizar la actualización.');
      }
    });
  }
  
  /**
   * Validates and send the request to the server for update the password.
   *
   * @param {string} password User's password.
   * @param {string} confirmPassword Password confirmation.
   * @param {string} oldPassword User's oldPassword.
   * @param {function} callback Callback function that will handle the result.
   */
  self.updatePassword = function (password, confirmPassword, oldPassword, callback) {
    try {
      self.valPassword(password, confirmPassword);
      
      try {
        self.valPassword(oldPassword, oldPassword);
      } catch (ex2) {
        throw new ALException('Tu contraseña actual es incorrecta.', 1);
      }
    } catch (ex1) {
      callback(1, ex1.message);
      
      return;
    }
    
    $.post('./json/user_update_password.php', {
      password : password, 
      confirmPassword : confirmPassword, 
      oldPassword : sha1(oldPassword)
    }, function (data) {
      try {
        var response = JSON.parse(data);
        callback(response.errno, response.message);
      } catch (ex1) {
        console.error(data);
        callback(1, 'No se pudo realizar la actualización.');
      }
    });
  }
  
  return self;
})();