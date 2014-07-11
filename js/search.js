function update(name, allVals) {
  var val = $('input[name="' + name + '"]:checked').val();
  for(var i=0; i<allVals.length; i++) {
    if(allVals[i] == val) {
      $('#' + name + "-" + allVals[i]).show();
    } else {
      $('#' + name + "-" + allVals[i]).hide();
    }
  }
}

function updateLocation() {
  update("loc-type", ["none", "setting", "distance", "state"]);
}

function updateLevel() {
  update("level-type", ["none", "some"]);
}

function updateControl() {
  update("control-type", ["none", "some"]);
}

function updateDegrees() {
  update("degrees-type", ["none", "some"]);
}

function updateMajors() {
  update("majors-type", ["none", "some"]);
}

window.onload = function() {
  updateLocation();
  updateLevel();
  updateControl();
  updateDegrees();
  updateMajors();
};

function toggleSec(id) {
    $(".major-sec-" + id + " ul").toggle();
    var indicator = $(".indicator-" + id);
    var status = indicator.html();
    if(status == "[+]") {
        indicator.html("[-]");
    } else {
        indicator.html("[+]");
    }
}