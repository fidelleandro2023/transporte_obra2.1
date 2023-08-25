
var errorGlob = 1;

$(function () {
    $('#subida').submit(function () {
        var comprobar = $('#csv').val().length;
        if (comprobar > 0) {
            var file = $('#csv').val()
            var ext = file.substring(file.lastIndexOf("."));
            if (ext != ".txt") {
                mostrarNotificacion('error', 'Error', 'Formato de archivo no v\u00E1lido. El formato correcto es .TXT');
                return false;
            } else {
                var formulario = $('#subida');
                var archivos = new FormData();
                var url = 'up1';
                for (var i = 0; i < (formulario.find('input[type=file]').length); i++) {
                    archivos.append((formulario.find('input[type="file"]:eq(' + i + ')').attr("name")), ((formulario.find('input[type="file"]:eq(' + i + ')')[0]).files[0]));
                }
                $('.easy-pie-chart').data('easyPieChart').update('5');
                $('#valuePie').html(5);
                console.log('va entrar al ajax');
                $.ajax({
                    url: url,
                    type: 'POST',
                    contentType: false,
                    data: archivos,
                    processData: false,
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.error == 0) {
                            console.log('termino correctamente el upload1');
                            $('.easy-pie-chart').data('easyPieChart').update('20');
                            $('#valuePie').html(20);
                            errorGlob = data.error;
                            if (errorGlob == 0) {
                                $.ajax({
                                    type: 'POST',
                                    url: 'up2'
                                }).done(function (data) {
                                    data = JSON.parse(data);
                                    if (data.error == 0) {
                                        console.log('termino correctamente el upload2');
                                        $('.easy-pie-chart').data('easyPieChart').update('30');
                                        $('#valuePie').html(30);
                                        errorGlob = data.error;
                                        if (errorGlob == 0) {
                                            $.ajax({
                                                type: 'POST',
                                                url: 'up3'
                                            }).done(function (data) {
                                                data = JSON.parse(data);
                                                if (data.error == 0) {
                                                    console.log('termino correctamente el upload3');
                                                    $('.easy-pie-chart').data('easyPieChart').update('40');
                                                    $('#valuePie').html(40);
                                                    errorGlob = data.error;
                                                    if (errorGlob == 0) {
                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'up4'
                                                        }).done(function (data) {
                                                            data = JSON.parse(data);
                                                            if (data.error == 0) {
                                                                console.log('termino correctamente el upload4');
                                                                $('.easy-pie-chart').data('easyPieChart').update('50');
                                                                $('#valuePie').html(50);
                                                                errorGlob = data.error;
                                                                if (errorGlob == 0) {
                                                                    $.ajax({
                                                                        type: 'POST',
                                                                        url: 'up5'
                                                                    }).done(function (data) {
                                                                        data = JSON.parse(data);
                                                                        if (data.error == 0) {
                                                                            console.log('termino correctamente el upload5');
                                                                            $('.easy-pie-chart').data('easyPieChart').update('60');
                                                                            $('#valuePie').html(60);
                                                                            errorGlob = data.error;
                                                                            if (errorGlob == 0) {
                                                                                $.ajax({
                                                                                    type: 'POST',
                                                                                    url: 'up8'
                                                                                }).done(function (data) {
                                                                                    data = JSON.parse(data);
                                                                                    if (data.error == 0) {
                                                                                        console.log('termino correctamente el upload8');
                                                                                        $('.easy-pie-chart').data('easyPieChart').update('70');
                                                                                        $('#valuePie').html(70);
                                                                                        errorGlob = data.error;
                                                                                        if (errorGlob == 0) {
                                                                                            $.ajax({
                                                                                                type: 'POST',
                                                                                                url: 'up6'
                                                                                            }).done(function (data) {
                                                                                                data = JSON.parse(data);
                                                                                                if (data.error == 0) {
                                                                                                    console.log('termino correctamente el upload6');
                                                                                                    $('.easy-pie-chart').data('easyPieChart').update('80');
                                                                                                    $('#valuePie').html(80);
                                                                                                    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: var(--verde_telefonica)">Termino con Existo la actualizacion de datos...</label>');
                                                                                                    $('#btnIniTransfeWU').css('display', 'none');
                                                                                                    $('#btnGenerarArchivos').css('display', 'block');
                                                                                                } else {
                                                                                                    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                                                    return false;
                                                                                                }

                                                                                            });
                                                                                        } else {
                                                                                            mostrarNotificacion('error', 'Error', data.msj);
                                                                                        }
                                                                                    } else {
                                                                                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                                        return false;
                                                                                    }

                                                                                });
                                                                            } else {
                                                                                mostrarNotificacion('error', 'Error', data.msj)
                                                                            }

                                                                        } else {
                                                                            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                            return false;
                                                                        }

                                                                    });
                                                                } else {
                                                                    mostrarNotificacion('error', 'Error', data.msj)
                                                                }

                                                            } else {
                                                                $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                return false;
                                                            }

                                                        });
                                                    } else {
                                                        mostrarNotificacion('error', 'Error', data.msj);
                                                    }

                                                } else {
                                                    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                    return false;
                                                }

                                            });
                                        } else {
                                            mostrarNotificacion('error', 'Error', data.msj);
                                        }
                                    } else {
                                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                        return false;
                                    }
                                });
                            }
                        } else {
                            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                            return false;
                        }
                    }
                })

                return false;
            }
        } else {
            mostrarNotificacion('error', 'Error', 'Selecciona un archivo txt para importar!!');
            return false;
        }
    });

});





function generarArchivosCSV() {
    console.log('entro al boton para generar archivos CSV');
    $.ajax({
        type: 'POST',
        url: 'up7'
    }).done(function (data) {
        data = JSON.parse(data);
        if (data.error == 0) {
            console.log('termino correctamente el upload7');
            $('.easy-pie-chart').data('easyPieChart').update('90');
            $('#valuePie').html(90);
            errorGlob = data.error;
            if (errorGlob == 0) {
                $.ajax({
                    type: 'POST',
                    url: 'uploadPOPEXT1'
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.error == 0) {
                        console.log('termino correctamente el uploadPOPEXT1');
                        $('.easy-pie-chart').data('easyPieChart').update('91');
                        $('#valuePie').html(91);
                        errorGlob = data.error;
                        if (errorGlob == 0) {
                            $.ajax({
                                type: 'POST',
                                url: 'uploadPOPEXT2'
                            }).done(function (data) {
                                data = JSON.parse(data);
                                if (data.error == 0) {
                                    console.log('termino correctamente el uploadPOPEXT2');
                                    $('.easy-pie-chart').data('easyPieChart').update('91');
                                    $('#valuePie').html(91);
                                    errorGlob = data.error;
                                    if (errorGlob == 0) {
                                        $.ajax({
                                            type: 'POST',
                                            url: 'uploadPOPEXT3'
                                        }).done(function (data) {
                                            data = JSON.parse(data);
                                            if (data.error == 0) {
                                                console.log('termino correctamente el uploadPOPEXT3');
                                                $('.easy-pie-chart').data('easyPieChart').update('92');
                                                $('#valuePie').html(92);
                                                errorGlob = data.error;
                                                if (errorGlob == 0) {
                                                    $.ajax({
                                                        type: 'POST',
                                                        url: 'uploadPOPEXT4'
                                                    }).done(function (data) {
                                                        data = JSON.parse(data);
                                                        if (data.error == 0) {
                                                            console.log('termino correctamente el uploadPOPEXT4');
                                                            $('.easy-pie-chart').data('easyPieChart').update('93');
                                                            $('#valuePie').html(93);
                                                            errorGlob = data.error;
                                                            if (errorGlob == 0) {
                                                                $.ajax({
                                                                    type: 'POST',
                                                                    url: 'uploadPOPEXT5'
                                                                }).done(function (data) {
                                                                    data = JSON.parse(data);
                                                                    if (data.error == 0) {
                                                                        console.log('termino correctamente el uploadPOPEXT5');
                                                                        $('.easy-pie-chart').data('easyPieChart').update('93');
                                                                        $('#valuePie').html(93);
                                                                        errorGlob = data.error;
                                                                        if (errorGlob == 0) {
                                                                            $.ajax({
                                                                                type: 'POST',
                                                                                url: 'uploadPOPEXT6'
                                                                            }).done(function (data) {
                                                                                data = JSON.parse(data);
                                                                                if (data.error == 0) {
                                                                                    console.log('termino correctamente el uploadPOPEXT6');
                                                                                    $('.easy-pie-chart').data('easyPieChart').update('94');
                                                                                    $('#valuePie').html(94);
                                                                                    errorGlob = data.error;
                                                                                    if (errorGlob == 0) {
                                                                                        $.ajax({
                                                                                            type: 'POST',
                                                                                            url: 'uploadPOPEXT7'
                                                                                        }).done(function (data) {
                                                                                            data = JSON.parse(data);
                                                                                            if (data.error == 0) {
                                                                                                console.log('termino correctamente el uploadPOPEXT7');
                                                                                                $('.easy-pie-chart').data('easyPieChart').update('94');
                                                                                                $('#valuePie').html(94);
                                                                                                errorGlob = data.error;
                                                                                                if (errorGlob == 0) {
                                                                                                    $.ajax({
                                                                                                        type: 'POST',
                                                                                                        url: 'uploadPOPEXT8'
                                                                                                    }).done(function (data) {
                                                                                                        data = JSON.parse(data);
                                                                                                        if (data.error == 0) {
                                                                                                            console.log('termino correctamente el uploadPOPEXT8');
                                                                                                            $('.easy-pie-chart').data('easyPieChart').update('95');
                                                                                                            $('#valuePie').html(95);
                                                                                                            errorGlob = data.error;
                                                                                                            if (errorGlob == 0) {
                                                                                                                $.ajax({
                                                                                                                    type: 'POST',
                                                                                                                    url: 'uploadPOPEXT9'
                                                                                                                }).done(function (data) {
                                                                                                                    data = JSON.parse(data);
                                                                                                                    if (data.error == 0) {
                                                                                                                        console.log('termino correctamente el uploadPOPEXT9');
                                                                                                                        $('.easy-pie-chart').data('easyPieChart').update('96');
                                                                                                                        $('#valuePie').html(96);
                                                                                                                        errorGlob = data.error;
                                                                                                                        if (errorGlob == 0) {
                                                                                                                            $.ajax({
                                                                                                                                type: 'POST',
                                                                                                                                url: 'uploadPOPEXT10'
                                                                                                                            }).done(function (data) {
                                                                                                                                data = JSON.parse(data);
                                                                                                                                if (data.error == 0) {
                                                                                                                                    console.log('termino correctamente el uploadPOPEXT10');
                                                                                                                                    $('.easy-pie-chart').data('easyPieChart').update('97');
                                                                                                                                    $('#valuePie').html(97);
                                                                                                                                    errorGlob = data.error;
                                                                                                                                    if (errorGlob == 0) {
                                                                                                                                        $.ajax({
                                                                                                                                            type: 'POST',
                                                                                                                                            url: 'uploadPOPEXT11'
                                                                                                                                        }).done(function (data) {
                                                                                                                                            data = JSON.parse(data);
                                                                                                                                            if (data.error == 0) {
                                                                                                                                                console.log('termino correctamente el uploadPOPEXT11');
                                                                                                                                                $('.easy-pie-chart').data('easyPieChart').update('98');
                                                                                                                                                $('#valuePie').html(98);
                                                                                                                                                errorGlob = data.error;
                                                                                                                                                if (errorGlob == 0) {
                                                                                                                                                    $.ajax({
                                                                                                                                                        type: 'POST',
                                                                                                                                                        url: 'uploadPOPINT'
                                                                                                                                                    }).done(function (data) {
                                                                                                                                                        data = JSON.parse(data);
                                                                                                                                                        if (data.error == 0) {
                                                                                                                                                            console.log('termino correctamente el uploadPOPINT');
                                                                                                                                                            $('.easy-pie-chart').data('easyPieChart').update('100');
                                                                                                                                                            $('#valuePie').html(100);
                                                                                                                                                            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: var(--verde_telefonica)">Termino con Existo la generacion de los archivos...</label>');
                                                                                                                                                            errorGlob = data.error;
                                                                                                                                                            if (errorGlob == 0) {
                                                                                                                                                                swal({
                                                                                                                                                                    title: 'Se realizo correctamente la transferencia de WU!!',
                                                                                                                                                                    text: 'Operacion existora!',
                                                                                                                                                                    type: 'success',
                                                                                                                                                                    buttonsStyling: false,
                                                                                                                                                                    confirmButtonClass: 'btn btn-primary',
                                                                                                                                                                    confirmButtonText: 'OK!'

                                                                                                                                                                }).then(function () {
                                                                                                                                                                    location.reload();
                                                                                                                                                                });
                                                                                                                                                            } else {
                                                                                                                                                                mostrarNotificacion('error', 'Error', data.msj);
                                                                                                                                                            }

                                                                                                                                                        } else {
                                                                                                                                                            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                                                                                                            return;
                                                                                                                                                        }

                                                                                                                                                    });

                                                                                                                                                } else {
                                                                                                                                                    mostrarNotificacion('error', 'Error', data.msj);
                                                                                                                                                }

                                                                                                                                            } else {
                                                                                                                                                $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                                                                                                return;
                                                                                                                                            }

                                                                                                                                        });
                                                                                                                                    } else {
                                                                                                                                        mostrarNotificacion('error', 'Error', data.msj);
                                                                                                                                    }

                                                                                                                                } else {
                                                                                                                                    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                                                                                    return;
                                                                                                                                }

                                                                                                                            });
                                                                                                                        } else {
                                                                                                                            mostrarNotificacion('error', 'Error', data.msj);
                                                                                                                        }

                                                                                                                    } else {
                                                                                                                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                                                                        return;
                                                                                                                    }

                                                                                                                });
                                                                                                            } else {
                                                                                                                mostrarNotificacion('error', 'Error', data.msj);
                                                                                                            }

                                                                                                        } else {
                                                                                                            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                                                            return;
                                                                                                        }

                                                                                                    });
                                                                                                } else {
                                                                                                    mostrarNotificacion('error', 'Error', data.msj);
                                                                                                }

                                                                                            } else {
                                                                                                $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                                                return;
                                                                                            }

                                                                                        });
                                                                                    } else {
                                                                                        mostrarNotificacion('error', 'Error', data.msj);
                                                                                    }

                                                                                } else {
                                                                                    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                                    return;
                                                                                }

                                                                            });
                                                                        } else {
                                                                            mostrarNotificacion('error', 'Error', data.msj);
                                                                        }

                                                                    } else {
                                                                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                                        return;
                                                                    }

                                                                });
                                                            } else {
                                                                mostrarNotificacion('error', 'Error', data.msj);
                                                            }

                                                        } else {
                                                            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                            return;
                                                        }

                                                    });
                                                } else {
                                                    mostrarNotificacion('error', 'Error', data.msj);
                                                }

                                            } else {
                                                $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                                return;
                                            }

                                        });
                                    } else {
                                        mostrarNotificacion('error', 'Error', data.msj);
                                    }

                                } else {
                                    $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                                    return;
                                }

                            });
                        } else {
                            mostrarNotificacion('error', 'Error', data.msj);
                        }

                    } else {
                        $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
                        return;
                    }

                });
            } else {
                mostrarNotificacion('error', 'Error', data.msj);
            }

        } else {
            $('#respuesta').html('<label style="font-size: larger; padding-top: 20px;color: #968c07;">' + data.msj + '</label>');
            return;
        }

    });
}