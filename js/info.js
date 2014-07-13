function showGPA() {
  $('.bg').show();
  $('.gpa-popup').show();
}

function hideGPA() {
  $('.gpa-popup').hide();
  $('.bg').hide();
}

function addGPARow() {
    var grades = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'E/F'];
    var values = [ 4.0, 4.0,  3.7,  3.3, 3.0,  2.7,  2.3, 2.0,  1.7,  1.3, 1.0,   0.0];

    var row = '<tr class="gpa-row">' +
              '<td width="180px"><input type="text" placeholder="Geometry" class="form-control"></td>' +
              '<td width="96px"><select class="form-control"><option value="0">Regular</option><option value="0.5">Honors</option><option value="1">AP/IB</option></select></td>' +
              '<td width="92px"><input type="text" placeholder="1" class="form-control sems"></td>' +
              '<td width="65px"><select class="form-control grade">' +
              '<option value="NaN">\u2205</option>';
    for(index in grades) {
        row += '<option value="' + values[index] + '">' + grades[index] + '</option>';
    }
    row += '</select></td></tr>';

    var classCont = $('.gpa-class-container');
    classCont.append(row);
    classCont.scrollTop(2000000000);

    var outer = $('.gpa-popup');
    outer.css("margin-top", (-parseInt(outer.css("height")) / 2) + 'px');
}

window.onload = addGPARow;

function calcGPA() {
    hideGPA();

    var rows        = $('.gpa-row');
    var sumWeight   = 0.0;
    var sumNoWeight = 0.0;
    var n           = 0.0;

    rows.each(function(i, element) {
        var cols   = rows.eq(i).children();
        var sems   = parseFloat(cols.eq(2).children('input').first().val());
        var grade  = parseFloat(cols.eq(3).children('select').first().val());
        var weight = parseFloat(cols.eq(1).children('select').first().val());
        if(!isNaN(sems) && !isNaN(grade)) {
            sumNoWeight += grade * sems;
            sumWeight += (grade + weight) * sems;
            n += sems;
        }
    });

    if(n != 0) {
      $('#gpaNoWeight').val(sumNoWeight / n);
      $('#gpaWeight').val(sumWeight / n);
    }
}