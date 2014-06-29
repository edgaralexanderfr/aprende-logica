/**
 * Copyright 2014 - Edgar Alexander Franco
 *
 * @author Edgar Alexander Franco
 * @version 1.0.0
 */

/**
 * Aprende Lógica Exception.
 *
 * @param {string} message Error message.
 * @param {number} code Error code.
 */
function ALException (message, code) {
  this.message = message;
  this.code = code;
}