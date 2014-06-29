/**
 * Copyright 2014 - Edgar Alexander Franco
 *
 * @author Edgar Alexander Franco
 * @version 1.0.0
 */

var Session = (function () {
  var self = {};
  
  /**
   * Verify the credentials and provide the access to the system.
   *
   * @param {string} alias User's alias.
   * @param {string} password User's password.
   * @param {function} callback Function to be called.
   */
  self.login = function (alias, password, callback) {
    try {
      User.valAlias(alias);
      User.valPassword(password, password);
    } catch (ex1) {
      callback(1, 'Alias o contraseña inválidos.');
      
      return;
    }
    
    $.post('./json/session_login.php', {
      alias : alias, 
      password : sha1(password)
    }, function (data) {
      try {
        var response = JSON.parse(data);
        callback(response.errno, response.message);
      } catch (ex1) {
        console.error(data);
        callback(1, 'No se pudo procesar el acceso.');
      }
    });
  }
  
  return self;
})();