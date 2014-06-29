<?php
  
  require_once '../php/config.php';
  require_once '../system/class.Session.php';
  
  $session = Session :: get();
  
?>
<?php if ($session != null) : ?>
<script type="text/javascript">
  loadPage('./', function () {
    history.pushState({ url : './' }, '', './');
  });
</script>
<?php exit(); endif; ?>
<div class="pageTitle">Registrarse...</div>
<div class="firstItem">
  <div class="paragraph left">Tu alias:</div>
  <div class="right">
    <input class="input1" id="alias" type="text" placeholder="Ejem. Edgar, Gilda, Alex, Carlos, Jessica, etc..." />
  </div>
</div>
<div class="item">
  <div class="paragraph left">Contraseña:</div>
  <div class="right">
    <input class="input1" id="password" type="password" placeholder="********" />
  </div>
</div>
<div class="item">
  <div class="paragraph left">Confirmar contraseña:</div>
  <div class="right">
    <input class="input1" id="confirmPassword" type="password" placeholder="********" />
  </div>
</div>
<div class="item">
  <div class="paragraph left">Tu cédula:</div>
  <div class="right">
    <input class="input1" id="cedula" type="type" placeholder="xx.xxx.xxx" />
  </div>
</div>
<div class="item">
  <div class="paragraph left">Tu nombre:</div>
  <div class="right">
    <input class="input1" id="name" type="type" placeholder="Ejem. Sergio A., José Raúl, etc." />
  </div>
</div>
<div class="item">
  <div class="paragraph left">Tu apellido:</div>
  <div class="right">
    <input class="input1" id="lastName" type="type" placeholder="Ejem. Francisco M., etc." />
  </div>
</div>
<div class="item">
  <div class="paragraph left">Tu correo:</div>
  <div class="right">
    <input class="input1" id="email" type="type" placeholder="Ejem. usuario@dominio.com" />
  </div>
</div>
<div id="message"></div>
<div class="submit">
  <div class="left">
    <span class="paragraph" id="sendingMessage">Enviando...</span>
  </div>
  <div class="right">
    <button class="button1" id="submitButton">¡Vamos!</button>
  </div>
</div>
<script type="text/javascript">
  $('#submitButton').click(function (evt) {
    $('#sendingMessage').show();
    User.register($('#alias').val(), $('#password').val(), $('#confirmPassword').val(), $('#cedula').val(), $('#name').val(), $('#lastName').val(), $('#email').val(), function (errno, message) {
      if (errno == 0) {
        $('input').val('');
        $('#message').attr('class', 'successMessage');
      } else {
        $('#message').attr('class', 'errorMessage');
      }
      
      $('#message').hide();
      $('#message').html(message);
      $('#message').fadeIn('fast');
      $('#sendingMessage').hide();
    });
  });
  
  $('#alias').focus();
</script>