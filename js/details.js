function toggleAdvancedSize() {
    $('.simple-size').toggle();
    $('.adv-size').toggle();

    var adv = $('#advanced-size');
    var indicator = $('#size-type');
    if(adv.html() == "Advanced") {
        // switch to advanced
        adv.html("Simple");
        indicator.val("advanced");
    } else {
        // switch to simple
        adv.html("Advanced");
        indicator.val("simple");
    }
}

function toggleAdvancedLoc() {
    $('.simple-loc').toggle();
    $('.adv-loc').toggle();

    var adv = $('#advanced-loc');
    var indicator = $('#loc-type');
    if(adv.html() == "Advanced") {
        // switch to advanced
        adv.html("Simple");
        indicator.val("advanced");
    } else {
        // switch to simple
        adv.html("Advanced");
        indicator.val("simple");
    }
}

function toggleGPA() {
    $('.gpa-popup').toggle();
}

function addGPARow() {
    var grades = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'E/F'];
    var values = [ 4.0, 4.0,  3.7,  3.3, 3.0,  2.7,  2.3, 2.0,  1.7,  1.3, 1.0,   0.0];

    var row = '<tr class="gpa-row">' +
              '<td><input type="text" placeholder="Geometry" class="form-control"></td>' +
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
    toggleGPA();

    var rows = $('.gpa-row');
    var sum  = 0.0;
    var n    = 0.0;

    rows.each(function(i, element) {
        var cols  = rows.eq(i).children();
        var sems  = parseFloat(cols.eq(1).children('input').first().val());
        var grade = parseFloat(cols.eq(2).children('select').first().val());
        if(!isNaN(sems) && !isNaN(grade)) {
            sum += grade * sems;
            n += sems;
        }
    });

    if(n != 0) $('#gpa').val(sum / n);
}

$("#results").click(function() {
    var majors = "";
    $(".major-right li").each(function(i,j) {
        majors += $(this).attr("class").substring(6) + ",";
    });
    $("#majors").val(majors);
    $("#form").submit();
});

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

function addMajor(id, name) {
    if(!($(".major-right .major-" + id).length)) {
        $(".major-right ul").append('<li class="major-' + id + '">' + name + '<a class="remove" onclick="removeMajor(' + id + ')">Remove</a></li>');
    }
}

function addAllMajors(secID) {
    $(".major-sec-" + secID + " li").each(function(i,j) {
        var html = $(this).html();
        addMajor($(this).attr("class").substring(6), html.substring(0, html.indexOf('<a class="add"')));
    });
}

function removeMajor(id) {
    $(".major-right .major-" + id).remove();
}