<?php 

session_start();
error_reporting(E_ALL);
include_once('class/class.conexion.php');

// verificar sesion
if (isset($_SESSION['cedula'])) { $Cedula = base64_decode($_SESSION['cedula']); } else { $Cedula = 0; }
if (isset($_SESSION['id'])) { $Id = base64_decode($_SESSION['id']); } else { $Id = 0; }
if (isset($_SESSION['nivel'])) { $Nivel = base64_decode($_SESSION['nivel']); } else { $Nivel = 0; }
if (isset($_SESSION['tipo'])) { $Tipo = base64_decode($_SESSION['tipo']); } else { $Tipo = 0; }
if (isset($_SESSION['usuario'])) { $Usuario = base64_decode($_SESSION['usuario']); } else { $Usuario = ""; }

$dbh = Conexion::singleton_conexion();
 
$sql = "SELECT * from usuarios WHERE (id = ".$Id." AND cedula = '".$Cedula."')";
$query = $dbh->prepare($sql);
$query->execute();
$filas = $query->fetchAll(PDO::FETCH_ASSOC);
//$filas = (int) $query->fetchColumn(); 
if (count($filas) <= 0) {      
  //si no existe el usuario
  //if ($tfilas <= 0) {
?>

<script>
window.location="index.php";
</script>

<?php
  }
  
 
  //print_r($filas[0]);
  if (isset($filas[0]['foto'])) {
     $Foto = "images/usuarios/".$filas[0]['foto'];
  } else {
     $Foto = "images/usuarios/avatar05.png";
  }
    
?> 

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Localizaci&oacute;n Sat&eacute;lital GPS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Localizacion Satelital GPS Con Sistemas AVL">
    <meta name="author" content="Ronald Martinez">
    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/bootstrap-submenu.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
    <link href="componentes/DataTable/css/jquery.dataTables.css" rel="stylesheet">
    <link href="componentes/DataTable/css/TableTools.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="icon" href="images/favicon.png">
	
	<style>
	 body {
	   background-color:#BBB;
	 }
	 .carga {
	   position:relative;
	   padding:15px;
	   background: url(images/carga.gif) no-repeat center center; 
	   width:50px;
	   height:50px; 
	 }
	#menu-top2{
	position: absolute;
	top: 1%;
     	left: 85%;
     	z-index: 2;
     
	 }
	 #adm-vehiculosoff{
	position: absolute;
	top: 15%;
     	left: 1%;
     	z-index: 2;
            
	 }
        #adm-vehiculos{
        background-color: #ffffff;
	position: absolute;
        width:250px;
	top: 12%;
     	left: 1%;
     	z-index: 2;
        border-radius: 5px 5px 5px 5px;
        -moz-border-radius: 5px 5px 5px 5px;
        -webkit-border-radius: 5px 5px 5px 5px;
        box-shadow: 3px 3px 1px #888888;
    
	 }
	#preloader{
	position: absolute;
	width: 20%;
        right: 40%;
	top: 50%;
     	z-index: -2;
     
	 }
	 
      #mapa-canvas{
      position: absolute;
      top: 9%;
      left: 0;
      bottom: 5%;
      width:100%;
      height:91%; 
      right: 0;
      z-index: -1;
     }
	</style>
	<script language="javascript">
	  var mapa,obj,contenido;
	  var ventanas = [];
	  var marks = [];
  	  var markerCluster;
  	  
	</script>
  </head>

  
 <body>
	<div class="bgpreloader"></div>
	<div class="gpsload-thecube">
		<div class="gpsload-cube gpsload-c1"></div>
		<div class="gpsload-cube gpsload-c2"></div>
		<div class="gpsload-cube gpsload-c4"></div>
		<div class="gpsload-cube gpsload-c3"></div>
	</div>
  <div id="adm-vehiculosoff">
  <button type="button" class="btn btn-primary" onclick="cargarAdminvehiculos('Buscar Veh&iacute;culo','adminvehiculos.php');">
  <img src="images/obj/busqueda.png" class="img-thumbnail" />
  </button>
  </div>
<div id="adm-vehiculos" style="display:none; height: 75%">
<div style="height: 100%; width: 100%; overflow-y: auto;  overflow-x: hidden;" >
<p id="adm-vehiculos-titulo"></p>
<p id="adm-vehiculos-contenido"></p>
</div><center>
<button type="button" class="btn btn-primary" onclick="ocultarAdminvehiculos()">Cerrar Lista de Vehiculos</button>
</div><br></center>
  <div class="progress" id="preloader">
  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="CARGANDO" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
  </div>

</div>
    <div class="container">
	  
	  <!--  PRECARGA  -->
	  <div id="carga" class="modal fade"> 
		<div class="modal-dialog modal-sx">
			<div class="modal-content">
 			  <div class="modal-body">
 			    <div class="precarga"></div>
 			  </div>
			</div>
		</div>
	  </div>
	 <!--  FIN PRECARGA  -->
	  
	  
	  <!--  VENTANA MODAL 1  -->
	  <div id="vntModal" class="modal fade"> 
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="vntTitulo" style="text-align:center;"></h4>
				</div>
				<div class="modal-body" id="vntContenido" ></div>
			</div>
		</div>
	  </div>
	  <!--  FIN VENTANA MODAL 1  -->
	  
	  	  <!--  VENTANA MODAL 2  -->
	  <div id="vntModal2" class="modal fade" style="padding-top:50px;"> 
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="vntTitulo2" style="text-align:center;"></h4>
				</div>
				<div class="modal-body" id="vntContenido2"></div>
			</div>
		</div>
	  </div>
	  <!--  FIN VENTANA MODAL 2  -->

	  
	   <div class="row" id="menu-top2" style="display:none;"><button type="button" class="btn btn-default btn-primary" onclick="mostrarMenu(2)">Mostrar Menu</button></div>
	  <div class="row" id="menu-top">
   <!--  Fila 1 Menu -->
     <div class="col-md-12">
      <form class="form-horizontal" role="form">
         <nav class="navbar navbar-static-top" role="navigation">
         <a class="navbar-brand btn-primary" href="#" onclick="mostrarMenu(1)">Ocultar Menu</a>
                       <div class="container-fluid"><!-- Responsive -->
            <div class="navbar-right">

               <ul class="nav navbar-nav" id="tooltips">
               
                  <li>
                     <button type="button" class="btn btn-primary" onclick="cargarPagina('Buscar Veh&iacute;culo','lvehiculos.php');">
                       <img src="images/obj/busqueda.png" class="img-thumbnail" />
                     </button>
                  </li>
                  
                  <li class="dropdown mensajes-menu" data-toggle="tooltip" data-original-title="Notificaciones del Sistema">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                     <i class="fa fa-envelope"></i>&nbsp;Notificaciones
                     <?php
			
			//$Sql = "SELECT * FROM notificaciones WHERE (idusuario = ".$Id." AND noleido = 1)";
			$Sql = "SELECT COUNT(*) total, sum(CASE WHEN noleido = 1 THEN 1 ELSE 0 end) sinleer FROM `notificaciones` WHERE id = ".$Id." LIMIT 1";
			$query = $dbh->prepare($Sql); $query->execute();
			
			 $totalNoLeidos =0;
			 $totalMensajes = 0;
			 
			//foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $fila) {
		        $fila = $query->fetchAll(PDO::FETCH_ASSOC);
				 if (isset($fila[0])) {
				  $totalNoLeidos = $fila[0]['sinleer'];
				  $totalMensajes = $fila[0]['total'];
				 }
		         
				 //echo "<script>console.log('".$totalNoLeidos.", ".$totalMensajes.".  ".$Sql."');</script>";
			//}
						if  (isset($totalNoLeidos) && $totalNoLeidos > 0) {
					 ?>
                       <span class="label label-success" id="notificaciones" style="width:20px;">
                         <?php echo $totalNoLeidos; ?>
                       </span>
                      <?php } ?>
                      
                     </a>
                     <ul class="dropdown-menu">
			<?php
			
			/*
			$Sql = "SELECT * FROM notificaciones WHERE idusuario = ".$Id;
			
			$query = $dbh->prepare($Sql); $query->execute();
			$totalMensajes = $query->rowCount();
			
		  	   foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $filas) {
		  	      echo "<li style=\"background-color:#FFF;\">";
                              echo "<a href=\"#\" style=\"text-align:center;\">".$filas['titulo']."</a></li>";
		  	   }
			*/
						if (isset($totalMensajes) && $totalMensajes > 0) {			
			?>
                        <li class="footer" style="background-color:#FFF;">
                           <a href="#" style="text-align:center;" onclick="cargarPagina('Notificaciones','notificaciones.php');">Ver Todos los Mensajes</a>
                        </li>
                        <?php } else { ?>
                        <li class="footer" style="background-color:#FFF;">
                          <a href="#" style="text-align:center;">Sin Mensajes</a>
                        </li>
                        <?php } ?>
                     </ul>
                  </li>
                  <li class="dropdown estadisticas-menu" data-toggle="tooltip" data-original-title="Reportes y Estad&iacute;sticas">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                     <i class="fa fa-pie-chart" style="color:#ddc0fc"></i>&nbsp;Reportes
                     </a>
                     <ul class="dropdown-menu">
                        <li style="background-color:#FFF;">
                           <a href="#">
                           <span class="fa fa-bar-chart" style="color:#c3d4f3;">&nbsp;</span>Reporte de Vehiculos
                           </a>
                        </li>
                     </ul>
                  </li>
                  <li class="dropdown configuracion-menu" data-toggle="tooltip" data-original-title="Configuraci&oacute;n del Sistema">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                     <i class="fa fa-book" style="color:#f7e1ab"></i>&nbsp;Registros
                     </a>
                     <ul class="dropdown-menu" style="text-align:justify;">
					   <?php if ($Nivel == 1) { ?>
						<li class="dropdown-submenu">
						  <a href="#">Gesti&oacute;n GPS</a>
							<ul class="dropdown-menu">
							  <li><a href="#">
							    <span class="fa fa-crosshairs" style="color:#4779b1;">
							      &nbsp;&nbsp;</span>Listado GPS</a>
							  </li>
							  <li><a href="#" onclick="cargarPagina('Registro GPS','rgps.php');">
							    <span class="fa fa-ioxhost" style="color:#4779b1;">
							     &nbsp;&nbsp;</span>Registro GPS</a>
							  </li>
							  <li><a href="#" onclick="cargarPagina('Comandos GPS','comandosgps.php');">
							    <span class="fa fa-twitch" style="color:#4779b1;">
							     &nbsp;&nbsp;</span>Comandos GPS</a>
							  </li>
							</ul>
						</li>
					   <?php } 
        			if ($Nivel == 1 || $Tipo == 1) {
					  ?>
						<li class="dropdown-submenu">
						  <a href="#">Gesti&oacute;n Usuarios</a>
							<ul class="dropdown-menu">
								<li><a href="#" onclick="cargarListado('Listado de Usuarios','lusuarios.php');">
				<span class="fa fa-list" style="color:#4779b1;">&nbsp;&nbsp;</span>Listado Usuarios</a></li>
								<li><a href="#" onclick="cargarPagina('Registro de Usuarios','rusuarios.php');"><span class="fa fa-user" style="color:#a4ea5f;">&nbsp;&nbsp;</span>Registro Usuarios</a></li>
							</ul>
						</li>
						<?php } ?>
						<li class="dropdown-submenu">
						  <a href="#">Control Vehiculos</a>
							<ul class="dropdown-menu">
							  <li>
							   <a href="#"><span class="fa fa-truck" style="color:#975fea;">&nbsp;&nbsp;</span>Listado Vehiculos</a>
							  </li>
					
	<?php  
	  if ($Nivel == 1 || $Tipo == 1) {
	?>
							  <li>
							   <a href="#" onclick="cargarPagina('Registro de Vehiculos','rusuarios.php');">
							   <span class="fa fa-automobile" style="color:#a4ea5f;">
							   &nbsp;&nbsp;</span>Registro Vehiculos</a>
							  </li>
        <?php } ?>
							</ul>
						</li>
						<?php if ($Nivel == 1) { ?>
						<li style="background-color:#FFF;">
                         <a href="#"><span class="fa fa-legal" style="color:#c7a693;">&nbsp;&nbsp;</span>Auditoria</a>
                        </li>						
                        <li style="background-color:#FFF;">
                           <a href="#"><span class="fa fa-gear" style="color:#bebebe;">&nbsp;&nbsp;</span>Configuraci&oacuten</a>
                        </li>
					    <?php } ?>
                     </ul>
                  </li>
                  <li class="dropdown usuario-menu">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                     <i class="fa fa-user"></i>
                     <span>
                     <?php echo $Usuario; ?>
                     <i class="caret"></i>
                     </span>
                     </a>
                     <ul class="dropdown-menu">
                        <!-- Imagen Usuario -->
                        <li class="user-header bg-light-blue">
                           <img src="<?php echo $Foto;  ?>" class="img-circle" alt="User Image" />
                           <p><br/>
						   <?php 
						    echo $Usuario ." - ";
							switch($Nivel) {
							   case 1: echo "Admin"; break;
							   case 2: echo "Dist."; break;
							   case 3: echo "P.Vta"; break;
							   case 4: echo "Inst."; break;
							   case 5: echo "Cliente"; break;
							}
						   ?>
							
						    <br/><small>Ingreso <?php echo date("d/m/Y"); ?></small></p>
                        </li>
                        <!-- Contenido Menu Usuario -->
                        <li class="user-body" style="background-color:#FFF;">
                           <div class="col-xs-8 text-justify">
                              <a href="#">Cambiar Clave</a>
                           </div>
                           <div class="col-xs-4 text-justify">
                              <a href="#" onclick="cargarPagina('GeoCercas','geocerca.php');">GeoCercas</a>
                           </div>
                        </li>
                        <!-- Footer Menu Usuario -->
                        <li class="user-footer" style="background-color:#FFF;">
                           <div class="pull-left">
                              <a href="#" class="btn btn-success btn-flat" onclick="cargarPaginaModal(' Ver Perfil','perfil.php');"><span class="fa fa-male">&nbsp;</span>Ver Perfil</a>
                           </div>
                           <div class="pull-right">
                              <a href="#" class="btn btn-warning btn-flat" onclick="cargarPaginaModal('Cerrar Sesi&oacute;n','csesion.php');"><span class="fa fa-sign-out">&nbsp;</span>Cerrar Sesi&oacute;n</a>
                           </div>
                        </li>
                     </ul>
                  </li>
               </ul>
               
              </div><!-- Responsive -->
               
            </div>
         </nav>
      </form>
   </div>
</div>
<!--  Fin Fila 1  -->
	  
      <div class="row" id="page-wrap"><!--  Fila 2 Mapa -->
		<div id='mapa-canvas' class="col-md-12">
		</div>
      </div><!--  Fin Fila 2  -->
	  
    </div> <!-- /container -->
	
	
<!-- Geocercas -->

<?php

$GeocercaJSON = "";

if ($Id == 1) {
 $Sql = "SELECT * FROM vehiculos_user";	
} else {
 $Sql = "SELECT * FROM vehiculos_user WHERE id = ".$Id;	
}

$query = $dbh->prepare($Sql); $query->execute();
$GeocercaJSON .= "[";

   foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $filas) {
     $Sql2 = "SELECT * FROM geocerca WHERE (idvehiculo = ".$filas['id'].")";
     $query2 = $dbh->prepare($Sql2); $query2->execute();
     $totalFilas = $query2->rowCount();
     $contRow = 0;
 	foreach ($query2->fetchAll(PDO::FETCH_ASSOC) as $filas2) {
 	  $GeocercaJSON .= "{\"vehiculo\":".$filas['id'].",\"tipogeocerca\":".$filas2['tipogeocerca'].", \"diametro\":".$filas2['diametro'].",\"datos\":[\"".$filas2['datos']."\"]}"; 
 	   	  $contRow++;             
 	  if ($contRow < $totalFilas) {
 	    $GeocercaJSON .= ",";
 	  }
 	}
   }
 
$GeocercaJSON .= "]";

?>	
	
	<script src="js/jquery.min.js" ></script>
	<script src="js/bootstrap.min.js" ></script>
	<script src="js/bootstrap-hover.min.js" ></script>
	<script src="js/bootstrap-submenu.min.js" ></script>
	<script src="js/jquery.maskedinput.min.js" ></script>
	<script src="js/livevalidation_standalone.js" ></script>
	<script src="js/funciones.js" ></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvlGdcbggXcDgfmclaTb8g12ewFvIuWas&v=3&libraries=drawing"></script>
	<script src="js/markerclusterer.js" ></script>
        <script src="componentes/DataTable/js/jquery.dataTables.js" ></script>
	<script src="componentes/DataTable/js/TableTools.min.js" ></script>
	<script>
	var mapa, data;
	var markerCluster;
    var marks = [];
    var requestArray = [], renderArray = [];
    var directionsService = new google.maps.DirectionsService();
    var ventanas = new Array();
    var vntActiva = 0;
    var infoWindow;
    
   $( document ).ready(function() {	
	
	//console.log("Geocerca: "+<?php echo $GeocercaJSON; ?>);
        
    // 16 Standard Colours for navigation polylines
    var colourArray = ['navy', 'grey', 'fuchsia', 'black', 'white', 'lime', 'maroon', 'purple', 'aqua', 'red', 'green', 'silver', 'olive', 'blue', 'yellow', 'teal'];
    
	
	$(document).ajaxStart(function(){
		$(".bgpreloader").show();
		$(".gpsload-thecube").show();
	});
	
	$(document).ajaxStop(function(){
		$(".bgpreloader").hide();
		$(".gpsload-thecube").hide();
	});
	
    // Dibuja una geocerca
    function dibujarGeocerca(tipo, datos, diametro) {
      var num = Math.floor((Math.random() * 15)); 
       //var coord = datos[0];
      //console.log("Tipo: "+tipo+"  Datos: "+datos[0]+"  diametro: "+diametro);
	// 0 = circulo, 1 = rectangulo, 2 = poligono
	if (tipo == 0) {
	  var coord = datos[0].split(",");
	  var cityCircle = new google.maps.Circle({
      	   strokeColor: colourArray[num],
      	   strokeOpacity: 0.8,
      	   strokeWeight: 2,
      	   fillColor: colourArray[num],
      	   fillOpacity: 0.35,
      	   map: mapa,
      	   center: new google.maps.LatLng(parseFloat(coord[0]),parseFloat(coord[1])),
      	   radius: diametro
    	  });
	} else if (tipo == 1) {
	  var coord = datos[0].split(",");
	   var rectangle = new google.maps.Rectangle({
	    strokeColor: colourArray[num],
	    strokeOpacity: 0.8,
	    strokeWeight: 2,
	    fillColor: colourArray[num],
	    fillOpacity: 0.35,
	    map: mapa,
	    bounds: {
	      north: parseFloat(coord[0]),
	      east: parseFloat(coord[1]),
	      south: parseFloat(coord[2]),
	      west: parseFloat(coord[3])
	    }
	  });

       	} else if (tipo == 2) {
       	//var path = newShape.getPaths().getAt(datos);
       	/*var path = jQuery.parseJSON(datos);
	   console.log(path);
            // Construct the polygon.
  	    var pol = new google.maps.Polygon({
    	     paths: datos,
    	     strokeColor: colourArray[num],
    	     strokeOpacity: 0.8,
    	     strokeWeight: 2,
    	     fillColor: colourArray[num],
    	     fillOpacity: 0.35
  	    });
  	  pol.setMap(mapa);*/
       	}
      
    }
    
    // Let's make an array of requests which will become individual polylines on the map.
    function generarRuta(jsonArray){
	var coord;
	var salir = false;
        requestArray = [];

        for (var route in jsonArray){
            // This now deals with one of the people / routes

            // Somewhere to store the wayoints
            var waypts = [];
            
            // 'start' and 'finish' will be the routes origin and destination
            var start, finish;
            
            // lastpoint is used to ensure that duplicate waypoints are stripped
            var lastpoint;
            data = jsonArray[route];
	
            limit = data.length;
          
          if (limit <= 0){
              continue;
          }else if (limit == 2 && (data[0] == "0" && data[1] == "0")) {
              continue;
          } else {
            
            for (var waypoint = 0; waypoint < limit; waypoint++) {
                if (data[waypoint] === lastpoint){
                    // Duplicate of of the last waypoint - don't bother
                    continue;
                }
                
                // Prepare the lastpoint for the next loop
                lastpoint = data[waypoint];
		//console.log("Wayp["+waypoint+"]: "+data[waypoint]);
                // Add this to waypoint to the array for making the request
                waypts.push({
                    location: data[waypoint]//,
                    //stopover: true
                });
            }
            
            // Grab the first waypoint for the 'start' location
            start = (waypts.shift()).location;
            // Grab the last waypoint for use as a 'finish' location
            finish = waypts.pop();
            if(finish === undefined){
                // Unless there was no finish location for some reason?
                finish = start;
            } else {
                finish = finish.location;
            }
            
            coord = start.split(',');
            var inicio = new google.maps.LatLng(parseFloat(coord[0]),parseFloat(coord[1]));
                   //console.log(inicio);
                   //console.log("Inicio: "+start);
            coord = finish.split(',');
	    var fin = new google.maps.LatLng(parseFloat(coord[0]),parseFloat(coord[1]));
	           //console.log(fin);
                   //console.log("Fin: "+finish);    
		//console.log(waypts);
            // Let's create the Google Maps request object
            //if(!waypts.location){
               /*var request = {
                origin: inicio,
                destination: fin,
                travelMode: google.maps.TravelMode.DRIVING
               };*/
               /*var request = {
                origin: inicio,
                destination: fin,
                //waypoints: waypts,
                travelMode: google.maps.TravelMode.DRIVING
               };
            } else {*/
               var request = {
                origin: inicio,
                destination: fin,
                waypoints: waypts,
                travelMode: google.maps.TravelMode.DRIVING
               };
           // }
               /*var request = {
                origin: inicio,
                destination: fin,
                waypoints: waypts,
                travelMode: google.maps.TravelMode.DRIVING
               };*/
            //}
            
            // and save it in our requestArray
            requestArray.push({"route": route, "request": request});
          }// if has routes
        } // for route

        processRequests();
    }

    function processRequests(){
        // Counter to track request submission and process one at a time;
        var i = 0;

        // Used to submit the request 'i'
        function submitRequest(){
            directionsService.route(requestArray[i].request, directionResults);
        }

        // Used as callback for the above request for current 'i'
        function directionResults(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                //console.log("Direccion OK");
                // Create a unique DirectionsRenderer 'i'
                if (this.renderArray[i] == null) {
    		  renderArray[i] = new google.maps.DirectionsRenderer();
    		}
    		if (this.renderArray[i].directions) this.renderArray[i].setMap(null);
                
                renderArray[i].setMap(mapa);

                // Some unique options from the colorArray so we can see the routes
                renderArray[i].setOptions({
                    preserveViewport: true,
                    suppressInfoWindows: true,
                    polylineOptions: {
                        strokeWeight: 4,
                        strokeOpacity: 0.8,
                        strokeColor: colourArray[i]
                    },
                    markerOptions:{
                        icon:{
                            path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
			    fillOpacity:0.0,
                            scale: 2,
                            strokeColor: colourArray[i]
                        }
                    }
                });

                // Use this new renderer with the result
                renderArray[i].setDirections(result);
                // and start the next request
                nextRequest();
            }

        }

        function nextRequest(){
            // Increase the counter
            i++;
            // Make sure we are still waiting for a request
            if(i >= requestArray.length){
                // No more to do
                return;
            }
            // Submit another request
            submitRequest();
        }

        // This request is just to kick start the whole process
        submitRequest();
    }
	
	function getDireccion(marker, cont) {
	 var geocoder = new google.maps.Geocoder();
	 var dir = "";
	 geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
       	  if (status === google.maps.GeocoderStatus.OK) {
      	    dir = "<div>Direcci&oacute;n: "+results[0].formatted_address+"</div>";
    	  } else {
	    dir = "<div>Direcci&oacute;n: Desconocido</div>";
    	  }
    	  cont = cont + dir;
    	   infoWindow.setContent(cont); 
  	 });
  	 
	}
	
	
	function actualizarVehiculos(Inicio) {
	var contenido = ""; 
	var cadJson = "{";
	//var cadJson = "";
            
	
	  $.getJSON("json/coord.php", function() {}) 
	    .done(function(data) {
			//console.log(data);
		var num = 0; var imagen = ""; var lat = ""; var long = ""; var fecha = "";
		var cantCarros = data.length;
		var cantCoord = 0;
		var serial = ""; var cont = "", idVeh = "";

		//console.log("CANT: "+cantCarros+"       Data: "+JSON.stringify(data));
		 // CADA VEHICULO
		 $.each(data, function(key, val) {
      	    	   num++;
      	    	   contenido = "<div id='contentInfoWindow'></div>";
      	    	   cont = "";
		   // DATOS VEHICULOS
 		   $.each(val, function(clave, valor) {
			var j = 1;
			
 	    	    if (clave != "Coord") {
 	    	      if (clave=="Fecha") {fecha = valor;}
 	    	      if (clave == "foto") {
 	    	        imagen = valor;
 	    	      } else if (clave == "Serial") {
 	    	        serial = valor;
                        idVeh = serial;
                        //num = "'"+serial+"'";
 	    	        cadJson = cadJson + "\""+serial+"\":";
 	    	      } else {
 	    	        cont = cont + "<div>"+clave+": "+valor+"</div>";
 	    	      }
 	            } else {
 	            
 	               cantCoord = valor.length;
 	               
				   //console.log("CANCOORD: "+cantCoord+"  DATA: "+JSON.stringify(valor));
 	               if (cantCoord > 0) {
 	                 //cadJson = cadJson + "\""+serial+"\":";
 	                 contenido = contenido + cont;
 	               } else {
 	                 if (num >= cantCarros) {
 	                  //cadJson = cadJson + "\"0\":[0,0]";
 	                  cadJson = cadJson + "[0,0]";
 	                 } else {
 	                  //cadJson = cadJson + "\"0\":[0,0],";
 	                  cadJson = cadJson + "[0,0],";
 	                 }
 	                 
 	               }
 	               //console.log(cantCoord);
 	               
 	               // Obtiene coordenadas
 	               $.each(valor, function(pos, coord) {
		        // console.log("Len Pos "+pos+ "  Len Val: "+cantCarros);
		        // console.log("Carro: "+serial+ "  Len Coord: "+coord.length+" Valor:"+valor.length);
		    
		         
 	                 if (pos === 0) { 
					    if (valor.length > 1) {
					     cadJson = cadJson + "["; lat = parseFloat(coord[0]); long = parseFloat(coord[1]);cadJson = cadJson + "\"" + lat + "," + long +"\",";
						} else {
					     cadJson = cadJson + "["; 
						  lat = parseFloat(coord[0]); 
						  long = parseFloat(coord[1]);
						  cadJson = cadJson + "\"" + lat + "," + long +"\"]";							
						}
					 } else if (pos >= ((valor.length)-1)) { 
					   lat = parseFloat(coord[0]); 
					   long = parseFloat(coord[1]); 
					   cadJson = cadJson + "\"" + lat + "," + long +"\"";
 	                 //cadJson = cadJson + "\"" + lat + "," + long +"\"";
 	                 //console.log(num+": "+coord[0]+ "," + coord[1]);
 	                 
						if (num >= cantCarros) {
						  cadJson = cadJson + "]";
						} else {
						  cadJson = cadJson + "],";
						}
 	                 
					 
 	                  } else {
 	                   cadJson = cadJson + "\"" + lat + "," + long +"\",";
 	                  }
		         //if (j >= pos.length) {
		         //if (j >= cantCoord) {
		         /*if (pos >= cantCoord) {
		           if (num >= cantCarros) {
			    cadJson = cadJson + "]";
			   } else {
			    cadJson = cadJson + "],";
			   }
		         } else {
		           cadJson = cadJson + ",";
		         }*/
		         
 	                 j++;
	               }); // Coordenadas
 	            }
 	    
		   }); // Datos Vehiculos
		    
		    //contenido = contenido +  "<div>Latitud: "+lat+"</div>";
		    //contenido = contenido +  "<div>Longitud: "+long+"</div>";
		    //contenido = contenido + "<div>Direcci&oacute;n: "+
		    
		  if (cantCoord > 0) {
			if (Inicio) {
		         //ventanas[(num-1)] = null;
                         ventanas[idVeh] = null;
		         //ventanas[(num-1)] = crearMarcador("images/obj/"+imagen,lat,long,contenido, (num-1));
                         ventanas[idVeh] = crearMarcador("images/obj/"+imagen,lat,long,contenido, idVeh);
			} else {
  		          //ventanas[num].setPosition(new google.maps.LatLng(lat,long));
                          ventanas[idVeh].setPosition(new google.maps.LatLng(lat,long));
			}
		    
		    //if (ventanas[(num-1)].idMarcador == vntActiva){
                    if (ventanas[idVeh].idMarcador == vntActiva){
		      //getDireccion(ventanas[(num-1)], contenido); 
                      getDireccion(ventanas[idVeh], contenido); 
		      //mapa.panTo(new google.maps.LatLng(lat,long));
	 	      //mapa.setCenter(new google.maps.LatLng(lat,long));
		    }
		    //marks.push(ventanas[(num-1)]);
                    marks.push(ventanas[idVeh]);
		  }
         });
	    
	    cadJson = cadJson + "}";
	    //console.log(cadJson);
	    generarRuta($.parseJSON(cadJson));
		
	     if (Inicio) {
	      markerCluster = new MarkerClusterer(mapa, marks);
	     }

	  }).fail(function(jqxhr, textStatus, error) {
	     var err = textStatus + "  " + error + ". " + jqxhr;
	     console.log("Error: " + err);
	  });
	  
	    
        } // Funcion Coordenadas vehiculos
        
        
	function inicializar() {
	
         var mapOpc = {
			zoom: 12,
			mapTypeId:google.maps.MapTypeId.HYBRID,
			//mapTypeId:google.maps.MapTypeId.TERRAIN,
			center: {lat:10.2437116667, lng:-67.582555},
			panControl: false,
			scaleControl: true,
			mapTypeControl: true,
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
				position: google.maps.ControlPosition.RIGHT_BOTTOM
			},
			zoomControl: true,
			zoomControlOptions: {
				style: google.maps.MapTypeControlStyle.VERTICAL_BAR,
				position: google.maps.ControlPosition.RIGHT_CENTER
			}
			//heading: 90
		};
     

	  mapa = new google.maps.Map(document.getElementById('mapa-canvas'), mapOpc);
	  
	//mapa.setTilt(45);
	  infoWindow = new google.maps.InfoWindow();
	  cargarBarraGeocerca();
	  google.maps.event.addListener(mapa, 'click', function(){ closeInfoWindow(); });
	  var geoJson = <?php echo "'".$GeocercaJSON."'"; ?>;
	  //console.log(geoJson);
	  var jsonGeo = jQuery.parseJSON(geoJson);
	  //console.log(jsonGeo);
	  
	  //console.log(geoJson);
	   $.each(jsonGeo, function(key, val) {
	    //dibujarGeocerca(val.vehiculo,val.datos"10.252757094458845,-67.60986328125",2175.77);
	     //console.log("vehiculo: "+val.vehiculo+"Datos: "+val.datos+"tipo geocerca: "+val.tipogeocerca+"diametro: "+val.diametro);
	     dibujarGeocerca(val.tipogeocerca,val.datos,val.diametro);
	   });
	  //dibujarGeocerca(0,"10.252757094458845,-67.60986328125",2175.77);
	  //dibujarGeocerca(1,"10.284850325987035,-67.56248474121094,10.262892150926476,-67.63252258300781",0);
	  
	   actualizarVehiculos(true);	
	   setTimeout(actualizarVehiculos, 20000,false);  
	 // markerCluster = new MarkerClusterer(mapa, marks);
	}
	
	function closeInfoWindow() {
	 vntActiva = -1;
         infoWindow.close();
    }
        
	
	function crearMarcador(imagen, lat, lng, contenido, num){
	  var marker = new google.maps.Marker({
          position: new google.maps.LatLng(lat, lng),
          icon:imagen,
	      map: mapa,
	      idMarcador: num
          });
	  google.maps.event.addListener(marker, 'click', function(){ cargarVentana(marker, contenido); });
	  return marker;
	}
	
	function showMaxZoom(e) {
        var maxZoomService = new google.maps.MaxZoomService();
        maxZoomService.getMaxZoomAtLatLng(e.getPosition(), function(response) {
        
    	  if (response.status !== google.maps.MaxZoomStatus.OK) {
     	   //mapa.setZoom(20);
     	   mapa.setCenter(e.getPosition());
    	  } else {
           //mapa.setZoom(20);
           //mapa.setZoom(response.zoom);
           mapa.setCenter(e.getPosition());
    	  }
  	 });	
	}

	function cargarVentana(marker, contenido) {
        var markerLatLng = marker.getPosition();
	 vntActiva = marker.idMarcador;
          infoWindow.setContent('');
         //console.log("POS MARKER: "+markerLatLng);
         mapa.panTo(markerLatLng);
         showMaxZoom(marker);
         //mapa.setZoom(20);
	 //mapa.setCenter(markerLatLng);
         infoWindow.setContent([
            contenido
         ].join(''));
         infoWindow.open(mapa, marker);
       }

       

	google.maps.event.addDomListener(window, 'load', inicializar);
	
	
      });
      
      function mostrarMenu(e) {
     if(e === 1){
    
     $( "#menu-top").css( "display", "none" );
    
   
     $( "#menu-top2").css( "display", "" );
     
     $( "#mapa-canvas").css( "top", "0px" );
     $( "#mapa-canvas").css( "height", "100%" );
     //console.log("se oculto el menu 1");
     }else{
      $( "#menu-top").css( "display", "" );
      $( "#menu-top2").css( "display", "none" );
      $( "#mapa-canvas").css( "top", "9%" );
      $( "#mapa-canvas").css( "height", "91%" );
        // console.log("se oculto el menu 2");
     }
         
       }
</script>

  </body>
</html>