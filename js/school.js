function help(dataPoint) {
  $('.bg').show();
  $('.popup').show();
  var infoCont = $('.popup .info-container');
  infoCont.scrollTop(0);
  infoCont.scrollTop($('#' + dataPoint).position().top - 25);
}

function closeHelp() {
  $('.popup').hide();
  $('.bg').hide();
}