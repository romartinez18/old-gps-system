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

if (isset($_SESSION['id'])) { $IdUser = base64_decode($_SESSION['id']); } else { $IdUser = 0; }

if ($IdUser < 1 || $IdUser > 1) { 
?>
<script>
window.location="index.php";
</script>
<?php
}

  include_once('class/class.config.php');
  require_once('class/class.rb.php');
  R::setup('mysql:host='.SERVIDOR.';dbname='.DATABASE, USUARIOBD, CLAVEBD);

  $lNoIP  = R::findOne('noip', ' Nombre = ? ', [ 'Servidor GPS' ] );
  
    if (isset($lNoIP) && $lNoIP != null) {
		//var_dump($lNoIP);
	  foreach($lNoIP as $k => $v){
		  if($k == "IP"){
			  $IPServer = $v;
		  }elseif($k == "PUERTO"){
			  $PUERTOServer = $v;
		  }
		  //echo "[".$lNoIP->$k."][".$k."](".json_encode($v).")_".gettype($k)."_<br/>";
	  }
	  //echo "ID: ".$lNoIP->id."  IPServer: ".$IPServer."  PUERTOServer: ".$PUERTOServer; 
	  
	} else {
	  $IPServer = "";
      $PUERTOServer = "";
	}
  
  
  
  
  //print_r($lNoIP);
  
  define('SELECTED_CLASS','listado');
  //define('SELECTED_CLASS','listado_vehiculos');
  define('SINGLE_SELECTED_CLASS','listado');
  //define('SINGLE_SELECTED_CLASS','listado_vehiculos');
  define('UNIQUE_BD_FIELD','iduser');

  $camposTitulo = array('Id User', 'Id Veh', 'Id GPS', 'Nick', 'Ced.', 'Nombre', 'Apellido', 'Serial', 'Cel GPS', 'Email', 'Cel User', 'Auth', 'Activo', 'Fecha Reg.', 'Placa', 'Descrip', 'Tipo', 'Act. GPS', 'Dia Corte', 'Fecha Reg. GPS');
  $camposBD = array('iduser', 'idveh', 'idgps', 'nick', 'cedula', 'name', 'lastname', 'serial', 'celgps', 'mail', 'cel', 'auth', 'activo', 'registro', 'placa', 'descrip', 'tipo', 'activogps', 'diacorte', 'registrogps');
  
  /*
  array('iduser', 'idveh', 'idgps', 'cedula', 'name', 'lastname', 'serial', 'celgps', 'mail', 'cel', 'auth', 'activo', 'registro', 'placa', 'descrip', 'tipo', 'activogps', 'diacorte', 'registrogps');
  */  
  
  $formCampos = array(array('iduser' => 'Id User', 'tipo' => 'number', 'validation' => 
								array('requerido' => 0, 'min_range' => '1000', 'max_range' => '99999999')), 
					  array('idveh' => 'Id Veh', 'tipo' => 'number', 'validation' => 
								array('requerido' => 0, 'min_range' => '1000', 'max_range' => '99999999')), 
					  array('idgps' => 'Id GPS', 'tipo' => 'number', 'validation' => 
								array('requerido' => 0, 'min_range' => '1000', 'max_range' => '99999999')), 
					  array('nick' => 'Nick', 'tipo' => 'text', 'validation' => 
								array('requerido' => 1, 'longitud' => '20')), 
					  array('cedula' => 'Ced.', 'tipo' => 'number', 'validation' => 
								array('requerido' => 0, 'min_range' => '1000', 'max_range' => '99999999')), 
					  array('name' => 'Nombre', 'tipo' => 'text', 'validation' => 
								array('requerido' => 1, 'longitud' => '250')), 
					  array('lastname' => 'Apellido', 'tipo' => 'text', 'validation' =>  
								array('requerido' => 0, 'longitud' => '250')), 
					  array('serial' => 'Serial', 'tipo' => 'text', 'validation' =>  
								array('requerido' => 0, 'longitud' => '20')),
					  array('celgps' => 'Cel GPS', 'tipo' => 'text', 'validation' =>  
								array('requerido' => 1, 'longitud' => '15')),								
					  array('mail' => 'Email', 'tipo' => 'email', 'validation' =>  
								array('requerido' => 0, 'longitud' => '150')),
					  array('cel' => 'Cel User', 'tipo' => 'text', 'validation' =>  
								array('requerido' => 0, 'longitud' => '15')),
					  array('auth' => 'Auth', 'tipo' => 'text', 'validation' =>  
								array('requerido' => 1, 'longitud' => '2')),
					  array('activo' => 'Activo', 'tipo' => 'text', 'validation' =>  
								array('requerido' => 1, 'longitud' => '1')),
					  array('registro' => 'Fecha Reg.', 'tipo' => 'date', 'validation' =>  
								array('requerido' => 0)),
					  array('placa' => 'Placa', 'tipo' => 'text', 'validation' =>  
								array('requerido' => 0)),
					  array('descrip' => 'Descrip', 'tipo' => 'text', 'validation' =>  
								array('requerido' => 0, 'longitud' => '255')),
					  array('tipo' => 'Tipo', 'tipo' => 'text', 'validation' =>  
								array('requerido' => 0, 'longitud' => '150')),
					  array('activogps' => 'Act. GPS', 'tipo' => 'number', 'validation' =>  
								array('requerido' => 0, 'min_range' => '0', 'max_range' => '1')),
					  array('diacorte' => 'Dia Corte', 'tipo' => 'text', 'validation' =>  
								array('requerido' => 0, 'longitud' => '2')),
					  array('registrogps' => 'Fecha Reg. GPS', 'tipo' => 'date', 'validation' =>  
								array('requerido' => 0))
					 );

?>
<!doctype html>
<html lang="es" dir="ltr">
  <head>
    <title><?php echo ucwords(SINGLE_SELECTED_CLASS); ?> General</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1000, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <link rel="stylesheet" href="css/fuente.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/admestilos.css">
    <script charset="utf-8" src="js/jquery.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvlGdcbggXcDgfmclaTb8g12ewFvIuWas&v=3&libraries=drawing"></script>
    <script charset="utf-8" src="js/jquery.dataTables.js"></script>
    <script charset="utf-8" src="js/jquery.validate.min.js"></script>
	<script charset="utf-8" src="js/bootstrap.min.js"></script>
    <script charset="utf-8" src="js/appCRUD.js"></script>
  <style>
  table.datatable th, table.datatable td {
	padding: 1px 1px 1px 1px;
  }
  #page_container {
	  width:100%;
  }
    label {
	  font-weight:normal;
	}
#mapaModalVeh {
  width:500px;
  height:480px;
}
  </style>
    <script>
	 /* var sent = "[{}]";
	  var notsent = "[{}]";
	  var outdate = "[{}]";*/
	  var sent = "";
	  var notsent = "";
	  var outdate = "";
	  var mapModal, mapModalOpc;
      var marker;
	  var latMap = 0;
	  var longMap = 0;
	  
	$(document).ajaxStart(function(){
		$(".bgpreloader").show();
		$(".gpsload-thecube").show();
	});
	
	$(document).ajaxStop(function(){
		$(".bgpreloader").hide();
		$(".gpsload-thecube").hide();
	});
	
	function loadMap(lat, log) {
		/*latMap = lat;
		longMap = log;*/
		var latlng = new google.maps.LatLng(lat, log);
		marker.setPosition(latlng);
		mapModal.setCenter(latlng);
	}
    </script>
  </head>
  <body onload="checkCommandGPS();">
  
    <div class="bgpreloader"></div>
	<div class="gpsload-thecube">
		<div class="gpsload-cube gpsload-c1"></div>
		<div class="gpsload-cube gpsload-c2"></div>
		<div class="gpsload-cube gpsload-c4"></div>
		<div class="gpsload-cube gpsload-c3"></div>
	</div>

    <div id="page_container">

      <h1>CRUD <?php echo ucwords(SINGLE_SELECTED_CLASS); ?></h1>
	  
      <button type="button" class="button" id="sendsms_<?php echo SINGLE_SELECTED_CLASS; ?>">
	    Enviar SMS
	  </button>
	  
	  <div class="col-md-4"><a href="../gps1/admin.php" class="btn btn-lg btn-info"><b>Ir al Menú</b></a></div>
	  
	  <?php
	    		
		include_once('class/class.conexion.php');
		
		$totalSent = 0;
		$totalNotSent = 0;
		$totalOutdate = 0;
		$dbh = Conexion::singleton_conexion();
		$sql = "SELECT idgps, descrip as idveh, iduser, celgps, serial from listado_vehiculos WHERE (activo = 1 AND activogps = 1) order by idgps DESC";
	    $query = $dbh->prepare($sql);
		$query->execute();
		$filas = $query->fetchAll(PDO::FETCH_ASSOC);
		
		 $tableSent = "";
		 $tableNotSent = "";
		 $tableOutdate = "";
		 
		 $tableTest = "";
		 $footTable = "";
		 $headerTable = "<table class='table-striped table-responsive'><thead><tr>";
		 $footTable = "<tfoot><tr>";
		  // add the table headers
		  foreach ($filas[0] as $key => $useless){
			$headerTable .= "<th><center>".strtoupper($key)."</center></th>";
			$footTable .= "<th><center>".strtoupper($key)."</center></th>";
		  }
		  
		 $headerTable .= "<th><center>SMS</center></th>";
		 $headerTable .= "<th><center>ULTIMA TRANS.</center></th>";
		 $headerTable .= "<th><center>DIAS</center></th>";
		 $headerTable .= "<th><center>MAPA</center></th>";
		 $headerTable .= "<th><center>LAT</center></th>";
		 $headerTable .= "<th><center>LNG</center></th>";
		 $headerTable .= "<th><center>VEL</center></th>";
		$headerTable .= "</tr></thead>";
		
		// TFOOT
		 $footTable .= "<th><center></center></th>";
		 $footTable .= "<th><center>ULTIMA TRANS.</center></th>";
		 $footTable .= "<th><center>DIAS</center></th>";
		 $footTable .= "<th><center></center></th>";
		 $footTable .= "<th><center>LAT</center></th>";
		 $footTable .= "<th><center>LNG</center></th>";
		 $footTable .= "<th><center>VEL</center></th>";
		$footTable .= "</tr></tfoot><tbody>";
		 
		 $headerTable .= $footTable;
		 
		 $lVehGPS  = array();
		  
		  $sql = "SELECT idvehiculo, fechahoragps as fecha, latitud as lat, longitud as lng, velocidad as vel FROM tramagps WHERE (idvehiculo, id) in (select idvehiculo, max(id) from tramagps group by idvehiculo)";
		  
			$query2 = $dbh->prepare($sql);
			$query2->execute();
			$filas2 = $query2->fetchAll(PDO::FETCH_ASSOC);
			
			foreach($filas2 as $f2) {
			  $idv = trim($f2['idvehiculo']);
			    if (isset($idv) && !empty($idv)) {
				  $lVehGPS[$idv] = array('fecha' => $f2['fecha'], 'lat' => $f2['lat'], 'lng' => $f2['lng'], 'vel' => $f2['vel']);
				}
			}
		
		foreach($filas as $f) {
		 $iconBtn = "<button type='button' class='btn btn-info btnSMS' data-id='".$f['celgps']."' data-toggle='modal' data-target='#modalSMS'>";
		 $iconBtn .= "<i class='fa fa-wrench'></i></button>";
	  
		 $tableTmp = "";
		 $foundTrans = false;
		 $lat = "-";
		 $lng = "-";
		 $fgps = "-";
		 $vel = "-";
		 $serial = trim($f['serial']);
		 $iconLink = "-";
		 $dias = "-";
		  
		    if (isset($lVehGPS[$serial]) && !empty($lVehGPS[$serial])) {
				$fgps = $lVehGPS[$serial]['fecha'];
				$lng = number_format($lVehGPS[$serial]['lng'],5);
				$lat = number_format($lVehGPS[$serial]['lat'],5);
				$vel = $lVehGPS[$serial]['vel'];
				//$iconLink = "<a href='https://www.google.co.ve/maps/@".$lat.",".$lng.",15z' target='_blank'><i class='fa fa-map-marker' aria-hidden='true'></i></a>";
				//$iconLink = "<button type='button' class='btn btn-success'>";
				//$iconLink .= "<i class='fa fa-map-marker'></i></button>";
				$iconLink = "<button type='button' class='btn btn-success' id='btnMap' onclick='loadMap(".$lat.",".$lng.");' data-id='".$lat.",".$lng."' data-toggle='modal' data-target='#modalMap'>";
				$iconLink .= "<i class='fa fa-map-marker'></i></button>";
				$foundTrans = true;
			}
		  
		     if ($foundTrans) {
			   $date1 = date('Y-m-d');
				$date2 = new DateTime($fgps);
				$date2 = $date2->format('Y-m-d');
				 
				$segundos=strtotime($date1) - strtotime($date2);
				$dias = intval($segundos/60/60/24);
			 }
			

				   
		 /*$iconBtn = "<button type='button' class='btn btn-success' data-toggle='modal' data-target='#modalSMS'>";
		 $iconBtn .= "<i class='fa fa-wrench'></i></button>";*/

		 /*$iconLink = "<button type='button' class='btn btn-success'>";
		 $iconLink .= "<i class='fa fa-map-marker'></i></button>";*/
		 
		  $tableTmp = "<tr>";
			$tableTmp .= "<td><center>".$f['idgps']."</center></td>";
			$tableTmp .= "<td><center>".$f['idveh']."</center></td>";
			$tableTmp .= "<td><center>".$f['iduser']."</center></td>";
			$tableTmp .= "<td><center>".$f['celgps']."</center></td>";
			$tableTmp .= "<td><center>".$f['serial']."</center></td>";
			
			$tableTmp .= "<td><center>".$iconBtn."</center></td>";
			$tableTmp .= "<td><center>".$fgps."</center></td>";
			$tableTmp .= "<td><center>".$dias."</center></td>";
			$tableTmp .= "<td><center>".$iconLink."</center></td>";
			
			$tableTmp .= "<td><center>".$lat."</center></td>";
			$tableTmp .= "<td><center>".$lng."</center></td>";
			$tableTmp .= "<td><center>".$vel."</center></td>";
			
		   $tableTmp .= "</tr>";
			$tableTest .= $tableTmp;
			
			  if (!$foundTrans) {
				$tableNotSent .= $tableTmp;
				$totalNotSent++;
			  } else {
				  if ($dias <= 0) {
					$totalSent++;
					$tableSent .= $tableTmp; 
				  } else {
					 $totalOutdate++;
					 $tableOutdate .= $tableTmp;
				  }
			  }
		}
		
		$tableTest = $headerTable.$tableTest."</tbody></table><br/>";
		//echo $tableTest;
		
		
		$tableSent = $headerTable.$tableSent."</tbody></table><br/>";
		//echo $tableSent;
		$tableNotSent = $headerTable.$tableNotSent."</tbody></table><br/>";
		//echo $tableNotSent;
		$tableOutdate = $headerTable.$tableOutdate."</tbody></table><br/>";
		//echo $tableOutdate;
		
		echo '<script>  sent = "'.$tableSent.'"; notsent = "'.$tableNotSent.'"; outdate = "'.$tableOutdate.'"; </script>';
		 
	  
	  ?>
	  
<script>
 var ipserver = '<?php echo $IPServer; ?>';
 var puertoserver = '<?php echo $PUERTOServer; ?>';
 var enviandosms = false;
 
 function checkCommandGPS() {
	 var selcmd = $('#selGPScmd').find(":selected").val(); //$('#selGPScmd').val();
	 var smsgps = "";//$("#smstosend").val();
	 var celgpscmd = $("#smscelgps").val();
	 
	 selcmd = parseInt(selcmd, 10); 
	 
	 $("#extrasms").hide();
	 $("#extrasms").val("");
	 
	 console.log("Seleccionado: "+selcmd);
	 
	   switch(selcmd) {
            
			// IP SERVIDOR
			case (1):
              smsgps = "adminip123456 "+ipserver+" "+puertoserver;
            break;
			
			// CHECK
			case (2):
              smsgps = "check123456";
            break;
			
			// RESET
			case (3):
              smsgps = "reset123456";
            break;
			
			// IMEI
			case (4):
              smsgps = "imei123456";
            break;
			
			// TIMEZONE
			case (5):
              smsgps = "timezone123456 {NUM_TIMEZONE}";
			  $("#extrasms").show();
            break;
			
			// APN
			case (6):
              smsgps = "apn123456 {CONFIG_GPRS}";
			  $("#extrasms").show();
            break;
			
			// GPRS
			case (7):
              smsgps = "gprs123456";
            break;
			
			// LESS GPRS
			case (8):
              smsgps = "less gprs123456 on";
            break;
			
			// FIX
			case (9):
              smsgps = "fix030s{CMD_FIX}n123456";
			  $("#extrasms").show();
            break;
			
			// CUSTOM
			case (10):
              smsgps = "CUSTOM";
			  $("#extrasms").show();
            break;
        }
		
	console.log("Enviar SMS: "+smsgps+"  TO: "+celgpscmd);
	
	$("#smssend").html(smsgps);
	$("#smstosend").val(smsgps);
 }

  function changeSMS() {
	var selcmd = $('#selGPScmd').find(":selected").val(); //$('#selGPScmd').val();
	var smsgps = "";
	var valtxt = $('#extrasms').val();
	 
	selcmd = parseInt(selcmd, 10); 
	
	 switch(selcmd) {
			
			// TIMEZONE
			case (5):
			  if (valtxt == '') {
			    smsgps = "timezone123456 {NUM_TIMEZONE}";
			  } else {
                smsgps = "timezone123456 "+valtxt;
			  }
            break;
			
			// APN
			case (6):
			  if (valtxt == '') {
			    smsgps = "apn123456 {CONFIG_GPRS}";
			  } else {
                smsgps = "apn123456 "+valtxt;
			  }
            break;
			
			// FIX
			case (9):
			  if (valtxt == '') {
			    smsgps = "fix030s{CMD_FIX}n123456";
			  } else {
			   smsgps = "fix030s"+valtxt+"n123456";  
			  }
            break;
			
			// CUSTOM
			case (10):
              smsgps = valtxt;
            break;
        }
	 
	$('#smssend').html(smsgps);  
	$('#smstosend').val(smsgps);
  }
 
 function enviarSMS() {
	   if (enviandosms){return;}else{enviandosms=true;}
	   
	//var cel = $('#smscelgps').html();  
	var cel = $('#smscelgps').val();  
	var sms = $('#smstosend').val().trim();
	var enviar = false;
	
	  //cel = parseInt(cel, 10); 
	  
	  if (!cel || 0 === cel.length) {
		 enviar = false;
		 alert('No se ha encontrado el numero de celular del GPS');
	  } else if (!$.isNumeric(cel)) {
		 enviar = false;
		 alert('El numero de celular del GPS es incorrecto');
	  } else if (!sms || 0 === sms.length) {
		 enviar = false; 
		 alert('Se selecciona el comando a enviar al GPS');
	  } else {
		 if (confirm("Esta seguro de que desea enviar el comando?")) {
		   enviar = true; 
		 }
	  }
	  
	 
   if (enviar) {
	   var json = '[{"telefono": "'+cel+'","mensaje": "'+sms+'"}]';
	   
	   $.ajax({
		  url : 'sms/salida.php',
		  data : { contenidoMensaje : json},
		  type : 'POST',
		  dataType : 'json'
		}).done(function(json) {
			//json = JSON.parse(json);
			console.log("-"+json[0].Codigo+"-");
			
			if (json[0].Codigo == 0) {
			  alert('Mensaje Enviado Correctamente');
			} else {
			  alert('Error al enviar el Comando. '+json[0].Mensaje);
			}
		  console.log(JSON.stringify(json));
		  enviandosms = false;
		}).fail(function(xhr, status) {
		  console.log('Hubo un error al enviar el mensaje. '+status+'  '+xhr.responseText);
		  enviandosms = false;
		});
		
	 /*$.ajax({
		url : 'sms/salida.php',
		data : { contenidoMensaje : json},
		type : 'POST',
		dataType : 'json',
		
		done : function(json) {
          //alert('Mensaje Enviado Correctamente');
		  console.log(json_encode(json));
		  enviandosms = false;
		},
		
		error : function(xhr, status) {
          console.log('Hubo un error al enviar el mensaje. '+status+'  '+xhr.responseText);
		  enviandosms = false;
		}
	 });	*/ 
		 
   }else{
	 enviandosms = false;
   }
   
 }
 
</script>
	  <!-- Estatus Vehiculos -->
	  <center>
      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalOpt" id="tramanosent">
	    Non-Trama&nbsp;&nbsp;<span class="badge"><?php echo $totalNotSent; ?></span>
	  </button>
      <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalOpt" id="tramaoutdate">
	    Trama Outdate&nbsp;&nbsp;<span class="badge"><?php echo $totalOutdate; ?></span>
	  </button>
      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalOpt" id="tramasend">
	    Trama Received&nbsp;&nbsp;<span class="badge"><?php echo $totalSent; ?></span>
	  </button>
	  </center>
	  <!-- Fin Estatus Vehiculos -->
	  
	  <!-- modal options -->
<div class="modal fade" id="modalOpt" tabindex="-1"  role="dialog" aria-labelledby="modalVeh">
  <div class="modal-dialog modal-lg" style="width:100%;" role="document">
    <div class="modal-content" style="width:100%;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modallblVeh"><center><div id="titleModal"></div></center></h4>
      </div>
      <div class="modal-body">
         <center><div id="vehContent" style="width: 100%;height: auto;margin: 0.6em;"></div></center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- modal options -->
<div class="modal fade" id="modalSMS" tabindex="-1"  role="dialog" aria-labelledby="modalSMS">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modallblSMS"><center>Comandos GPS</center></h4>
      </div>
      <div class="modal-body">
	    <form class="form-inline">
		   
		     <input type="hidden" name="smstosend" id="smstosend" />
			 
			<div class="form-group">
			  <label for="ippuerto"><b>IP - Puerto del Servidor: &nbsp;&nbsp;&nbsp;</b></label>
				 <label id="ippuerto"><?php echo $IPServer.":".$PUERTOServer; ?></label>
		    </div><br/>
			
			<div class="form-group">
			  <label for="smscelgps"><b>CELULAR GPS: &nbsp;&nbsp;&nbsp;</b></label>
				 <!--<label id="smscelgps"></label>-->
				 <input type="text" class="form-control" maxlength="11" name="smscelgps" id="smscelgps" />
		    </div><br/>
			
			<div class="form-group">
			  <label for="selGPScmd"><b>COMANDOS GPS: &nbsp;&nbsp;&nbsp;</b></label>
			  
			   <select class="form-control" id="selGPScmd" name="selGPScmd" onchange="checkCommandGPS();">
			     <option value="1">IP Servidor</option>
			     <option value="2">Check</option>
			     <option value="3">Reset</option>
			     <option value="4">IMEI</option>
				 
				 <option value="5">Zona Horaria</option>
				 <option value="6">APN</option>
				 <option value="7">GPRS</option>
				 <option value="8">Less GPRS</option>
				 <option value="9">Fix</option>
				 
				 <option value="10">Personalizado</option>
			   </select>
			   
			   <input type="text" class="form-control" maxlength="320" name="extrasms" id="extrasms" onblur="changeSMS();" />
			   
		    </div><br/><br/><br/>
			
			<div class="form-group">
			
			  <center>
			   <label for="smssend"><b>Mensaje a Enviar: </b></label>
				 <label id="smssend"></label>
			  </center>
		    </div><br/><br/>
          <br/>
			
		 
		 <center>
		   <button type="button" class='btn btn-success' onclick="enviarSMS();">
			 <i class="fa fa-paper-plane-o"></i>ENVIAR SMS
		   </button>
		</center>
		 
		</form>
		 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div><!-- FIN MODAL SMS -->


<!-- modal MAPA -->
<div class="modal fade" id="modalMap" tabindex="-1"  role="dialog" aria-labelledby="modalMap">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width:100%;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modallblMap"><center><div id="titleModal"></div></center></h4>
      </div>
      <div class="modal-body">
         <center><div id="mapaModalVeh"></div></center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

      <table class="datatable" id="table_<?php echo SELECTED_CLASS; ?>">
        <thead>
          <tr>
		    <?php foreach($camposTitulo as $titulo) { ?>
            <th><center><?php echo $titulo; ?></center></th>
			<?php } ?>
			<th><center>Funciones</center></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>

    </div><!-- FIN CONTAINER -->

    <div class="lightbox_bg"></div>

    <div class="lightbox_container">
      <div class="lightbox_close"></div>
      <div class="lightbox_content">
        
        <h2>Agregar <?php echo ucwords(SINGLE_SELECTED_CLASS); ?></h2>

        <form role="form" class="form-horizontal form add" id="form_<?php echo SINGLE_SELECTED_CLASS; ?>" data-id="" novalidate>
			<?php 
			
			  $nameDB = '';
			  $nameTit = '';
			  $type = 'text';
			  $validation = '';
			  $lbl = '';
			  $fieldForm = '';
			  
				foreach($formCampos as $k1 => $v1){
		          foreach($v1 as $key => $value){
					
					if ($key != 'validation' && $key != 'tipo') {
						$nameDB = $key;
						$nameTit = $value;
					} else if ($key == 'tipo') {
						$type = $value;
					} else {
					  $req = false;
						
					   foreach($value as $k => $val){
				         if ($k == 'min_range') {
						   $validation .= ' min="'.$val.'"';
				         } else if ($k == 'max_range') {
					       $validation .= ' max="'.$val.'"';
				         } else if ($k == 'longitud') {
					       $validation .= ' maxlength="'.$val.'"';
				         } else if ($k == 'requerido') {
					        if ($val = 1) {$validation .= 'required'; $req = true;}
				         }
				   
					   }// foreach validaciones
						
						//echo $nameDB."   ".$req."  ".$type."<br/>";
						// label
						if ($req === true) {
						  $lbl = '<label for="'.$nameDB.'">'.$nameTit.' (<span class="required">*</span>):</label>';
						} else {
						  $lbl = '<label for="'.$nameDB.'">'.$nameTit.':</label>';
						}
						
						if ($type == ''){$type='text';}
						
						if ($type == 'select') {
						  $fieldForm = '<select class="form-control combo" name="'.$nameDB.'" id="'.
						  $nameDB.'" >';	
						  $fieldForm .= '<option value="0"></option>';
						  $fieldForm .= '<option value="1">Test</option>';
						  $fieldForm .= '</select>';	
						} else {
						  $fieldForm = '<input type="'.$type.'" class="form-control" name="'.$nameDB.'" id="'.
						  $nameDB.'" "'.$validation.'" value="" />';	
						}
						
			?> 
					<div class="form-group input_container">
					 <?php echo $lbl; ?>
					  <div class="field_container">
					   <?php echo $fieldForm; ?>
					  </div>
					</div><!-- div input_container -->
					
			<?php
			
					   $validation = "";
					}	
				}// foreach formCampos	
			   }// foreach formCampos						
			?>
          <div class="button_container">
            <button type="submit">Agregar <?php echo ucwords(SINGLE_SELECTED_CLASS); ?></button>
          </div>
        </form>
        
      </div>
    </div>

    <noscript id="noscript_container">
      <div id="noscript" class="error">
        <p>Se necesita tener JavaScript habilitado para usar está página.</p>
      </div>
    </noscript>

    <div id="message_container">
      <div id="message" class="success">
        <p>This is a success message.</p>
      </div>
    </div>

    <div id="loading_container">
      <div id="loading_container2">
        <div id="loading_container3">
          <div id="loading_container4">
            Cargando, por favor espere...
          </div>
        </div>
      </div>
    </div>

  </body>
  <script>  
$(document).ready(function(){

	  // Tabla 1 - Not Sent
	  $('#tramanosent').on('click',function() {
		  
		  $('#titleModal').html("<b>Vehiculos Sin Trama Enviadas</b>");
		  $('#vehContent').html(notsent);
		  
		    // DATATABLE
		    var tabla = $('#vehContent>table').dataTable({
			 "aoColumns" : [
				{ "sWidth": '30px' }, // idgps
				{ "sWidth": '20px' }, // idveh
				{ "sWidth": '20px' }, // iduser
				{ "sWidth": '30px' }, // celgps
				{ "sWidth": '30px' }, // serial
				{ "sWidth": '10px' }, // SMS
				{ "sWidth": '80px' }, // ult. trama
				{ "sWidth": '10px' }, // dias
				{ "sWidth": '10px' }, // MAPA
				{ "sWidth": '20px' }, // lat
				{ "sWidth": '20px' }, // lng
				{ "sWidth": '10px' } // vel
			 ],
			 "language": {
                "url": "js/lang/datatables.spanish.json"
			  }
			});
			
			// Aplicar busqueda
			$('#vehContent>table tfoot th').each(function (i){

                var title = $('#vehContent>table thead th').eq($(this).index()).text();
				
				  if (title == 'SMS') {
				  } else if (title == 'ULTIMA TRANS.') {
					var search = '<input type="datetime" placeholder="Buscar ' + title + '" />';
				
					$(this).html('');
					$(search).appendTo(this).keyup(function(){tabla.fnFilter($(this).val(),i)})
				  } else {
					var search = '<input type="text" placeholder="Buscar ' + title + '" />';
				
					$(this).html('');
					$(search).appendTo(this).keyup(function(){tabla.fnFilter($(this).val(),i)})
				  }
			});
			
			$('div.dataTables_filter input[type=seach]').focus();
			
	  });
	  
	  
	  // Tabla 2 - OutDate
	  $('#tramaoutdate').on('click', function() {
		  
		  $('#titleModal').html("<b>Vehiculos Desactualizados</b>");
		  $('#vehContent').html(outdate);
		  
			// DATATABLE
			var tabla = $('#vehContent>table').dataTable({
			 "aoColumns" : [
				{ "sWidth": '30px' }, // idgps
				{ "sWidth": '20px' }, // idveh
				{ "sWidth": '20px' }, // iduser
				{ "sWidth": '30px' }, // celgps
				{ "sWidth": '30px' }, // serial
				{ "sWidth": '10px' }, // SMS
				{ "sWidth": '80px' }, // ult. trama
				{ "sWidth": '10px' }, // dias
				{ "sWidth": '10px' }, // MAPA
				{ "sWidth": '20px' }, // lat
				{ "sWidth": '20px' }, // lng
				{ "sWidth": '10px' } // vel
			 ],
			 "language": {
                "url": "js/lang/datatables.spanish.json"
			  }
			});
			
			// Aplicar busqueda
			$('#vehContent>table tfoot th').each(function (i){

                var title = $('#vehContent>table thead th').eq($(this).index()).text();
				  if (title == 'SMS') {
				  } else {
					var search = '<input type="text" placeholder="Buscar ' + title + '" />';
				
					$(this).html('');
					$(search).appendTo(this).keyup(function(){tabla.fnFilter($(this).val(),i)})
				  }
			});	
			
			$('div.dataTables_filter input[type=seach]').focus();
			
	  });
	  
	  
	  
	  // Tabla 3 - Send
	  $('#tramasend').on('click', function() {
		  
		  $('#titleModal').html("<b>Vehiculos Actualizados</b>");
		  $('#vehContent').html(sent);
		  
		    // DATATABLE
		    var tabla = $('#vehContent>table').dataTable({
			 "aoColumns" : [
				{ "sWidth": '30px' }, // idgps
				{ "sWidth": '20px' }, // idveh
				{ "sWidth": '20px' }, // iduser
				{ "sWidth": '30px' }, // celgps
				{ "sWidth": '30px' }, // serial
				{ "sWidth": '10px' }, // SMS
				{ "sWidth": '80px' }, // ult. trama
				{ "sWidth": '10px' }, // dias
				{ "sWidth": '10px' }, // MAPA
				{ "sWidth": '20px' }, // lat
				{ "sWidth": '20px' }, // lng
				{ "sWidth": '10px' } // vel
			 ],
			 "language": {
                "url": "js/lang/datatables.spanish.json"
			  }
		    });
			
			// Aplicar busqueda
			$('#vehContent>table tfoot th').each(function (i){

                var title = $('#vehContent>table thead th').eq($(this).index()).text();
								  if (title == 'SMS') {
				  } else if (title == 'ULTIMA TRANS.') {
					var search = '<input type="datetime" placeholder="Buscar ' + title + '" />';
				
					$(this).html('');
					$(search).appendTo(this).keyup(function(){tabla.fnFilter($(this).val(),i)})
				  } else {
					var search = '<input type="text" placeholder="Buscar ' + title + '" />';
				
					$(this).html('');
					$(search).appendTo(this).keyup(function(){tabla.fnFilter($(this).val(),i)})
				  }
			});

			$('div.dataTables_filter input[type=seach]').focus();
		  
	  });
	  
	 $('#modalOpt').on('shown.bs.modal', function(e) { 
	    //console.log("MODAL LOADED");
		/*var getIdFromRow = $(e.relatedTarget).closest('.btnSMS').data('id');
		
		console.log(getIdFromRow);*/
		//$('.btnSMS').data('id');
	    $('.btnSMS').on('click', function() {
		  //$('#smscelgps').removeAttr('readonly');
		  var celulargps = $(this).attr('data-id');
		   console.log("Celular GPS: "+celulargps);
		   //$('#smscelgps').html(celulargps);
		   $('#smscelgps').val(celulargps);
		   //$('#smscelgps').attr('readonly','readonly');
		});
		
	  });
	  
	  
	  
   var selClass = '<?php echo SELECTED_CLASS; ?>';
   var singClass = '<?php echo SINGLE_SELECTED_CLASS; ?>';
   var tit = <?php echo json_encode($camposTitulo); ?>;
   var bd = <?php echo json_encode($camposBD); ?>;
   var unique = '<?php echo UNIQUE_BD_FIELD; ?>';
   
   configDatatable(unique,selClass,singClass,tit,bd);
   executeDatatable();
   

});

var myCenter=new google.maps.LatLng(10.2437116667, -67.582555);

  marker=new google.maps.Marker({
    position:myCenter
  });

function initialize() {
  mapModalOpc = {
      center:myCenter,
      zoom: 14,
      draggable: false,
      scrollwheel: false,
      mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  
  mapModal=new google.maps.Map(document.getElementById("mapaModalVeh"),mapModalOpc);
  marker.setMap(mapModal);
    
  /*google.maps.event.addListener(marker, 'click', function() {
      
    infowindow.setContent(contentString);
    infowindow.open(mapModal, marker);
    
  }); */
};
google.maps.event.addDomListener(window, 'load', initialize);

google.maps.event.addDomListener(window, "resize", resizingMap());

$('#modalMap').on('show.bs.modal', function() {
   resizeMap();
});

function resizeMap() {
   if(typeof mapModal =="undefined") return;
   setTimeout( function(){resizingMap();} , 400);
}

function resizingMap() {
   if(typeof mapModal =="undefined") return;
   var center = mapModal.getCenter();
   google.maps.event.trigger(mapModal, "resize");
   mapModal.setCenter(center); 
}

  </script>
  
</html>