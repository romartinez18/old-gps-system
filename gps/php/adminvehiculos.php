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

  
include_once('../class/class.conexion.php');

// verificar sesion
if (isset($_SESSION['cedula'])) { $Cedula = base64_decode($_SESSION['cedula']); } else { $Cedula = 0; }
if (isset($_SESSION['id'])) { $Id = base64_decode($_SESSION['id']); } else { $Id = 0; }
if (isset($_SESSION['nivel'])) { $Nivel = base64_decode($_SESSION['nivel']); } else { $Nivel = 0; }

$dbh = Conexion::singleton_conexion();

$sql = "SELECT v.id as idvehiculo, v.placa as placa, v.descripcion as descripcion, v.tipo as tipo, v.foto as foto, g.serial as serial FROM vehiculos as v inner join gps as g on v.idgps = g.id inner join vehiculos_user as vu on vu.idvehiculo = v.id where vu.idusuario = ".$Id;

$query = $dbh->prepare($sql);
$query->execute();

?>

<script language="javascript">
  function buscarVeh(pos) {
    google.maps.event.trigger(ventanas[pos], 'click');
    /* mapa.panTo(ventanas[pos].getPosition());
     mapa.setZoom(14);   
     mapa.setCenter(ventanas[pos].getPosition());*/
  }
</script>

<form id="lvehiculos">

<div align="center">
  <table class="table table-hover" style="border:2px solid #DDD;">
	<thead>
		<tr>
		  <th style="text-align:center;">Tipo</th>
		  <th style="text-align:center;">Descripci&oacute;n</th>
                  <th style="text-align:center;"></th>
		</tr>
	</thead>
	<tbody style="text-align:center;">
		<?php 
		
		  //$i = 0;
		  foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $filas) {
		    echo "<tr onclick=\"buscarVeh('".$filas['serial']."');\" data-dismiss=\"modal\" id='".$filas['serial']."'>";
		    echo "<td><img src='images/obj/".$filas['foto']."' style='max-width:100%; height:auto;' /></td>";
                    echo "<td>".$filas['descripcion']."</td>";
                    echo "<td><a href='#' onclick=\"ComandosGPS('".$filas['idvehiculo']."')\">Comandos</a><input id='idvehiculo' style='display:none;' name='idvehiculo' type='text' value='".$filas['idvehiculo']."'></td>";
		    //$i++;
		  }
		  
		
		?>
	</tbody>
  </table>
</div>
</form>
<script>
$.getJSON( "json/coord.php", function( data ) {
  $.each( data, function( key, val ) {
   
   var fecha= new Date(val.Fecha2);
		var diaActual1 = fecha.getDate();
		var mmActual1 = fecha.getMonth() + 1;
		var yyyyActual1 = fecha.getFullYear();
		var fecha_calculo = ''+yyyyActual1+'-'+mmActual1+'-'+diaActual1+'';
	if((val.Coord).length === 0){
	 $("#"+val.Serial).css('background-color','#BBBBBB');
	// console.log("SIM REPORTAR: "+diasEntreFechas(fecha_calculo)+""); 
	// console.log((val.Coord).length);
	}else{
		
	if (diasEntreFechas(fecha_calculo) != 1) {
	   $("#"+val.Serial).css('background-color','#F5A9A9');	 
	   console.log("Dias sin reportar: "+diasEntreFechas(fecha_calculo)+""); 
	 } else {
	   $("#"+val.Serial).css('background-color','#A9F5E1');
	//console.log("REPORTANDO: "+diasEntreFechas(fecha_calculo)+""); 
	 }
	}
	
  }); 
  
});
</script>