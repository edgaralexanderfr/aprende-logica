<?php
  
  require_once '../php/config.php';
  require_once '../system/class.Session.php';
  
  $session = Session :: get();
  
?>
<?php if ($session !== null) : ?>
<script type="text/javascript">
  loadPage('./', function () {
    history.pushState({ url : './' }, '', './');
  });
</script>
<?php exit(); endif; ?>
<div class="loginLeft">
  <div class="title pageTitle">
    Iniciar sesi�n..
  </div>
  <div class="firstItem">
    <div class="paragraph left">
      Tu alias:
    </div>
    <div class="right">
      <input class="input1" id="alias" type="text" placeholder="Ejem. Edgar, Gilda, Alex, Carlos, Jessica, etc..." />
    </div>
  </div>
  <div class="paragraph item">
    <div class="left">
      Tu contrase�a:
    </div>
    <div class="right">
      <input class="input1" id="password" type="password" placeholder="********" />
    </div>
  </div>
  <div class="loginErrorMessage"></div>
  <div class="access">
    <div class="paragraph left">
      <span id="verifyingMessage">Verificando...</span>
    </div>
    <div class="right">
      <button class="button1" id="loginButton">Acceder</button>
    </div>
  </div>
</div>
<div class="loginRight">
  <div class="paragraph dialogTitle">
    Un d�a, un hombre abord� al gran fil�sofo S�crates...
  </div>
  <div class="image">
    <img src="./img/login/socrates.jpg" alt />
  </div>
  <div class="paragraph dialog">
    <span class="platon">"�Sabes, S�crates, lo que acabo de o�r sobre uno de tus disc�pulos?"</span><br /><br /><span class="socrates">"Antes me gustar�a que pasaras la prueba del triple filtro. El primero es el de la Verdad. �Est�s seguro de que lo que vas a decirme es cierto? Plat�n"<br /><br /></span><span class="platon">"Me acabo de enterar y ... "</span><br /><br /><span class="socrates">"... o sea, que no sabes si es cierto. El segundo filtro es el de la Bondad. �Quieres contarme algo bueno sobre mi disc�pulo?"</span><br /><br /><span class="platon">"Todo lo contrario."</span><br /><br /><span class="socrates">"Conque quieres contarme algo malo de �l y sin saber si es cierto. No obstante a�n podr�a pasar el tercer filtro, el de la Utilidad, �me va a ser �til?"</span><br /><br /><span class="platon">"No mucho."</span><br /><br /><span class="socrates">"Si no es ni cierto, ni bueno, ni �til, �para qu� contarlo?"</span>
  </div>
</div>
<script type="text/javascript">
  (function ($) {
    /**
     * Verify the credentials and provide the access to the system.
     */
    function login () {
      $('#verifyingMessage').show();
      Session.login($('#alias').val(), $('#password').val(), function (errno, message) {
        $('#verifyingMessage').hide();
        
        if (errno == 1) {
          $('.loginErrorMessage').hide();
          $('.loginErrorMessage').html(message);
          $('.loginErrorMessage').fadeIn('fast');
        } else {
          var selector1 = $($('.nav a')[3]);
          var selector2 = $($('.nav a')[4]);
          
          selector1.attr('href', './session');
          selector1.html('Sesi�n');
          selector2.attr('href', './logout');
          selector2.html('Cerrar sesi�n');
          
          $('input').val('');
          loadPage('./', function () {
            history.pushState({ url : './' }, '', './');
          });
        }
      });
    }
    
    $('input').keyup(function (evt) {
      if (evt.which == 13) {
        login();
      }
    });
    
    $('#loginButton').click(function (evt) {
      login();
    });
  })(jQuery);
  
  $('#alias').focus();
</script>