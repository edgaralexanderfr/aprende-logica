/**
 * Copyright 2014 - Edgar Alexander Franco
 *
 * @author Edgar Alexander Franco
 * @version 1.0.0
 */

$.ajaxSetup({
  contentType: 'application/x-www-form-urlencoded; charset=ISO-8859-1', 
  beforeSend: function(jqXHR) {
    jqXHR.overrideMimeType('application/x-www-form-urlencoded; charset=ISO-8859-1');
  }
});

/**
 * Loads a new page into the .content div if the requested page is registered.
 *
 * @param {string} url URL of the file to request.
 * @param {function} callback (Optional) Function to perform when the page is loaded.
 */
function loadPage (url, callback) {
  var page = pages[ url ];
  
  if (typeof page == 'undefined') {
    return false;
  }
  
  onLeave();
  onLeave = function () {
    
  };
  
  $('.loadingContent').fadeIn('fast');
  $('html, body').animate({ scrollTop: 0 }, 'medium');
  $.get(page.path, function (data) {
    $(document).prop('title', page.title);
    $('.content').html(data);
    $('.loadingContent').fadeOut('fast');
    
    if (typeof callback == 'function') {
      callback();
    }
  });
  
  return true;
}

/**
 * Function to set the links behaviour of the page.
 */
function setLinks () {
  $('a').unbind('click');
  $('a').click(function (evt) {
    var url = $(this).attr('href');
    var success = loadPage(url, function () {
      evt.preventDefault();
      history.pushState({ url : url }, '', url);
    });
    
    if (success) {
      evt.preventDefault();
    }
  });
}

/**
 * Function to override.
 */
function onLeave () {
  
}

$(document).ready(function () {
  setLinks();
  $(window).on('popstate', function (evt) {
    if (evt.originalEvent.state) {
      loadPage(evt.originalEvent.state.url);
    }
  });
  
  var page = $('#params').attr('data-page');
  
  if (!loadPage(page, function () {
    history.pushState({ url : page }, '', page);
  })) {
    loadPage('./404', function () {
      history.pushState({ url : './404' }, '', './404');
    })
  }
});