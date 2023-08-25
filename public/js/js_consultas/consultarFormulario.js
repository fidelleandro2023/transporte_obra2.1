new Vue({
    el: '#consult',
    data: () => {
        return {
            arrayFormulario : [],
            arrayDetalle    : [],
            idTipo_obra     : null,
            jsonDetalle     : any={},
            cantNodos       : 0,
            arrayComboCodigo : [],
            arrayCodigoSelec : [],
            arrayMaterial   : [],
            arrayDetalleMaterial : [],
            arrayCmbTipoFicha : [],
            idFichaTecnica : null,
            arrayEditarMaterial : any = { itemplan  : null,
                                          cuadrilla : null },
            jsonMaterial : []
        }
    },
    methods: {
        getTablaConsultaFormulario:function(){
            var vue = this; 
            $.ajax({
                type : 'POST',
                url  : 'getConsultaFormulario'
            }).done(function(data){
                data = JSON.parse(data);
                // console.log(data.arrayData)
                vue.arrayFormulario = data.arrayData;
                vue.arrayMaterial   = data.arrayMaterial;
            });
        },
        openDetalleForm:function(itemplan, idTipo_obra) {
            var vue = this; 
            $.ajax({
                type : 'POST',
                url  : 'getDetalleFormulario',
                data : { itemplan    : itemplan, 
                         idTipo_obra : idTipo_obra}
            }).done(function(data){
                data = JSON.parse(data);
                vue.idTipo_obra  = idTipo_obra
                vue.arrayDetalle = data.arrayDataDetalle;
                modal('modalDetalle');
            });  
        },
        openModalEditar:function(json) {
            var vue = this;
            $.ajax({
                type : 'POST',
                url  : 'getArrayComboCodigo',
                data : { itemplan : this.jsonDetalle.itemplan }
            }).done(function(data) {
                data = JSON.parse(data);
                vue.arrayComboCodigo = data.cmbCodigo;
                vue.jsonDetalle = json;
                console.log(vue.jsonDetalle);
                if(vue.jsonDetalle.idTipo_obra==2) {
                    var arrayNodos = vue.jsonDetalle.cod_nodos.split(',');
                    if(vue.cantNodos != null) {
                        vue.cantNodos = arrayNodos.length;
                    } else {
                        vue.cantNodos = 0;
                    }
                }
                modal('modalEditar');
            });  
        },
        getCodigoObraArray:function(id) {
            var valor = $('#cmb_'+id).val();
            var valor_anterior = null;
            var vue=this;
            var flg = null;
            if(this.arrayCodigoSelec.length != 0) {
                this.arrayCodigoSelec.forEach(function(element){
                    if(valor != element['value']) {
                        if(element['key'] == id) {
                            vue.contador++;
                            element['value'] = valor;
                            flg = null;
                        } else {
                            console.log("ENTRO2 "+vue.contador);
                            flg = (vue.contador == 0) ? 1 : null; 
                        } 
                    } else {
                        mostrarNotificacion('error','El nombre ya fue seleccionada');
                        return;
                    }
                });
                if(flg == 1) {
                    vue.arrayCodigoSelec.push({key   : id,
                                               value : valor});
                    flg = null;
                } 
            } else {
                vue.contador = 0;
                this.arrayCodigoSelec.push({key   : id,
                                            value : valor});
            }  
        },
        actualizarDetalle:function() {
            var vue = this;
            $.ajax({
                type : 'POST',
                url  : 'actualizarDetalleForm',
                data :  { 
                            'json'             : vue.jsonDetalle,
                            'arrayCodigoSelec' : JSON.stringify(vue.arrayCodigoSelec)
                        }
            }).done(function(data){
                try {
                    data = JSON.parse(data);
                    if(data.error == 0) {
                        modal('modalEditar');
                        mostrarNotificacion('success', 'Se actualiz&oacute; correctamente');
                    } else {
                        mostrarNotificacion('error', data.msj);
                    }
                } catch(err) {
                    mostrarNotificacion('error',err.message);
                }
            });
        },
        seleccionarItemPlan:function(itemplan) {
            var vue = this;
            $.ajax({
                type : 'POST',
                data : { itemplan : itemplan },
                url  : 'getDataMaterialRadioButton',
            }).done(function(data){
                data = JSON.parse(data);
                console.log(vue.arrayMaterial);
                vue.arrayMaterial = [];
                vue.arrayMaterial   = data.arrayMaterial;
            });
        },
        openModalEditarMaterial:function(itemplan, idFichaTecnica) {
            var vue = this;
            $.ajax({
                type : 'POST',
                data : { itemplan       : itemplan,
                         idFichaTecnica : idFichaTecnica},
                url  : 'getDataMaterialRadioButton',
            }).done(function(data){
                data = JSON.parse(data);
                vue.arrayEditarMaterial = data.arrayMaterial;
                
                vue.arrayEditarMaterial.cuadrilla = data.arrayMaterial[0].jefe_c_nombre;
                vue.arrayEditarMaterial.itemplan = data.arrayMaterial[0].itemplan;
                modal('modalEditarMaterial');
            });
        },
        openmodalEditarMaterialDetalle:function(idFichaTecnica){
            var vue = this;
            $.ajax({
                type : 'POST',
                data : { idFichaTecnica : idFichaTecnica},
                url  : 'getMaterialDetalle',
            }).done(function(data){
                vue.idFichaTecnica = idFichaTecnica;
                data = JSON.parse(data);
                vue.arrayDetalleMaterial = data.arrayDetalleMaterial;
                vue.arrayCmbTipoFicha = data.arrayTipoFicha;
                modal('modalEditarMaterialDetalle');
            });
        },
        actualizarMaterialDetalle :function() {
            var array = [];
            var vue = this;
            this.arrayDetalleMaterial.forEach(function(data){
                var cantidadTrabajo   = $('#inputCantidadTrabajo'+data.id_ficha_tecnica_trabajo).val();
                var select            = $('#selectTrabajo'+data.id_ficha_tecnica_trabajo+' option:selected').val();
                var comentarioTrabajo = $('#inputComentarioTrabajo'+data.id_ficha_tecnica_trabajo).val();
                
                if(cantidadTrabajo != '' && select != '') {
                    vue.jsonMaterial.push({ id_ficha_tecnica              : vue.idFichaTecnica,
                                            id_ficha_tecnica_trabajo      : data.id_ficha_tecnica_trabajo, 
                                            cantidad                      : cantidadTrabajo,
                                            id_ficha_tecnica_tipo_trabajo : select,
                                            observacion                   : comentarioTrabajo });
                }
            });

            $.ajax({
                type : 'POST',
                url  : 'updateFichaTecnica',
                data : {
                            arrayJsonData  : vue.jsonMaterial,
                            idFichaTecnica : vue.idFichaTecnica
                       } 
            }).done(function(data){
                data = JSON.parse(data);
                if(data.val == 1) {
                    vue.jsonMaterial = [];
                    modal('modalEditarMaterialDetalle');
                    mostrarNotificacion('success', 'se actualiz&oacute; correctamente');
                } else {
                    mostrarNotificacion('success', 'No se actualiz&oacute');
                }
            });
        }
    }, 
    mounted:function() {
        this.arrayCodigoSelec = [];
        this.getTablaConsultaFormulario();

    },
    updated:function(){
        if(!$.fn.dataTable.isDataTable('#simpletable')) {
            $("#simpletable").removeAttr('width').DataTable({
                dom: 'Bfrtip',
                buttons:[{extend:'excelHtml5'}],
                pageLength:5,
                lengthMenu:[[30,60,100,-1],[30,60,100,"Todos"]],
                language :  {
                                sProcessing:"Procesando...",
                                sLengthMenu:"Mostrar _MENU_ registros",
                                sZeroRecords :"No se encontraron resultados",
                                sEmptyTable  :"Ning\u00fan dato disponible en esta tabla",
                                sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",
                                sInfoFiltered:"(filtrado de un total de _MAX_ registros)",
                                sInfoPostFix:"",
                                sSearch:"Buscar:",
                                sUrl : "",
                                sInfoThousands  : ",",
                                sLoadingRecords : "Cargando...",
                                oPaginate: { sFirst    :"Primero",
                                            sLast     : "\u00daltimo",
                                            sNext     : "Siguiente",
                                            sPrevious : "Anterior"},
                                            oAria     : {
                                                            sSortAscending:": Activar para ordenar la columna de manera ascendente",
                                                            sSortDescending:": Activar para ordenar la columna de manera descendente"
                                                        }
                            }
            });
        }
        
        if(!$.fn.dataTable.isDataTable('#simpletable2')) {
            $("#simpletable2").removeAttr('width').DataTable({
                dom: 'Bfrtip',
                buttons:[{extend:'excelHtml5'}],
                pageLength:5,
                lengthMenu:[[30,60,100,-1],[30,60,100,"Todos"]],
                language :  {
                                sProcessing:"Procesando...",
                                sLengthMenu:"Mostrar _MENU_ registros",
                                sZeroRecords:"No se encontraron resultados",
                                sEmptyTable:"Ning\u00fan dato disponible en esta tabla",
                                sInfo:"Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                sInfoEmpty:"Mostrando registros del 0 al 0 de un total de 0 registros",
                                sInfoFiltered:"(filtrado de un total de _MAX_ registros)",
                                sInfoPostFix:"",
                                sSearch:"Buscar:",
                                sUrl : "",
                                sInfoThousands  : ",",
                                sLoadingRecords : "Cargando...",
                                oPaginate: { sFirst    :"Primero",
                                            sLast     : "\u00daltimo",
                                            sNext     : "Siguiente",
                                            sPrevious : "Anterior"},
                                            oAria     : {
                                                            sSortAscending:": Activar para ordenar la columna de manera ascendente",
                                                            sSortDescending:": Activar para ordenar la columna de manera descendente"
                                                        }
                            }
            });
        }   
    }
  })

