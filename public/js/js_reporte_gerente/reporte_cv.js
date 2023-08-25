new Vue({
    el: '#app',
    data: () => {
        return {
            searchDetalle:'',
            modalDetalle:false,
            modalDetJefEECC:false,
             modalDetPOCV:false,
            drawer: null,
            arrayDetalleEmpresaColab: [],  
            arrayDetCVJefEECC:  [],
            arrayDetPOCV:  [],
            currentItem: 'tab-Web',
            flg : 0,
            anioactual:null,
            itemsMenu: [
                { title: 'Home',           icon: 'dashboard', metodo: 'getReporte();'},
                { title: 'Paralizaciones', icon: 'question_answer', metodo: 'getParalizados();'}
              ],
              mini: false,
              right: null,
            items: [{
                        tabs: 'status'
                    },
                   {
                        tabs: 'busqueda'
                   }],
            search: '',
            headers: [
                { text: 'Jefatura', value: 'Jefatura' },
                { text:  'Antes de Ayer', value: 'Fecha', class:"antes_ayer_jef" },
                { text: 'Ayer'  , value: 'Fecha' , class:"ayer_jef" },
                { text: 'Hoy'   , value: 'Fecha' , class:"hoy_jef"}
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
                                    }                  
                      },
            arrayDetalleEmpresaColab: [],
            arrayTodoDetalle : [],
            arrayParalizacion : [],
            arrayCVJefEECC   :  [],
            headerDetalleObra: [
                { text: 'Jefatura'             , value : 'Empresa Colaboradora'},
                { text: 'Empresa Colaboradora' , value : 'zonal' },
                { text: 'Busqueda'             , value : 'busqueda' , class: "cantidadPreRegistro" }, 
                { text: 'Diseño(Pendientes)'   , value : 'disenio'    , class:"cantidadDiseno"},                
                { text: 'En Obra(Pendientes)'  , value : 'pendientes' , class:"cantidadPendientes"},
                { text: 'Liquidadas(HOY)'      , value : 'liquidadas' , class: "cantidadPreliquidado" }
            ],
            
            headerRepJefEECC: [
            { text: 'Jefatura'             , value : 'Jefatura'},
            { text: 'Empresa Colaboradora' , value : 'EmpresaColaboradora' },
            { text: 'Sin fecha Const.'     , value : 'SinFecha'}, 
            { text: 'Enero'    , value : 'Enero'  , class: "cantidadEne" },
            { text: 'Febrero'  , value : 'Febrero', class: "cantidadFeb"},
            { text: 'Marzo'    , value : 'Marzo'  , class: "cantidadMar"},
            { text: 'Abril'    , value : 'Abril'  , class: "cantidadAbr"},
            { text: 'Mayo'     , value : 'Mayo'   , class: "cantidadMay"},
            { text: 'Junio'    , value : 'Junio'  , class: "cantidadJun"},
            { text: 'Julio'    , value : 'Julio'  , class: "cantidadJul"},
            { text: 'Agosto'   , value : 'Agosto' , class: "cantidadAgo"},
            { text: 'Septiembre' , value : 'Septiembre' , class: "cantidadSep"},
            { text: 'Octubre'    , value : 'Octubre'    , class: "cantidadOct"},
            { text: 'Noviembre'  , value : 'Noviembre'  , class: "cantidadNov"},
            { text: 'Diciembre'  , value : 'Diciembre'  , class: "cantidadDic"}               
                
            ],

            headerDetJefEECC: [
                { text: 'Itemplan'             , value : 'Itemplan'},
                { text: 'Nombre Proyecto' , value : 'NombreProyecto' },
                { text: 'Jefatura'     , value : 'Jefatura'}, 
                { text: 'EECC'    , value : 'EECC'  },
                { text: 'Estado'  , value : 'Estado'},
                { text: 'Fecha Creacion IP'    , value : 'FechaCreacionIP'  },
                { text: 'Fecha Fin de Construccion'    , value : 'FechaFinConstruccion'  },
                { text: '% Avance'     , value : 'Avance'  }           
                
            ],
            
            totalesPlanObra : {        
                sumaPendientes  : 0,
                sumaLiquidadas  : 0,
                sumaCanceladas  : 0,
                sumaDiseno      : 0,
                sumaParalizada  : 0,
                sumaPreRegistro : 0                       
            },

            totalesCVJefEECC : {        
                cantidadEne  : 0,
                cantidadFeb  : 0,
                cantidadMar  : 0,
                cantidadAbr  : 0,
                cantidadMay  : 0,
                cantidadJun : 0 ,
                cantidadJul  : 0,
                cantidadAgo  : 0,
                cantidadSep  : 0,
                cantidadOct  : 0,
                cantidadNov : 0 ,
                cantidadDic : 0 

            }
            
            
            
            
            
        }
    },
    methods: {
        getTablaReporte:function(){
            vue = this;
            $.ajax({
                type : 'POST',
                url  : 'getTablaReporteCv'
            }).done(function(data){
                data = JSON.parse(data);
                vue.arrayTodoDetalle  = data.todoTablaDetalle;
                // vue.arrayParalizacion = data.arrayReporteParalizacion;

                if(vue.flg==0) {
                    vue.sumarCantidades();                
                }

                data.todoTablaDetalle.forEach(function(data) {
                    vue.totalesPlanObra.sumaLiquidadas = vue.totalesPlanObra.sumaLiquidadas+parseInt(data.hoy_pre_liqui);
                    vue.totalesPlanObra.sumaPendientes = vue.totalesPlanObra.sumaPendientes+parseInt(data.obra);                    
                    vue.totalesPlanObra.sumaCanceladas = vue.totalesPlanObra.sumaCanceladas+parseInt(data.hoy_trunco);
                    vue.totalesPlanObra.sumaDiseno     = vue.totalesPlanObra.sumaDiseno+parseInt(data.diseno);
                    vue.totalesPlanObra.sumaPreRegistro = vue.totalesPlanObra.sumaPreRegistro+parseInt(data.pre_registro);      

                });
                $('.cantidadPendientes').append("<br>TOTAL: "+vue.totalesPlanObra.sumaPendientes+"</br>");
                $('.cantidadPreliquidado').append("<br>TOTAL: "+vue.totalesPlanObra.sumaLiquidadas+"</br>");
                $('.cantidadDiseno').append("<br>TOTAL: "+vue.totalesPlanObra.sumaDiseno+"</br>");
                $('.cantidadPreRegistro').append("<br>TOTAL: "+vue.totalesPlanObra.sumaPreRegistro+"</br>");
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
        
        getDataCVJefeEECC:function(){
            vue=this;

            $.ajax({
                type : 'POST',
                url  : 'getIPCVRepOnLine'
            }).done(function(data){
                data = JSON.parse(data);
                vue.arrayCVJefEECC  = data.listaCVJefEECC;
                
                data.listaCVJefEECC.forEach(function(data) {
                    vue.totalesCVJefEECC.cantidadEne = vue.totalesCVJefEECC.cantidadEne+parseInt(data.FECH_1);
                    vue.totalesCVJefEECC.cantidadFeb = vue.totalesCVJefEECC.cantidadFeb+parseInt(data.FECH_2);
                    vue.totalesCVJefEECC.cantidadMar = vue.totalesCVJefEECC.cantidadMar+parseInt(data.FECH_3);
                    vue.totalesCVJefEECC.cantidadAbr = vue.totalesCVJefEECC.cantidadAbr+parseInt(data.FECH_4);
                    vue.totalesCVJefEECC.cantidadMay = vue.totalesCVJefEECC.cantidadMay+parseInt(data.FECH_5);
                    vue.totalesCVJefEECC.cantidadJun = vue.totalesCVJefEECC.cantidadJun+parseInt(data.FECH_6);
                    vue.totalesCVJefEECC.cantidadJul = vue.totalesCVJefEECC.cantidadJul+parseInt(data.FECH_7);
                    vue.totalesCVJefEECC.cantidadAgo = vue.totalesCVJefEECC.cantidadAgo+parseInt(data.FECH_8);
                    vue.totalesCVJefEECC.cantidadSep = vue.totalesCVJefEECC.cantidadSep+parseInt(data.FECH_9);
                    vue.totalesCVJefEECC.cantidadOct = vue.totalesCVJefEECC.cantidadOct+parseInt(data.FECH_10);
                    vue.totalesCVJefEECC.cantidadNov = vue.totalesCVJefEECC.cantidadNov+parseInt(data.FECH_11);                    
                    vue.totalesCVJefEECC.cantidadDic = vue.totalesCVJefEECC.cantidadDic+parseInt(data.FECH_12);
                });

                anioactual=new Date();
                anioactual=anioactual.getFullYear();

                $('.cantidadEne').append(anioactual+"<br>TOTAL: "+vue.totalesCVJefEECC.cantidadEne+"</br>");
                $('.cantidadFeb').append(anioactual+"<br>TOTAL: "+vue.totalesCVJefEECC.cantidadFeb+"</br>");
                $('.cantidadMar').append(anioactual+"<br>TOTAL: "+vue.totalesCVJefEECC.cantidadMar+"</br>");
                $('.cantidadAbr').append(anioactual+"<br>TOTAL: "+vue.totalesCVJefEECC.cantidadAbr+"</br>");
                $('.cantidadMay').append(anioactual+"<br>TOTAL: "+vue.totalesCVJefEECC.cantidadMay+"</br>");
                $('.cantidadJun').append(anioactual+"<br>TOTAL: "+vue.totalesCVJefEECC.cantidadJun+"</br>");
                $('.cantidadJul').append(anioactual+"<br>TOTAL: "+vue.totalesCVJefEECC.cantidadJul+"</br>");
                $('.cantidadAgo').append(anioactual+"<br>TOTAL: "+vue.totalesCVJefEECC.cantidadAgo+"</br>");
                $('.cantidadSep').append(anioactual+"<br>TOTAL: "+vue.totalesCVJefEECC.cantidadSep+"</br>");
                $('.cantidadOct').append(anioactual+"<br>TOTAL: "+vue.totalesCVJefEECC.cantidadOct+"</br>");
                $('.cantidadNov').append(anioactual+"<br>TOTAL: "+vue.totalesCVJefEECC.cantidadNov+"</br>");
                $('.cantidadDic').append(anioactual+"<br>TOTAL: "+vue.totalesCVJefEECC.cantidadDic+"</br>");
                
      
            }); 
        },
        
        detalleJefEECCCV:function(jefatura,eecc,mes) {
          
            $.ajax({
                type : 'POST',
                url  : 'getDetCVJEECCOnline',
                data : {jefatura : jefatura,
                        eecc     : eecc,
                        mes      : mes }
            }).done(function(data){
                data = JSON.parse(data);
                vue.arrayDetCVJefEECC = data.listaDetCVJefEECC;
                vue.modalDetJefEECC=true;              
            });      
        },
        
        detalleDataPOCVOnline:function(jefatura,eecc,estado) {
          
            $.ajax({
                type : 'POST',
                url  : 'getDetDataPOCVOnline',
                data : {jefatura : jefatura,
                        eecc     : eecc,
                        estado   : estado}
            }).done(function(data){
                data = JSON.parse(data);
                vue.arrayDetPOCV = data.TablaDetallePOCV;
                vue.modalDetPOCV=true;              
            });      
        }

    },  
    mounted:function() {
        $('#tbZonal').css('overflow-x','scroll');
        this.getTablaReporte();
        this.getDataCVJefeEECC();
    }
  })