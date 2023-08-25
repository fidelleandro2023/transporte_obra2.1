var app = new Vue ({
    el: '#mCuadrilla',
    data: {
        arrayZonal   : [],
        arrayEcc     : [],
        arraTablaCua : [],
        arrayCuadrilla : [],
        titulo : 'MANTENIMIENTO DE CUADRILLA',
        objData : any = { 
                            inputNombreCuadrilla : {
                                                    label       : 'NOMBRE DE CUADRILLA',
                                                    modelNombre : null,
                                                    msjValid    : null
                                                   },
                            cmbZonal             : {
                                                    label      : 'ZONAL',
                                                    modelZonal : 0,
                                                    msjValid    : null
                                                   },
                            cmbEcc               : {
                                                    label      : 'EMPRESA COLABORADORA',
                                                    modelEcc   : 0,
                                                    msjValid   : null
                                                   }                            
                        }
    },
    methods:{
        getTablaCuadrilla:function() {
            vue = this;
            $.ajax({
                type : 'POST',
                url  : 'getTablaCuadrilla'
            }).done(function(data){
                data = JSON.parse(data);
                console.log(data);
                vue.arrayCuadrilla = data.array_cuad;
                console.log(vue.arrayCuadrilla);
            });
        },
        openModalForm:function() {
            modal('modalFormCuadrilla');
        },
        getCmb:function() {
            vue = this;
            $.ajax({
                type : 'POST',
                url  : 'getCmbsCuadrillas'
            }).done(function(data){
                data = JSON.parse(data);
                vue.arrayZonal = data.cmbZonal;
                vue.arrayEcc   = data.cmbEecc;
            });
        }, 
        registrarCuadrilla:function() {
            vue = this;
            $.ajax({
                type : 'POST',
                url  : 'registrarCuadrilla',
                data : { 'nombCuadrilla' : vue.objData.inputNombreCuadrilla.modelNombre,
                         'idEecc'        : vue.objData.cmbEcc.modelEcc,
                         'idZonal'       : vue.objData.cmbZonal.modelZonal }
            }).done(function(data){
                data = JSON.parse(data);
                console.log("dataErr "+data.error);
                try {
                    if(data.error==0) {
                        mostrarNotificacion('success', 'Cuadrilla registrada');
                        vue.arrayCuadrilla = data.array_cuad;
                        modal('modalFormCuadrilla');
                    } else {
                        mostrarNotificacion('error', data.msj);
                    }
                } catch(err) {
                    mostrarNotificacion('error', err.message);
                }
            });
        }
    }, 
    mounted: function() {
        this.getCmb();
        this.getTablaCuadrilla();
    }
});