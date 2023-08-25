function subirArchivo() {
    $('.easy-pie-tab2').data('easyPieChart').update('0');
    $('#valuePieTab2').html(0);
}

$('#import_form_cto').on('submit', function(event){
    $('.easy-pie-tab2').data('easyPieChart').update('40');
    $('#valuePieTab2').html(40);

    var input = document.getElementById('fileExcelCto');
    var form = new FormData();

    form.append('file', input.files[0]);

    event.preventDefault();
    
    $.ajax({
        url         : 'insertCtoCotizacion',
        method      : 'POST',
        data        : form,
        contentType : false,
        cache       : false,
        processData : false
    }).done(function(data){
        $('.easy-pie-tab2').data('easyPieChart').update('70');
        $('#valuePieTab2').html(70);
        data = JSON.parse(data);

        if(data.error == 0) {
            console.log("ENTRO");
            $('#valuePieTab2').html(100);
            $('.easy-pie-tab2').data('easyPieChart').update('100');
            // $('#contTablaCto').html(data.tablaCto);
            // $('#contTablaCto').css('display', 'block');
            mostrarNotificacion('succes', data.msj, 'correcto');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
});

$('#import_form_reservas').on('submit', function(event){
    $('.easy-pie-tab2').data('easyPieChart').update('40');
    $('#valuePieTabR').html(40);

    var input = document.getElementById('fileExcelReservas');
    var form = new FormData();

    form.append('file', input.files[0]);

    event.preventDefault();
    
    $.ajax({
        url         : 'insertReservasCotizacion',
        method      : 'POST',
        data        : form,
        contentType : false,
        cache       : false,
        processData : false
    }).done(function(data){
        $('.easy-pie-tabR').data('easyPieChart').update('70');
        $('#valuePieTabR').html(70);
        data = JSON.parse(data);

        if(data.error == 0) {
            console.log("ENTRO");
            $('#valuePieTabR').html(100);
            $('.easy-pie-tabR').data('easyPieChart').update('100');
            // $('#contTablaCto').html(data.tablaCto);
            // $('#contTablaCto').css('display', 'block');
            mostrarNotificacion('succes', data.msj, 'correcto');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
});

$('#import_form_ebc').on('submit', function(event){console.log("ENTRO!111");
    $('.easy-pie-tab4').data('easyPieChart').update('40');
    $('#valuePieTabEbc').html(40);

    var input = document.getElementById('fileExcelEbc');
    var form = new FormData();

    form.append('file', input.files[0]);

    event.preventDefault();
    
    $.ajax({
        url         : 'insertEbcCotizacion',
        method      : 'POST',
        data        : form,
        contentType : false,
        cache       : false,
        processData : false
    }).done(function(data){
        $('.easy-pie-tab2').data('easyPieChart').update('70');
        $('#valuePieTabEbc').html(70);
        data = JSON.parse(data);

        if(data.error == 0) {
            console.log("ENTRO");
            $('#valuePieTabEbc').html(100);
            $('.easy-pie-tab4').data('easyPieChart').update('100');
            // $('#contTablaCto').html(data.tablaCto);
            // $('#contTablaCto').css('display', 'block');
            mostrarNotificacion('succes', data.msj, 'correcto');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
});


$('#import_form_cto_edif').on('submit', function(event){
    $('.easy-pie-tab3').data('easyPieChart').update('40');
    $('#valuePieTab3').html(40);

    var input = document.getElementById('fileExcelCtoEdif');
    var form = new FormData();

    form.append('file', input.files[0]);
	console.log(input.files[0]);
    event.preventDefault();
    console.log("ENTRO2");
    $.ajax({
        url         : 'insertCtoCotizacionEdif',
        method      : 'POST',
        data        : form,
        contentType : false,
        cache       : false,
        processData : false
    }).done(function(data){console.log("ENTRO3");
        $('.easy-pie-tab3').data('easyPieChart').update('70');
        $('#valuePieTab3').html(70);
        data = JSON.parse(data);

        if(data.error == 0) {
            console.log("ENTRO");
            $('#valuePieTab3').html(100);
            $('.easy-pie-tab3').data('easyPieChart').update('100');
            // $('#contTablaCto').html(data.tablaCto);
            // $('#contTablaCto').css('display', 'block');
            mostrarNotificacion('succes', data.msj, 'correcto');
        } else {
            mostrarNotificacion('error', data.msj, 'error');
        }
    });
});