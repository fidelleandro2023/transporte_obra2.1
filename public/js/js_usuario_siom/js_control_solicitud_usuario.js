var idPerfilGlb = null;
function openModalUsuariosActivos(btn) {
    idPerfilGlb = btn.data('id_perfil');

    if(idPerfilGlb == null || idPerfilGlb == '') {
        return;
    }

    $.ajax({
        type : 'POST',
        url  : 'getDataUsuarioSiomAct',
        data : { id_perfil : idPerfilGlb }
    }).done(function(data){
        data = JSON.parse(data);
        $('#contTablaUsuarioAct').html(data.tablaUsuario);
        initDataTable('#data-table_detalle');
        // $('#contTablaUsuarioAct').html(data.tablaUsuario);
        
        modal('modalUsuarioActivo');
        
        
    });

}