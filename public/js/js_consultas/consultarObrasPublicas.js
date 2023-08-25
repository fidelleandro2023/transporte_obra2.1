new Vue({
    el: '#obrasConObp',
    data: () => {
        return {
            arrayFormulario : [],
            jsonFormObrasP : {
                id               : null,
                itemplan         : null, 
                ptr              : null, 
                canalizacion_km  : null, 
                camaras_und      : null, 
                c_postes         : null, 
                ma_postes        : null,
                km_ducto         : null,
                km_tritubo       : null,
                km_par_cobre     : null,
                km_cable_coax    : null,
                km_fo            : null,
                observacion      : null,
                fecha_form       : null,
                usuario_registro : null,
                fecha_registro   : null 
            }
        }
    },
    methods: {
        getTablaArrayObp:function() {
            var vue = this;
            $.ajax({
                type : 'POST',
                url  : 'getDataObp'
            }).done(function(data){
                data = JSON.parse(data);
                vue.arrayFormulario = data.arrayDataObrasPub;
                console.log(vue.arrayFormulario);
            });
        }, 
        openModalUpdate: function(itemplan, idEstacion) {
            var vue = this;
            $.ajax({
                type : 'POST',
                url  : 'getDataUpdate',
                data : { itemplan   : itemplan,
                         idEstacion : idEstacion }
            }).done(function(data){
                data = JSON.parse(data);
                vue.jsonFormObrasP = data.arrayDataUpdate;
                console.log(vue.jsonFormObrasP.fecha_form);
                modal('modalFormObrasPub');
            });
        },
        registrarFormObraPub:function() {
            var vue = this;
            $.ajax({
                type : 'POST',
                url  : 'updateData',
                data : { jsonFormObrasP : vue.jsonFormObrasP }
            }).done(function(data){
                location.reload();
                modal('modalFormObrasPub');
            });
        }
    },
    mounted:function() {
        this.getTablaArrayObp();
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
});