<?php
  
  require_once '../php/config.php';
  require_once '../system/class.Session.php';
  
  $session = Session :: get();
  
?>
<?php if ($session == null) : ?>
<script type="text/javascript">
  loadPage('./', function () {
    history.pushState({ url : './' }, '', './');
  });
</script>
<?php exit(); endif; ?>
<div class="sessionLeft">
  <div class="title pageTitle">
    Mi sesión.
  </div>
  <div class="dataTitle">
    Información básica.
  </div>
  <div class="sessionItem">
    <div class="paragraph left">
      Tu cédula:
    </div>
    <div class="right">
      <input id="cedula" class="input1" type="text" value="<?= $session->cedula ?>" placeholder="xx.xxx.xxx" />
    </div>
  </div>
  <div class="sessionItem">
    <div class="paragraph left">
      Tu nombre:
    </div>
    <div class="right">
      <input id="name" class="input1" type="text" value="<?= $session->name ?>" placeholder="Ejem. Sergio A., José Raúl, etc." />
    </div>
  </div>
  <div class="sessionItem">
    <div class="paragraph left">
      Tu apellido:
    </div>
    <div class="right">
      <input id="lastName" class="input1" type="text" value="<?= $session->lastName ?>" placeholder="Ejem. Francisco M., etc." />
    </div>
  </div>
  <div class="sessionItem">
    <div class="paragraph left">
      Tu correo:
    </div>
    <div class="right">
      <input id="email" class="input1" type="text" value="<?= $session->email ?>" placeholder="Ejem. usuario@dominio.com" />
    </div>
  </div>
  <div id="infoMessage"></div>
  <div class="sessionUpdate">
    <div class="paragraph left">
      <span id="validatingInfo">Validando...</span>
    </div>
    <div class="right">
      <button id="updateInfoButton" class="button1">OK</button>
    </div>
  </div>
</div>
<div class="sessionRight">
  <div class="buttons">
    <?php if ($session->admin) : ?>
    <button id="homeworksButton" class="button1">Tareas</button>
    <?php endif; ?>
    <button id="logoutButton" class="button1">Cerrar</button>
  </div>
  <div class="dataTitle">
    Cambiar contraseña.
  </div>
  <div class="sessionItem">
    <div class="paragraph left">
      Contraseña:
    </div>
    <div class="right">
      <input id="password" class="input1" type="password" placeholder="********" />
    </div>
  </div>
  <div class="sessionItem">
    <div class="paragraph left">
      Confirmar contraseña:
    </div>
    <div class="right">
      <input id="confirmPassword" class="input1" type="password" placeholder="********" />
    </div>
  </div>
  <div class="sessionItem">
    <div class="paragraph left">
      Contraseña actual:
    </div>
    <div class="right">
      <input id="oldPassword" class="input1" type="password" placeholder="********" />
    </div>
  </div>
  <div id="passwordMessage" class="sessionSuccessMessage"></div>
  <div class="sessionUpdate">
    <div class="paragraph left">
      <span id="validatingPassword">Validando...</span>
    </div>
    <div class="right">
      <button class="button1" id="updatePasswordButton">OK</button>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('#homeworksButton').click(function () {
    loadPage('./homeworks', function () {
      history.pushState({ url : './homeworks' }, '', './homeworks');
    });
  });
  
  $('#logoutButton').click(function () {
    loadPage('./logout', function () {
      history.pushState({ url : './logout' }, '', './logout');
    });
  });
  
  $('#updateInfoButton').click(function (evt) {
    $('#validatingInfo').show();
    User.updateInfo($('#cedula').val(), $('#name').val(), $('#lastName').val(), $('#email').val(), function (errno, message) {
      $('#infoMessage').hide();
      $('#infoMessage').html(message);
      
      if (errno == 1) {
        $('#infoMessage').attr('class', 'sessionErrorMessage');
      } else {
        $('#infoMessage').attr('class', 'sessionSuccessMessage');
      }
      
      $('#infoMessage').fadeIn('fast');
      $('#validatingInfo').hide();
    });
  });
  
  $('#updatePasswordButton').click(function (evt) {
    $('#validatingPassword').show();
    User.updatePassword($('#password').val(), $('#confirmPassword').val(), $('#oldPassword').val(), function (errno, message) {
      $('#passwordMessage').hide();
      $('#passwordMessage').html(message);
      
      if (errno == 1) {
        $('#passwordMessage').attr('class', 'sessionErrorMessage');
      } else {
        $('input:password').val('');
        $('#passwordMessage').attr('class', 'sessionSuccessMessage');
      }
      
      $('#passwordMessage').fadeIn('fast');
      $('#validatingPassword').hide();
    });
  });
  
  $('#cedula').focus();
</script>