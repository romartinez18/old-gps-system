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

error_reporting(E_ALL);
include_once('../class/class.conexion.php');

// verificar sesion
if (isset($_SESSION['cedula'])) { $Cedula = base64_decode($_SESSION['cedula']); } else { $Cedula = 0; }
if (isset($_SESSION['id'])) { $Id = base64_decode($_SESSION['id']); } else { $Id = 0; }
if (isset($_SESSION['nivel'])) { $Nivel = base64_decode($_SESSION['nivel']); } else { $Nivel = 0; }

$dbh = Conexion::singleton_conexion();
 
$sql = "SELECT * FROM notificaciones WHERE idusuario = ".$Id." order by prioridad, fecha desc";
$query = $dbh->prepare($sql);
$query->execute();

?>

<script>

$('#checkall').on('click', function() {
	$('#fnotificaciones').find(':checkbox').prop('checked', this.checked);
});

</script>
<style>
.noleido {
   background-color:#ddd;
   font-weight: bold;
}
</style>

<form id="fnotificaciones">

<div class="form-group">
  <button type="button" class="btn btn-success">Marcar como Le&iacute;dos</button>
  <button type="button" class="btn btn-warning">Eliminar</button>
</div>

<table class="table table-hover" style="border:2px solid #DDD;">
	<thead>
		<tr>
		  <th style="text-align:center;">
			<input type="checkbox" id="checkall" />
		  </th>
		  <th style="text-align:center;">Mensaje</th>
		  <th style="text-align:center;">Prioridad</th>
		  <th style="text-align:center;">Fecha</th>
		</tr>
	</thead>
	<tbody style="text-align:center;">
		<?php 
		  
		  $i = 0;
		  foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $filas) {
		    $label = ""; $text = ""; $i++;
		    $phpdate = strtotime($filas['fecha']);
		    $fecha = date("d/m/Y H:i:s",$phpdate);
		    $smsLeido = "";
			
		     switch($filas['prioridad']) {
		       case 1: $label = "label label-info"; $text = "Informaci&oacute;n";  break;
		       case 2: $label = "label label-warning"; $text = "Advertencia";  break;
		       case 3: $label = "label label-danger"; $text = "Emergencia";  break;
		     }
  	        
			if ($filas['noleido'] == 1) {$smsLeido = "class='noleido'";}
			
			 echo "<tr ".$smsLeido." onclick=\"cargarMensaje(".$i.");\">
  	            <td><input type=\"checkbox\" name=\"marcar\" value=\"".$i."\" /></td>";
		      echo "<td>".$filas['titulo']."</td>";
		      echo "<td><span class=\"".$label."\">".$text."</span></td>";
		      echo "<td>".$fecha."</td></tr>";
			
		  }
		  
		
		?>
	</tbody>
</table>

<div class="modal-footer">
  <button type="button" class="btn btn-danger" style="text-align:right;" data-dismiss="modal">Cerrar</button><br/><br/>
</div>

</form>
