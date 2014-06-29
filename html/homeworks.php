<?php
  
  require_once '../php/config.php';
  require_once '../system/class.Session.php';
  
  $session = Session :: get();
  
?>
<?php if ($session == null || !$session->admin) : ?>
<script type="text/javascript">
  loadPage('./404', function () {
    history.pushState({ url : './404' }, '', './404');
  });
</script>
<?php exit(); endif; ?>
<?php
  
  require_once '../php/connection.php';
  
?>
<?php if (!$mysqli) : ?>
<script type="text/javascript">
  loadPage('./', function () {
    history.pushState({ url : './' }, '', './');
  });
</script>
<?php exit(); endif; ?>
<?php
  
  require_once '../system/class.Homework.php';
  
  Homework :: $mysqli = $mysqli;
  $pages = Homework :: getPages(5);
  $correctedPages = $pages['corrected_pages'];
  $uncorrectedPages = $pages['uncorrected_pages'];
  
?>
<input id="homeworksParams" data-correctedPages="<?= $correctedPages ?>" data-uncorrectedPages="<?= $uncorrectedPages ?>" type="hidden" />
<div class="homeworkAssignment">
  <div class="a">
    Actualizar asignación:
  </div>
  <form action="./json/assignment_publish.php" method="post" enctype="multipart/form-data">
    <div class="b">
      <input name="assignment" class="input2" type="file" />
    </div>
    <div class="c">
      <button id="publishAssignmentButton" class="button1">OK</button>
    </div>
  </form>
</div>
<div id="assignmentMessage" class="assignmentSuccessMessage"></div>
<div class="paragraph homeworkTitle">
  Tareas no corregidas:
</div>
<div id="uncorrectedHomeworks" class="paragraph homeworksTray"></div>
<div class="homeworksButtons">
  <div class="highlight left">
    <span id="pageA">1</span>/<?= $uncorrectedPages ?>
  </div>
  <div class="right">
    <button id="backButtonA" class="button1" title="Pasar a la página anterior.">Anterior</button>
    <button id="forwardButtonA" class="button1" title="Pasar a la siguiente página.">Siguiente</button>
  </div>
</div>
<div class="paragraph homeworkTitle">
  Tareas corregidas:
</div>
<div id="correctedHomeworks" class="paragraph homeworksTray"></div>
<div class="homeworksButtons">
  <div class="highlight left">
    <span id="pageB">1</span>/<?= $correctedPages ?>
  </div>
  <div class="right">
    <button id="backButtonB" class="button1" title="Pasar a la página anterior.">Anterior</button>
    <button id="forwardButtonB" class="button1" title="Pasar a la siguiente página.">Siguiente</button>
  </div>
</div>
<script type="text/javascript">
  $('#publishAssignmentButton').click(function (evt) {
    evt.preventDefault();
    $(this).html('Publicando...');
    $('form').ajaxSubmit(function (data) {
      try {
        var response = JSON.parse(data);
        var errno = response.errno;
        var message = response.message;
      } catch (ex1) {
        var errno = 1;
        var message = 'No se pudo procesar la subida.';
        
        console.error(data);
      }
      
      $('#publishAssignmentButton').html('OK');
      $('#assignmentMessage').hide();
      $('#assignmentMessage').html(message);
      
      if (errno == 1) {
        $('#assignmentMessage').attr('class', 'assignmentErrorMessage');
      } else {
        $('#assignmentMessage').attr('class', 'assignmentSuccessMessage');
        $('input').val(null);
      }
      
      $('#assignmentMessage').fadeIn('slow');
    });
  });
  
  (function ($) {
    var uncorrectedPages = +$('#homeworksParams').attr('data-uncorrectedPages');
    var correctedPages = +$('#homeworksParams').attr('data-correctedPages');
    var pageA = 1;
    var pageB = 1;
    
    function loadHomeworksPage (page, corrected) {
      if (corrected) {
        var jsonFile = './json/homework_get_corrected.php';
        var tray = '#correctedHomeworks';
        var noHomeworksMessage = 'No se han corregido tareas.';
        var css = 'corrected';
        var title = 'Marcar como no corregida.';
      } else {
        var jsonFile = './json/homework_get_uncorrected.php';
        var tray = '#uncorrectedHomeworks';
        var noHomeworksMessage = 'No hay tareas entregadas por corregir.';
        var css = 'uncorrected';
        var title = 'Marcar como corregida.';
      }
      
      $(tray).html('<img src="./img/loading.gif" alt />');
      $.post(jsonFile, {
        page : page
      }, function (data) {
        try {
          var homeworks = JSON.parse(data);
          var total = homeworks.length;
          
          if (total == 0) {
            $(tray).html(noHomeworksMessage);
            
            return;
          }
          
          var html = '';
          var homework;
          
          for (var i = 0; i < total; i++) {
            homework = homeworks[ i ];
            html += '<div class="homework"><div class="a"><button name="correctButton" class="' + css + '" title="' + title + '" data-id="' + homework.id + '"></button></div><div class="b">C.I <span class="highlight">' + homework.cedula + '</span></div><div class="c">De: <span class="highlight">' + homework.name + ' ' + homework.lastName + '</span>.</div><div class="d">' + homework.sendDate + '</div><div class="e"><button name="downloadButton" class="button1" title="Descargar tarea #' + homework.id + '" data-id="' + homework.id +  '">Descargar</button></div></div>';
          }
          
          $(tray).html(html);
          $('[name="correctButton"]').unbind('click');
          $('[name="correctButton"]').click(function (evt) {
            if ($(this).attr('class') == 'corrected') {
              var jsonFile = './json/homework_uncorrect.php';
            } else {
              var jsonFile = './json/homework_correct.php';
            }
            
            $.post(jsonFile, {
              id : $(this).attr('data-id')
            }, function (data) {
              try {
                var response = JSON.parse(data);
                
                if (response.errno == 1) {
                  alert(response.message);
                } else {
                  loadHomeworksPage(1, 0);
                  loadHomeworksPage(1, 1);
                }
              } catch (ex1) {
                console.error(data);
                alert('No se pudo actualizar el estado de la tarea.');
              }
            });
          });
          
          $('[name="downloadButton"]').unbind('click');
          $('[name="downloadButton"]').click(function (evt) {
            location = './download-homework.php?id=' + $(this).attr('data-id');
          });
        } catch (ex1) {
          console.error(data);
          $(tray).html('No se pudieron procesar las tareas.');
        }
      });
    }
    
    $('#backButtonA').click(function (evt) {
      if (pageA == 1) {
        pageA = uncorrectedPages;
      } else {
        pageA--;
      }
      
      $('#pageA').html(pageA);
      loadHomeworksPage(pageA, 0);
    });
    
    $('#forwardButtonA').click(function (evt) {
      if (pageA == uncorrectedPages) {
        pageA = 1;
      } else {
        pageA++;
      }
      
      $('#pageA').html(pageA);
      loadHomeworksPage(pageA, 0);
    });
    
    $('#backButtonB').click(function (evt) {
      if (pageB == 1) {
        pageB = correctedPages;
      } else {
        pageB--;
      }
      
      $('#pageB').html(pageB);
      loadHomeworksPage(pageB, 1);
    });
    
    $('#forwardButtonB').click(function (evt) {
      if (pageB == correctedPages) {
        pageB = 1;
      } else {
        pageB++;
      }
      
      $('#pageB').html(pageB);
      loadHomeworksPage(pageB, 1);
    });
    
    loadHomeworksPage(1, 0);
    loadHomeworksPage(1, 1);
  })(jQuery);
</script>