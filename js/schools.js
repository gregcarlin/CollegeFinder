function addToList(school, list) {
  $.get('list-util.php?action=0&school=' + school + '&list=' + list, '', function(data, textStatus, jqXHR) {
    var lists = ['Reach', 'Target', 'Safety'];
    $('#row-' + school + ' .save span').html(jqXHR.responseText == 0 ? lists[list] : 'Error');
    $('#row-' + school + ' .save .list-popup').html(jqXHR.responseText == 0 ? '<a onclick="removeFromList(' + school + ',' + list + ')">Remove</a>' : '');
  });
  $('#row-' + school + ' .save span').html('...');
  $('#row-' + school + ' .save .list-popup').html('');
}

function removeFromList(school, list) {
  $.get('list-util.php?action=1&school=' + school + '&list=' + list, '', function(data, textStatus, jqXHR) {
    $('#row-' + school + ' .save span').html(jqXHR.responseText == 0 ? 'Save &raquo;' : 'Error');
    $('#row-' + school + ' .save .list-popup').html(jqXHR.responseText == 0 ? '<a onclick="addToList(' + school + ',0)">Reach</a><a onclick="addToList(' + school + ',1)">Target</a><a onclick="addToList(' + school + ',2)">Safety</a>' : '');
  });
  $('#row-' + school + ' .save span').html('...');
  $('#row-' + school + ' .save .list-popup').html('');
}