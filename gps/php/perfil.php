<?php

session_start();
require_once('../class/class.conexion.php');

 $Conexion = Conexion::singleton_conexion();

// verificar sesion
if (isset($_SESSION['cedula'])) { $Cedula = base64_decode($_SESSION['cedula']); } else { $Cedula = 0; }
if (isset($_SESSION['id'])) { $Id = base64_decode($_SESSION['id']); } else { $Id = 0; }
if (isset($_SESSION['nivel'])) { $Nivel = base64_decode($_SESSION['nivel']); } else { $Nivel = 0; }

 $id = 1;
 $Filas = "";

  $Sql = "select * from usuarios where id = ".$Id;
  $Consulta = $Conexion->prepare($Sql);
  $Consulta->execute();
  $Fila = $Consulta->fetchObject();


?>

<form class="form-inline" role="form" name="vntAjax" id="vntAjax">
 <div class="row"> <!-- ROW -->

<div class="col-md-2"></div>

<div class="col-md-8">

 <div class="center-block">

 <div class="form-group">
  <label for="cedula"><b>Cedula</b></label>:&nbsp;<?php echo $Fila->cedula; ?>&nbsp;&nbsp;&nbsp;
 </div>

 <div class="form-group">
  <label for="nombres"><b>Nombres</b></label>:&nbsp;<?php echo $Fila->nombres; ?>&nbsp;&nbsp;&nbsp;
 </div>

  <div class="form-group">
  <label for="apellidos"><b>Apellidos</b></label>:&nbsp;<?php echo $Fila->apellidos; ?>&nbsp;&nbsp;&nbsp;
 </div><br/>

  <?php

   $Sql = "select * from ciudades where id = ".$Fila->ciudad;
   $Consulta = $Conexion->prepare($Sql);
   $Consulta->execute();
   $FilaTmp = $Consulta->fetchObject();
   $Ciudad = $FilaTmp->ciudad;
  ?>

  <div class="form-group">
  <label for="ciudad"><b>Ciudad</b></label>:&nbsp;<?php echo $Ciudad; ?>&nbsp;&nbsp;&nbsp;
 </div>

  <?php

   $Sql = "select * from estados where id = ".$Fila->estado;
   $Consulta = $Conexion->prepare($Sql);
   $Consulta ->execute();
   $FilaTmp = $Consulta->fetchObject();
   $Estado = $FilaTmp->estado;

  ?>

 <div class="form-group">
  <label for="estado"><b>Estado</b></label>:&nbsp;<?php echo $Estado; ?>&nbsp;&nbsp;&nbsp;
 </div>

 <div class="form-group">
  <label for="direccion"><b>Direcci&oacute;n</b></label>:&nbsp;<?php echo $Fila->direccion; ?>&nbsp;&nbsp;&nbsp;
 </div><br/>

 <div class="form-group">
  <label for="email"><b>Email</b></label>:&nbsp;<?php echo $Fila->email; ?>&nbsp;&nbsp;&nbsp;
 </div>

 <?php

    $aFecha = explode("-",$Fila->fecharegistro);
    $Fecha = $aFecha[2]."/".$aFecha[1]."/".$aFecha[0];

 ?>

 <div class="form-group">
  <label for="fregistro"><b>Fecha de Registro</b></label>:&nbsp;<?php echo $Fecha; ?>&nbsp;&nbsp;&nbsp;
 </div><br/>

 <div class="form-group">
  <label for="celular"><b>Celular</b></label>:&nbsp;<?php echo $Fila->celular; ?>&nbsp;&nbsp;&nbsp;
 </div>

 <div class="form-group">
  <label for="telefono"><b>Tel&eacute;fono</b></label>:&nbsp;<?php echo $Fila->telefono; ?>&nbsp;&nbsp;&nbsp;
 </div>

 </div>
</div>

<div class="col-md-2"></div>

</div> <!-- ROW -->
</form>

 <div class="modal-footer" style="text-align:center;">
	<button type="button" class="btn btn-success" name="cerrar" data-dismiss="modal" id="cerrar"><span class="fa fa-caret-square-o-right">&nbsp;</span>Cerrar</button><br/><br/>
 </div>
