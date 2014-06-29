<?php
  
  /**
   * Copyright 2014 - Edgar Alexander Franco
   *
   * @author Edgar Alexander Franco
   * @version 1.0.0
   */
  
  /**
   * Validates the keys of an array.
   *
   * @param {array} $array Array to be validated.
   * @param {array} $keys Keys that the array must have.
   * @return {boolean}
   */
  function valpost ($array, $keys) {
    $total = 0;
    
    foreach ($keys as $key) {
      if (!isset($array[ $key ])) {
        return false;
      }
      
      $total++;
    }
    
    if (count($array) != $total) {
      return false;
    }
    
    return true;
  }