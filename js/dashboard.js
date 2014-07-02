function updateLocation() {
  var val = $('input[name="loc-type"]:checked').val();
  var allVals = ["none", "setting", "distance", "state"];
  for(var i=0; i<allVals.length; i++) {
    if(allVals[i] == val) {
      $('#' + allVals[i]).show();
    } else {
      $('#' + allVals[i]).hide();
    }
  }
}