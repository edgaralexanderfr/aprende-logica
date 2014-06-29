<?php
  
  /**
   * Copyright 2014 - Edgar Alexander Franco
   *
   * @author Edgar Alexander Franco
   * @version 1.0.0
   */
  
  /**
   * Deletes the content of a directory including it's sub-folders.
   *
   * @param {string} $dir Path of the directory to clear.
   * @param {boolean} $deldir (Optional) if it's true, then the directory to clear will be deleted too.
   */
  function cleardir ($dir, $deldir = false) {
    $files = scandir($dir);
    
    foreach ($files as $file) {
      if ($file == '.' || $file == '..') {
        continue;
      }
      
      $path = $dir . $file;
      
      if (is_dir($path)) {
        cleardir($path . '/', true);
      } else {
        unlink($path);
      }
    }
    
    if ($deldir) {
      rmdir($dir);
    }
  }