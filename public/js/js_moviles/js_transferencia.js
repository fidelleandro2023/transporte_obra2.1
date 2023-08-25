/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$('#subida').submit(function (e) {
    e.preventDefault();

    var comprobar = $('#csv').val().length;

    if (comprobar > 0) {

        var file = $('#csv').val()
        console.log($('#csv').val().length);
        var ext = file.substring(file.lastIndexOf("."));
        console.log(ext);
        if (ext != ".txt")
        {
            alert('Formato de archivo no v\u00E1lido. El formato correcto es .TXT');
            return false;
        } else
        {
            var formulario = $('#subida');
            var archivos = new FormData();
            var url = 'upsf1v2Moviles';
            for (var i = 0; i < (formulario.find('input[type=file]').length); i++) {
                archivos.append((formulario.find('input[type="file"]:eq(' + i + ')').attr("name")), ((formulario.find('input[type="file"]:eq(' + i + ')')[0]).files[0]));
            }
            $('.easy-pie-chart').data('easyPieChart').update('5');
            $('#contSubida').hide();
            $('#valuePie').html(5);
            $.ajax({
                url: url,
                type: 'POST',
                contentType: false,
                data: archivos,
                processData: false,
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.error == 1) {
                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color:#968c07;">' + data.msj + '</label>');
                        $('#contResult').show();
                        return false;
                    } else if (data.error == 0) {
                        $('.easy-pie-chart').data('easyPieChart').update('20');
                        $('#valuePie').html(20);

                        $.ajax({
                            type: 'POST',
                            url: 'upsf2v2Moviles'
                        }).done(function (data) {
                            data = JSON.parse(data);
                            if (data.error == 1) {
                                $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                $('#contResult').show();
                                return false;
                            } else if (data.error == 0) {
                                $('.easy-pie-chart').data('easyPieChart').update('60');
                                $('#valuePie').html(60);

//                                $.ajax({
//                                    type: 'POST',
//                                    url: 'upPFV2Moviles'
//                                }).done(function (data) {
//                                    data = JSON.parse(data);
//                                    if (data.error == 1) {
//                                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
//                                        $('#contResult').show();
//                                        return false;
//                                    } else if (data.error == 0) {
                                        $('.easy-pie-chart').data('easyPieChart').update('100');
                                        $('#valuePie').html(100);
                                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #0d7d3f;">Se importaron los datos con �xito!</label>');
                                        mostrarNotificacion('success', 'Exito', 'Se actualizo correctamente la Informacion!');
//
//                                        $.ajax({
//                                            type: 'POST',
//                                            url: 'procesarSisego'
//                                        });
//                                    }
//                                });
                            }
                        });
                    }
                }
            })
            return false;
        }
    } else {
        mostrarNotificacion('warning', 'Alerta', 'Selecciona un archivo txt para importar');
        return false;

    }
});