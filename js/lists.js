$(".sortable").sortable({
  update: function(event, ui) {
    var list = ui.item.parent().parent().attr("id").substring(8);
    var rows = $("#results-" + list + " .sortable tr");
    var gaveError = false;
    for(var i = 0; i < rows.length; i++) {
      var row = rows.get(i);
      var school = row.id.substring(4);
      $.get('list-util.php?action=2&school=' + school + '&list=' + list + '&data=' + i, '', function(data, textStatus, jqXHR) {
        if(jqXHR.responseText == 0) {
          // do nothing
        } else {
          if(!gaveError) {
            gaveError = true;
            alert("Error ranking schools.");
          }
        }
      });
    }
  }
});

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
}