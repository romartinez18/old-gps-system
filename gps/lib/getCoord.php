<?php 


session_start();


 $a = session_id();
      if(empty($a)){session_start();}


// verificar sesion
if (!isset($_SESSION['id'])) { 
?>
<script>
window.location="index.php";
</script>
<?php
} else if (!isset($_SESSION['nivel'])) { 
?>
<script>
window.location="index.php";
</script>
<?php
}

require_once '../class.conexion.php';
include_once('../class.config.php');
session_start();

 $Cedula = trim(base64_decode($fila['cedula']));
 //$id = trim(base64_decode($fila['idusuario'])); 
 $id = trim(base64_decode($fila['id'])); 
 $nivel = trim(base64_decode($fila['nivelautorizacion']));       

$dbh = Conexion::singleton_conexion();

//$id = "353451041983782";

$sql = "SELECT * from tramagps WHERE (idvehiculo = '".$id."') LIMIT 0,100";

$query = $dbh->prepare($sql);

$query->execute();
$dbh = null;
$i = 0;
$total = $query->rowCount();

     while ($total > 0)
     {
       $fila[$i] = $query->fetch();
     } 

?> 

<script>
	
	var mapa,obj,contenido;
	var ventanas = [];
	var latitud = [<?php for($i = 0; $i < $total;$i++){echo $fila[$i]->latitud.",".$fila[$i]->longitud;} ?>];
	var longitud = [-67.597852,-67.582555];
	var imagen = ['images/obj/car.png','images/obj/ambulance.png'];
	var latlong1 = new google.maps.LatLng(latitud[0], longitud[0]);
	var latlong2 = new google.maps.LatLng(latitud[1], longitud[1]);
	
	function inicializar() {
	
         var mapOpc = {
			zoom: 12,
			/*mapTypeId:google.maps.MapTypeId.ROADMAP,*/
			mapTypeId:google.maps.MapTypeId.TERRAIN,
			/*mapTypeId:google.maps.MapTypeId.HYBRID,*/
			center: {lat:latitud[0], lng:longitud[0]},
			panControl: false,
			zoomControl: true,
			scaleControl: true
		};
	
	  mapa = new google.maps.Map(document.getElementById('mapa-canvas'), mapOpc);
	  
	  //var obj1,obj2;
	  var marks = [];
	  
//	  obj1 = dibujarObj(latlong1,imagen[0]);
	  ventanas[0] = dibujarObj(latlong1,imagen[0]);
//	  obj2 = dibujarObj(latlong2,imagen[1]);
	  ventanas[1] = dibujarObj(latlong2,imagen[1]);
//	  marks.push(obj1);
	  marks.push(ventanas[0]);
	  marks.push(ventanas[1]);
//	  marks.push(obj2);
//	  cargarVentana(obj1);
	  cargarVentana(ventanas[0]);
  	  cargarVentana(ventanas[1]);
//	  cargarVentana(obj2);
	  
	  
//	 google.maps.event.addListener(obj1, 'click', function(){
	 google.maps.event.addListener(ventanas[0], 'click', function(){
	 	//cargarVentana(obj1);
	 	cerrarVentanas();
//		ventana.open(mapa,obj1);
		ventana.open(mapa,ventanas[0]);
	 });
//	 google.maps.event.addListener(obj2, 'click', function(){
	 google.maps.event.addListener(ventanas[1], 'click', function(){
	 	//cargarVentana(obj2);
	 	cerrarVentanas();
		ventana.open(mapa,ventanas[1]);
		
	 });
	 
	 //var markerCluster = new MarkerClusterer(mapa, (obj1,obj2));
	  var markerCluster = new MarkerClusterer(mapa, marks);
	}
	
	function cerrarVentanas() {
	   $.each(ventanas, function(indice, contenido){
 	       ventanas[indice].close();
	   });
	}
	
	function dibujarObj(latlong,img) {
	  	  
	 obj =  new google.maps.Marker({
		      position:latlong,
		      map:mapa,
		      icon:img
	        }); 
	  return obj;	
	}

	function cargarVentana(obj) {
	
          //if(!window.ventana) { 
            contenido = "<div id='contentInfoWindow'>Titulo</div>" +
		      "<div>Contenido</div>"  +
		      "<div>Latitud: "  + obj.getPosition().lat() + "</div>" +
		      "<div>Longitud: " + obj.getPosition().lng() + "</div>";
		         
	     window.ventana = new google.maps.InfoWindow({
				  content:contenido,
				  maxWidth: 200
				});
	  //}
	  
	}
	

	google.maps.event.addDomListener(window, 'load', inicializar);

    </script>