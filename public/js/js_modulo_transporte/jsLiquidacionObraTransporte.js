	function openModalPorcentaje(btn) {
        itemPlanGlobalPorcentaje = btn.data('item_plan');
        $this=$(this);$.fancybox({
            height:"100%",href:"porcentaje?id="+itemPlanGlobalPorcentaje,type:"iframe",width:"100%"
        });return!1
    }

    function zipItemPlan(btn) {
        var itemPlan = btn.data('item_plan');
        var val = null;
        if(itemPlan == null || itemPlan == '') {
            return;
        }
        
        $.ajax({
            type : 'POST',
            url  : 'zipItemPlan',
            data : { itemPlan : itemPlan }
        }).done(function(data){
            // try {
                data = JSON.parse(data);
                if(data.error == 0) {
                    var url= data.directorioZip; 
                    if(url != null) {
                        val = window.open(url, 'Download');
                    } else {
                        alert('No tiene evidencias');
                    }   
                    // mostrarNotificacion('success', 'descarga realizada', 'correcto');
                } else {
                    // mostrarNotificacion('error', 'descarga no realizada', 'error');            
                    alert('error al descargar');
                }
            // } catch(err) {
                // alert(err.message);
            // }
        });
    }

    function filtrarTablaLiqui() {
        var itemplan = $('#txtItemplan').val();

        $.ajax({
            type : 'POST',
            url  : 'filtrarTablaLiquiTransp',
            data : { itemplan : itemplan }
        }).done(function(data){
            data = JSON.parse(data);
            console.log("ENTRO");
            $('#contTabla').html(data.htmlTabla);
            initDataTable('#data-table');
        });
    }