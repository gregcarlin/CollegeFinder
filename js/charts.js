var settings = Chart.defaults.global;
settings.animation = false;
settings.tooltipTemplate = "<%if (label){%><%=label%><%}%>";

var acceptCanvas = $("#accept-chart").get(0).getContext("2d");
var acceptChart = new Chart(acceptCanvas).Pie(acceptData);

var genderCanvas = $("#gender-chart").get(0).getContext("2d");
var genderChart = new Chart(genderCanvas).Pie(genderData);