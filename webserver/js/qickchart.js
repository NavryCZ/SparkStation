

google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawLineColors);
function drawLineColors() {
var data = new google.visualization.DataTable();
data.addColumn('string', 'time');
data.addColumn('number', 'Teplota');
data.addColumn('number', 'Vlhkost');
//data.addColumn('number', 'Tlak');
var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
var options = { 
    title: 'Rychlý přehled za poslední 3 hodiny',
    hAxis: {
        title: 'Čas' 
    },
    vAxis: {
        title: 'Rychlý přehled'
    },
    colors: ['#a52714', '#0000e6', '#993300'], 
    legend: { position: 'bottom' },
    backgroundColor: '#eaf1ff'
};
drawChart();
//data.addRow(quick);
function drawChart() {
$.ajax({
    url: '../cloud/jsondata.php',
    dataType: "json"
}).done(function (jsonData) {
    //alert(jsonData.length);
    for (var i = 0; i < jsonData.length; i++) {        
        //add a row for each piece of data
        data.addRows([
            [jsonData[i].time, jsonData[i].temp, jsonData[i].hum]
        ]);
    }
    chart.draw(data, options);
}).fail(function (jqXHR, textStatus, errorThrown) {
    console.log(textStatus, errorThrown);
});
}
}  


