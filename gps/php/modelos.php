<?php

  //error_reporting(E_ALL);
  
  require_once('../class/class.conexion.php');
  
  $Conexion = Conexion::singleton_conexion();
  //$Conexion = new Conexion();
  
?> 

<style>
 .modal-body {
   margin:10px 10px 10px 10px;
 }

</style>

<form class="form-inline" role="form">
 <div class="row"> <!-- ROW -->

  <?php  
   $Sql = "select * from marcagps order by nombre asc";
   $Consulta = $Conexion->prepare($Sql);  
   $Consulta->execute();  
 ?>
 
 <div class="form-group">
  <label for="nMarca">Seleccione la Marca</label>:&nbsp;&nbsp;
    <select name="nMarca" class="form-control input-sm" onchange="">
	    <option value="0"></option>
	    <?php 
		  while ($Filas = $Consulta->fetchObject())
		  {
		    echo "<option value=\"".$Filas->id."\">".$Filas->nombre."</option>";
		  }
		?>
	</select>
 </div><br/><br/>
 
  <div class="form-group">
  <label for="nModelo">Nombre del Modelo</label>:&nbsp;&nbsp;
	<input type="text" name="nModelo" id="nModelo" class="form-control input-sm" placeholder="Modelo" />&nbsp;&nbsp;
 </div><br/><br/>


</div> <!-- ROW -->
	
	
 <div class="modal-footer" style="text-align:center;">
	<button type="button" class="btn btn-success" name="guardar" id="guardar"><span class="fa fa-save">&nbsp;</span>Guardar</button>
	<button type="button" class="btn btn-warning" name="cancelar" data-dismiss="modal" id="cancelar"><span class="fa fa-caret-square-o-right">&nbsp;</span>Cancelar</button><br/><br/>
 </div>

</form>
