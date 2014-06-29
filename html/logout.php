<?php
  
  require_once '../php/config.php';
  require_once '../system/class.Session.php';
  
  Session :: close();
  
?>
<script type="text/javascript">
  (function ($) {
    var selector1 = $($('.nav a')[3]);
    var selector2 = $($('.nav a')[4]);
    
    selector1.attr('href', './register');
    selector1.html('Registrarse');
    selector2.attr('href', './login');
    selector2.html('Iniciar sesión');
  })(jQuery);
  
  loadPage('./', function () {
    history.pushState({ url : './' }, '', './');
  });
</script>