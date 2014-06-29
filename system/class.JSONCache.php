<?php
  
  /**
   * Copyright 2014 - Edgar Alexander Franco
   *
   * @author Edgar Alexander Franco
   * @version 1.0.0
   */
  
  require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SITE_PATH'] . '/system/function.utf8encodearray.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SITE_PATH'] . '/system/function.cleardir.php';
  
  class JSONCache {
    const ENABLE_STORE = true;
    const ENABLE_GET = true;
    const ENABLE_PURGE = true;
    const ENCODE_TO_UTF8 = false;
    
    public static $PATH;
    
    /**
     * Saves the specified data in the cache hashing it's database query as a reference.
     * NOTE: make sure that each string contained inside the array is correctly encoded in UTF-8.
     *
     * @param {string} $query The performed query to be cached.
     * @param {array} $data Result data of the query.
     * @param {string} $dir (Optional) sub-directory of the data under the cache folder.
     */
    public static function store ($query, $data, $dir = '') {
      if (!self :: ENABLE_STORE) {
        return;
      }
      
      if (!is_dir(self :: $PATH)) {
        mkdir(self :: $PATH);
      }
      
      $dir = self :: $PATH . $dir;
      
      if (!is_dir($dir)) {
        @mkdir($dir);
      }
      
      $hash = sha1($query);
      $path = $dir . $hash;
      
      if (self :: ENCODE_TO_UTF8) {
        $data = utf8encodearray($data);
      }
      
      @file_put_contents($path, json_encode($data));
    }
    
    /**
     * Returns the stored data of a specific query.
     *
     * @param {string} $query Stored data for the requested query.
     * @param {string} $dir (Optional) sub-directory where the data of the query is located.
     * @return {array}
     */
    public static function get ($query, $dir = '') {
      if (!self :: ENABLE_GET) {
        return null;
      }
      
      $hash = sha1($query);
      $json = @file_get_contents(self :: $PATH . $dir . $hash);
      
      if ($json === false) {
        return null;
      }
      
      return json_decode($json, true);
    }
    
    /**
     * Purges the cache.
     *
     * @param {string} $dir (Optional) if it is specified, then it will only purge the directory.
     */
    public static function purge ($dir = '') {
      if (!self :: ENABLE_PURGE) {
        return;
      }
      
      $path = self :: $PATH . $dir;
      
      if (is_dir($path)) {
        cleardir($path);
      }
    }
  }
  
  JSONCache :: $PATH = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SITE_PATH'] . 'system/cache/';