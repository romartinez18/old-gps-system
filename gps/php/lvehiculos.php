<?php 
  session_start();
  
include_once('../class/class.conexion.php');

// verificar sesion
if (isset($_SESSION['cedula'])) { $Cedula = base64_decode($_SESSION['cedula']); } else { $Cedula = 0; }
if (isset($_SESSION['id'])) { $Id = base64_decode($_SESSION['id']); } else { $Id = 0; }
if (isset($_SESSION['nivel'])) { $Nivel = base64_decode($_SESSION['nivel']); } else { $Nivel = 0; }

$dbh = Conexion::singleton_conexion();

$sql = "SELECT v.placa as placa, v.descripcion as descripcion, v.tipo as tipo, v.foto as foto, g.serial as serial FROM vehiculos as v inner join gps as g on v.idgps = g.id inner join vehiculos_user as vu on vu.idvehiculo = v.id where vu.idusuario = ".$Id;

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
		  <th style="text-align:center;">Placa</th>
		  <th style="text-align:center;">Descripci&oacute;n</th>
		  <th style="text-align:center;">Serial</th>
		</tr>
	</thead>
	<tbody style="text-align:center;">
		<?php 
		
		  $i = 0;
		  foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $filas) {
		    echo "<tr onclick=\"buscarVeh(".$i.");\" data-dismiss=\"modal\">";
		    echo "<td>".$filas['placa']."</td>";
		    echo "<td>".$filas['descripcion']."</td>";
		    echo "<td>".$filas['serial']."</td></tr>";
		    $i++;
		  }
		  
		
		?>
	</tbody>
  </table>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-danger" style="text-align:right;" data-dismiss="modal">Cerrar</button><br/><br/>
</div>

</form>
