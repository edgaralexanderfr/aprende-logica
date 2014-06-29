<?php
  
  require_once './php/config.php';
  require_once './system/class.Session.php';
  
  $session = Session :: get();
  $page = './';
  
  if (isset($_GET['uri'])) {
    require_once './system/function.xssfil.php';
    
    $page .= xssfil(basename($_GET['uri']));
  }
  
?>
<!doctype html>
  <html lang="es">
    <head>
      <meta charset="iso-8859-1">
      <noscript>
        <meta http-equiv="refresh" content="0; url=./no-script.html">
      </noscript>
      <!-- Begin SEO -->
      <!-- Begin Google+ -->
      <meta itemprop="name" content="Aprende Lógica">
      <meta itemprop="description" content="Tu recurso digital.">
      <meta itemprop="image" content="http://<?= $_SERVER['DOMAIN'] ?>/img/about/gilda.jpg">
      <!-- End Google+ -->
      <!-- Begin Facebook -->
      <meta property="og:url" content="http://<?= $_SERVER['DOMAIN'] ?>/">
      <meta property="og:image" content="http://<?= $_SERVER['DOMAIN'] ?>/img/about/gilda.jpg">
      <meta property="og:description" content="Tu recurso digital.">
      <meta property="og:title" content="Aprende Lógica.">
      <meta property="og:site_name" content="Aprende Lógica.">
      <meta property="og:see_also" content="http://<?= $_SERVER['DOMAIN'] ?>/">
      <!-- End Facebook -->
      <!-- Begin Regular -->
      <meta charset="utf-8">
      <meta name="description" content="Tu recurso digital.">
      <meta name="keywords" content="aprende, lógica, algebra, matemáticas, tarea, blog, iutirla, uah, rodolfo, loero, arismendi, alejandro, humboldt, programación, algoritmos, proposición, proposicional, binario, bit, tablas, verdad, boole, módulo, objeto, instancia, estudio, alan, turing, aristóteles, sócrates, platón, c++, java, javascript, php, c, pascal, fortran, array, vector, matriz, función, clase, artículo, poo, polimorfismo, abstracción, herencia, variable, dato, tipo, entero, decimal, real">
      <link rel="canonical" href="http://<?= $_SERVER['DOMAIN'] ?>/" />
      <!-- End Regular -->
      <!-- End SEO -->
      <link rel="shortcut icon" href="./img/icon.ico" />
      <link rel="stylesheet" type="text/css" href="./css/index.css" />
      <link rel="stylesheet" type="text/css" href="./css/home.css" />
      <link rel="stylesheet" type="text/css" href="./css/options.css" />
      <link rel="stylesheet" type="text/css" href="./css/about.css" />
      <link rel="stylesheet" type="text/css" href="./css/register.css" />
      <link rel="stylesheet" type="text/css" href="./css/login.css" />
      <link rel="stylesheet" type="text/css" href="./css/session.css" />
      <link rel="stylesheet" type="text/css" href="./css/sources.css" />
      <link rel="stylesheet" type="text/css" href="./css/homeworks.css" />
      <link rel="stylesheet" type="text/css" href="./css/articles.css" />
      <link rel="stylesheet" type="text/css" href="./css/404.css" />
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script type="text/javascript" src="./js/lib/jquery-form/jquery.form.min.js"></script>
      <script type="text/javascript" src="./js/lib/php.js/utf8_encode.js"></script>
      <script type="text/javascript" src="./js/lib/php.js/sha1.js"></script>
      <script type="text/javascript" src="./pages.js"></script>
      <script type="text/javascript" src="./js/function.isalnum.js"></script>
      <script type="text/javascript" src="./js/class.ALException.js"></script>
      <script type="text/javascript" src="./js/class.User.js"></script>
      <script type="text/javascript" src="./js/class.Session.js"></script>
      <script type="text/javascript" src="./js/main.js"></script>
      <title>Aprende Lógica</title>
    </head>
    <body>
      <input id="params" type="hidden" data-page="<?= $page ?>" />
      <div class="loadingContent"></div>
      <header>
        <div class="header">
          <div class="left">
            <img src="./img/banner/logo.png" alt />
          </div>
          <div class="right">
            Tu recurso digital...
          </div>
        </div>
      </header>
      <nav>
        <div class="nav">
          <a class="option" href="./">Página principal</a>
          <a class="option" href="./options">Otras opciones</a>
          <a class="option" href="./about">Acerca de...</a>
          <?php if ($session == null) : ?>
          <a class="option" href="./register">Registrarse</a>
          <a class="option" href="./login">Iniciar sesión</a>
          <?php else : ?>
          <a class="option" href="./session">Sesión</a>
          <a class="option" href="./logout">Cerrar sesión</a>
          <?php endif; ?>
        </div>
      </nav>
      <article>
        <div class="content"></div>
      </article>
      <footer>
        <div class="footer">
          Aprende Lógica © 2012 - 2014. Un blog de Gilda Gómez.
          <div>
            <a class="link1" href="./">Aprende Lógica.</a>
          </div>
        </div>
      </footer>
    </body>
  </html>