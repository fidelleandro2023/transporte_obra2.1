<!DOCTYPE html>
<html lang="es">
  
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="twitter:image" content="../img/bracketplus-social.png">

    <!-- Facebook -->
    
    <meta property="og:description" content="Premium Quality and Responsive UI for Dashboard.">

    <meta property="og:image" content="../img/bracketplus-social.png">
    <meta property="og:image:secure_url" content="../img/bracketplus-social.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Premium Quality and Responsive UI for Dashboard.">

    <title>Administrador</title>
    <style>
    html,
body,
#map_canvas {
  height: 100%;
  width: 100%;
  margin: 0px;
  padding: 0px
}
    </style>
  </head>

<body class="collapsed-menu">
<div id="map_canvas" >
</div>
<script src="../lib/jquery/jquery.js"></script>  
<script type='text/javascript' src='//maps.google.com/maps/api/js?libraries=geometry&#038;key=AIzaSyCQT6WVUZH-OJgtJmrj7oWzjdTzGVncWO0'></script>
<script>
var markers = [];
var map;
var labels = 'ABCD';
var labelIndex = 0;

function initialize() {
  map = new google.maps.Map(
    document.getElementById("map_canvas"), {
 <?php
if($c["coordX"]&&$c["coordY"]){
?>    
      center: new google.maps.LatLng(<?php echo $c["coordX"];?>, <?php echo $c["coordY"];?>),
<?php }else{
?>
      center: new google.maps.LatLng(<?php echo $r[0];?>, <?php echo $r[1];?>),
<?php
}?>     
      zoom: 15,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

  addMarker({
    lat: <?php echo $r[0];?>,
    lng: <?php echo $r[1];?>
  }, "red");
<?php
if($c["coordX"]&&$c["coordY"]){
?>
  addMarker({
    lat: <?php echo $c["coordX"];?>,
    lng: <?php echo $c["coordY"];?>
  }, "green");
<?php }?>
}
google.maps.event.addDomListener(window, "load", initialize);


function addMarker(location, color) {
  var marker = new google.maps.Marker({
    position: location,
    label: labels[labelIndex++ % labels.length],
    icon: {
      url: 'http://maps.google.com/mapfiles/ms/icons/' + color + '.png',
      labelOrigin: new google.maps.Point(15, 10)
    },
    map: map
  });
  markers.push(marker);
}
</script>

  </body>

</html>
