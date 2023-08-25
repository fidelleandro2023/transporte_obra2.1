<link rel="stylesheet" href="<?php echo base_url();?>public/css/galeria_fotos.css?v=<?php echo time();?>">
<style>.fa-crosshairs,.fa-money,.fa-bell,.fa-cog,.fa-book,.fa-warning,.fa-edit,.fa-download,.icofont-zipped{margin-right:10px}#simpletable tr td:nth-child(6){color:#ec3305}.popover-body{text-align:center}.cancelar,.terminar,.devolver,.asignar,.situacion{font-size:16px}</style>


<main id="obrasConObp">
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row heading-bg">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h5 class="txt-dark"><span class="txt-info"><?php echo $pagina;?></span></h5>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                    <li><a href="index.php">Inicio</a></li>
                    <!-- <li><a href="#" class=""><span>Registro Ficha Tecnica</span></a></li> -->
                    <li><a href="#" class="active"><span><?php echo $pagina;?></span></a></li>
                    <li><a href="#" class="active"><span>Consultar Formulario</span></a></li>
                    </ol>
                </div>
            </div>
        </div>
        

    <div class="panel panel-default card-view">
        <div class="panel-body">
            <div class="table-wrap">
                <div class="table-responsive" style="width:100%">
                    <table id="simpletable" class="container-fluid table table-hover display  pb-30 table-striped table-bordered nowrap">
                        <thead>
                            <th>Itemplan</th>
                            <th>Fase</th> 
                            <th>Canalizaci&oacute;n Km</th>
                            <th>C&aacute;maras Und</th>
                            <th>C (postes)</th>
                            <th>MA (postes)</th>
                            <th>Km ducto</th>
                            <th>Km Tritubo</th>
                            <th>Km Par Cobre</th>
                            <th>Km Cable Coax</th>
                            <th>Km Cable FO</th>
                            <th>Observaci&oacute;n</th>
                            <th>Fecha</th>
                            <th>Editar</th>
                        </thead>
                        <tbody>
                            <tr v-for="row in arrayFormulario">
                                <td>{{row.itemplan}}</td>
                                 <td>{{row.faseDesc}}</td> 
                                <td>{{row.canalizacion_km}}</td>
                                <td>{{row.camaras_und}}</td>
                                <td>{{row.c_postes}}</td>
                                <td>{{row.ma_postes}}</td>
                                <td>{{row.km_ducto}}</td>
                                <td>{{row.km_tritubo}}</td>
                                <td>{{row.km_par_cobre}}</td>
                                <td>{{row.km_cable_coax}}</td>
                                <td>{{row.km_fo}}</td>
                                <td>{{row.observacion}}</td>
                                <td>{{row.fecha_registro}}</td>
                                <td>
                                    <a style="cursor:pointer" @click="openModalUpdate(row.itemplan, row.idEstacion)"><i class="fa fa-pencil"></i></a>
                                </td>
                            </tr>                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div> 
    <?php
        include('application/views/vf_formulario/v_obra_publica.php');
    ?>                   
</main>


<script src="<?php echo base_url();?>public/vendors/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url();?>public/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>public/dist/js/jquery.slimscroll.js"></script>
<script src="<?php echo base_url();?>public/dist/js/dropdown-bootstrap-extended.js"></script>
<script src="<?php echo base_url();?>public/dist/js/init.js"></script>

<script src="<?php echo base_url();?>public/js/Utils.js?v=<?php echo time();?>"></script>
  
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="<?php echo base_url();?>public/js/js_consultas/consultarObrasPublicas.js?v=<?php echo time();?>"></script>  
<script src="<?php echo base_url();?>public/bower_components/notify/pnotify.custom.min.js?v=<?php echo time();?>"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/fancy/source/jquery.fancybox.js"></script>

<script src="<?php echo base_url();?>public/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.flash.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/jszip/dist/jszip.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/pdfmake/build/pdfmake.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/pdfmake/build/vfs_fonts.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="<?php echo base_url();?>public/vendors/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="<?php echo base_url();?>public/dist/js/export-table-data.js"></script>

  <script type="text/javascript">

</script>  