<?php 
  session_start();
  
include_once('../class/class.conexion.php');

// verificar sesion
if (isset($_SESSION['cedula'])) { $Cedula = base64_decode($_SESSION['cedula']); } else { $Cedula = 0; }
if (isset($_SESSION['id'])) { $Id = base64_decode($_SESSION['id']); } else { $Id = 0; }
if (isset($_SESSION['nivel'])) { $Nivel = base64_decode($_SESSION['nivel']); } else { $Nivel = 0; }

$dbh = Conexion::singleton_conexion();

$sql = "SELECT v.idvehiculo as idvehiculo, v.placa as placa, v.descripcion as descripcion, v.tipo as tipo, v.foto as foto, g.serial as serial FROM vehiculos as v inner join gps as g on v.idgps = g.id inner join vehiculos_user as vu on vu.idvehiculo = v.id where vu.idusuario = ".$Id;

$query = $dbh->prepare($sql);
$query->execute();

?>



<form id="lcomandos">

<div align="center">
  <table class="table table-hover" style="border:2px solid #DDD;">
	<thead>
		
	</thead>
	<tbody style="text-align:center;">
		<tr>
		  <th style="text-align:center;">Apagar</th>
		</tr>
                <tr>
		  <th style="text-align:center;">Control de Velocidad</th>
		</tr>
                 <tr>
		  <th style="text-align:center;" data-dismiss="modal" aria-hidden="true" onclick="cargarPagina('Reporte de Vehiculos','filtro.php');"><span>Reportes / Historial</span></th>
		</tr>
	</tbody>
  </table>
</div>
</form>
