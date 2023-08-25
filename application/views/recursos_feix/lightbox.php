<script src="<?php echo base_url();?>public/ekko-lightbox/js/ekko-lightbox.js"></script>
<script src="<?php echo base_url();?>public/lightbox2/js/lightbox.js"></script>
<script type="text/javascript">
<?php
if($pagina=="pendiente"){
?>
$(document).on("click",'[data-toggle="lightbox"]',function(a){a.preventDefault();$(this).ekkoLightbox()});	
<?php }?>
</script>