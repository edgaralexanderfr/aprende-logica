<?php
  
  $mysqli = @mysqli_connect('127.0.0.1', 'root', '', 'aprende_logica');
  
  function closeMysqli ($mysqli) {
    mysqli_close($mysqli);
  }
  
  register_shutdown_function('closeMysqli', $mysqli);