// Radialize the colors
var arrayPieGlb = [{'name' : 'aa', 'y' : 12}];

function verGrafico() {
    $.ajax({
        type : 'POST',
        url  : 'getDataGrafCotizacion'
    }).done(function(data){
        data = JSON.parse(data);
        arrayPieGlb = data.arrayGrafPie
        grafDataCoti(arrayPieGlb, 'container');
        
        modal('modal_grafico');
    });
    
}

function grafDataCoti(json, idCont) {    
    // Build the chart
    var options = {
        legend: {
            itemStyle: {
                color: '#959595'
            }
        },
        chart: {
            renderTo: idCont,
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            backgroundColor: "white",
            color:"#959595"
        },
        title: {
            text: 'PORCENTAJE DE COTIZACION'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    connectorColor: 'silver'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Share',
            data: []
        }]
    }
    options.series[0].data = json;

    chart = new Highcharts.Chart(options);

    $.each(options.series[0].data, function (key, value){
    	value.events.click = function (){ };
    });
}

function getDetalleCotiAten(btn) {
    var estado      = btn.data('estado');
    var intervalo_h = btn.data('intervalo_h');

    $.ajax({
        type : 'POST',
        url  : 'getDetalleCotiAten',
        data : { estado      : estado,
                 intervalo_h : intervalo_h }
    }).done(function(data){
        data = JSON.parse(data);
        
        if(data.error == 0) {
            $('#contTablaDetalleCotiAten').html(data.tablaDetalleCotiAten);
            initDataTable('#tbDetalleCotiAten');
            modal('modal_detalle_aten_coti');
        } else {
            mostrarNotificacion('error',data.msj);
        }
    });
}