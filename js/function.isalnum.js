/**
 * Copyright 2014 - Edgar Alexander Franco
 *
 * @author Edgar Alexander Franco
 * @version 1.0.0
 */

/**
 * Checks if the specified string is alphanumeric.
 *
 * @param {string} string String to check.
 */
function isalnum (string) {
  return /^[a-zA-Z0-9]*$/g.test(string);
}