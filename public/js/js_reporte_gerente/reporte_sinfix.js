new Vue({
    el: '#app',
    data: () => {
        return {
            searchDetalle:'',
            modalDetalle:false,
            drawer: null,
            arrayDetalleEmpresaColab: [],             
            currentItem: 'tab-Web',
            flg : 0,
            itemsMenu: [
                { title: 'Home',           icon: 'dashboard', metodo: 'getReporte();'},
                { title: 'Paralizaciones', icon: 'question_answer', metodo: 'getParalizados();'}
              ],
              mini: false,
              right: null,
            items: [{
                        tabs: 'Avance del día'
                    },
                   {
                        tabs: 'evolutivo'
                   },
                    {
                        tabs: 'status'
                    }],
            search: '',
            headers: [
                { text: 'Jefatura', value: 'Jefatura' },
                { text:  'Antes de Ayer', value: 'Fecha', class:"antes_ayer_jef" },
                { text: 'Ayer'  , value: 'Fecha' , class:"ayer_jef" },
                { text: 'Hoy'   , value: 'Fecha' , class:"hoy_jef"}
              ], 
            headersEecc: [
                { text: 'EECC', value: 'EECC' },
                { text: 'Hoy (Lima)', value: 'Fecha' , class:"hoy_ecc_lima"},
                { text: 'Hoy (Provincia)', value: 'Fecha' , class:"hoy_ecc_lima"}
              ], 
            headerDetalle: [
                { text: 'itemplan', value: 'itemplan'},
                { text: 'Nombre Proyecto', value: 'nombre Proyecto'},
                { text: 'zonal'   , value : 'zonal' },
                { text: 'sisego'  , value : 'sisego' },
                { text: 'hora'    , value : 'hora' }
            ],  
            arrayJefatura: [],
            arrayEecc : [],
            totales : {
                        jefatura  : {
                                        sumaAntesAyer : 0,
                                        sumaAyer      : 0,
                                        sumaHoy       : 0
                                    },
                        emp_colab : {
                                        sumaAntesAyer : 0,
                                        sumaAyer      : 0,
                                        sumaHoy       : 0
                                    },
                        paralizacion : {
                                          sumaHoyLima   : 0,
                                          sumaProvincia : 0
                                        }                        
                      },
            arrayDetalleEmpresaColab: [],
            arrayTodoDetalle : [],
            arrayParalizacion : [],
            headerDetalleObra: [
                { text: 'Jefatura'             , value: 'Empresa Colaboradora'},
                { text: 'Empresa Colaboradora' , value : 'zonal' },
                { text: 'Dise\u00f1o(Pendientes)'   , value : 'disenio'    , class:"cantidadDiseno"},                
                { text: 'En Obra(Pendientes)'  , value : 'pendientes' , class:"cantidadPendientes"},
                { text: 'Liquidadas(HOY)'      , value : 'liquidadas' , class: "cantidadPreliquidado" },
                { text: 'Canceladas(HOY)'      , value : 'Cantidad Canceladas', class: "cantidadCancelada" },
                { text: 'Paralizados(HOY)'     , value : 'Cantidad Canceladas', class: "cantidadParalizados" }                 
            ],
            headerParalizacion: [
                { text: 'Empresa Colaboradora' , value: 'Empresa Colaboradora'},
                { text: 'Lima (HOY)'           , value : 'Lima'       , class: "hoyPaLima"},
                { text: 'Provincia (HOY)'      , value : 'Provincia'  , class: "hoyPaProvincia"}          
            ],
            headerParalizacion: [
                { text: 'Empresa Colaboradora' , value: 'Empresa Colaboradora'},
                { text: 'Lima (HOY)'           , value : 'Lima'       , class: "hoyPaLima"},
                { text: 'Provincia (HOY)'      , value : 'Provincia'  , class: "hoyPaProvincia"}          
            ],
            totalesPlanObra : {        
                sumaPendientes : 0,
                sumaLiquidadas : 0,
                sumaCanceladas : 0,
                sumaDiseno     : 0,
                sumaParalizada : 0 
            }           
        }
    },
    methods: {
        getTablaReporte:function(){
            vue = this;
            $.ajax({
                type : 'POST',
                url  : 'getTablaReporte'
            }).done(function(data){
                data = JSON.parse(data);
                vue.arrayJefatura = data.arrayReporteTableJefatura;
                vue.arrayEecc     = data.arrayReporteTablaEecc;
                vue.arrayTodoDetalle  = data.todoTablaDetalle;
                // vue.arrayParalizacion = data.arrayReporteParalizacion;

                if(vue.flg==0) {
                    vue.sumarCantidades();                
                }
                vue.sumarParalizaciones();

                data.todoTablaDetalle.forEach(function(data) {
                    vue.totalesPlanObra.sumaLiquidadas = vue.totalesPlanObra.sumaLiquidadas+parseInt(data.hoy_pre_liqui);
                    vue.totalesPlanObra.sumaPendientes = vue.totalesPlanObra.sumaPendientes+parseInt(data.obra);                    
                    vue.totalesPlanObra.sumaCanceladas = vue.totalesPlanObra.sumaCanceladas+parseInt(data.hoy_trunco);
                    vue.totalesPlanObra.sumaDiseno     = vue.totalesPlanObra.sumaDiseno+parseInt(data.diseno);      
                    vue.totalesPlanObra.sumaParalizada = vue.totalesPlanObra.sumaParalizada+parseInt(data.hoyParalizacion);                
                });
                $('.cantidadPendientes').append("<br>TOTAL: "+vue.totalesPlanObra.sumaPendientes+"</br>");
                $('.cantidadPreliquidado').append("<br>TOTAL: "+vue.totalesPlanObra.sumaLiquidadas+"</br>");
                $('.cantidadCancelada').append("<br>TOTAL: "+vue.totalesPlanObra.sumaCanceladas+"</br>");
                $('.cantidadDiseno').append("<br>TOTAL: "+vue.totalesPlanObra.sumaDiseno+"</br>");
                $('.cantidadParalizados').append("<br>TOTAL: "+vue.totalesPlanObra.sumaParalizada+"</br>");
                vue.flg=1;
                $('#tbZonal').css('overflow-x','scroll');
            });        
        },
        sumarCantidades:function(){
            vue.arrayJefatura.forEach(function(data){
                vue.totales.jefatura.sumaAntesAyer = vue.totales.jefatura.sumaAntesAyer+parseInt(data.cantidadAntesAyer); 
                vue.totales.jefatura.sumaAyer      = vue.totales.jefatura.sumaAyer+parseInt(data.cantidadAyer);                 
                vue.totales.jefatura.sumaHoy       = vue.totales.jefatura.sumaHoy+parseInt(data.cantidadHoy); 
            });
            $('.antes_ayer_jef').append("<br>TOTAL: "+vue.totales.jefatura.sumaAntesAyer+"</br>");
            $('.ayer_jef').append("<br>TOTAL: "+vue.totales.jefatura.sumaAyer+"</br>");
            $('.hoy_jef').append("<br>TOTAL: "+vue.totales.jefatura.sumaHoy+"</br>");
            
        },

        sumarParalizaciones:function() {
            var vue = this;
            this.arrayParalizacion.forEach(function(data){
                vue.totales.paralizacion.sumaHoyLima      = vue.totales.paralizacion.sumaHoyLima+parseInt(data.hoyLima);
                vue.totales.paralizacion.sumaHoyProvincia = vue.totales.paralizacion.sumaHoyLima+parseInt(data.hoyProvincia);
            });
            $('.hoyPaLima').append("<br>TOTAL: "+vue.totales.paralizacion.sumaHoyLima+"</br>");
            $('.hoyPaProvincia').append("<br>TOTAL: "+vue.totales.paralizacion.sumaHoyProvincia+"</br>");
        }, 

        datalleEmpresaColab:function(idEmpresaColab, flg) {
            $.ajax({
                type : 'POST',
                url  : 'getDetalleDataEmpreColab',
                data : {idEmpresaColab : idEmpresaColab,
                        flg            : flg }
            }).done(function(data){
                data = JSON.parse(data);
                vue.arrayDetalleEmpresaColab = data.arrayDetalle;
                vue.modalDetalle=true;              
            });      
        },
        getTbParalizados:function() {
            console.log("ENTRO");
        }
    },  
    mounted:function() {
        $('#tbZonal').css('overflow-x','scroll');
        this.getTablaReporte();
    }
  })



  function getParalizados() {
      vue.getTbParalizados();
  }

  function getReporte() {
    vue.getTablaReporte();
  }