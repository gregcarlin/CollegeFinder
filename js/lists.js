function addToList(school, list) {
  alert("An unknown error has occurred. Please reload the page and try again.");
}

function removeFromList(school, list) {
  $.get('list-util.php?action=1&school=' + school + '&list=' + list, '', function(data, textStatus, jqXHR) {
    if(jqXHR.responseText == 0) {
      $('#row-' + school).remove();
      if($('#results-' + list + ' tr').length <= 1) {
        $('#results-' + list).html('You do not have any schools in this list! Add some by <a href="search.php">searching</a> for them.');
      }
    } else {
      $('#row-' + school + ' .save span').html('Error');
    }
  });
  $('#row-' + school + ' .save span').html('...');
  $('#row-' + school + ' .save .list-popup').html('');
}