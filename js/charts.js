var settings = Chart.defaults.global;
settings.animation = false;

var radarSettings = Chart.defaults.Radar;
radarSettings.scaleBeginAtZero = false;
radarSettings.pointLabelFontSize = 0;

var acceptCanvas = $("#accept-chart").get(0).getContext("2d");
var acceptChart = new Chart(acceptCanvas).Pie(acceptData);

var genderCanvas = $("#gender-chart").get(0).getContext("2d");
var genderChart = new Chart(genderCanvas).Pie(genderData);

var satCanvas = $("#sat-chart").get(0).getContext("2d");
var satChart = new Chart(satCanvas).Radar(satData);