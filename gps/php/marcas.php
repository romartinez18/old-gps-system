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

 <div class="form-group">
  <label for="Nombre">Nombre de la Marca</label>:&nbsp;&nbsp;
	<input type="text" name="nMarca" id="nMarca" class="form-control input-sm" placeholder="Marca" />&nbsp;&nbsp;
 </div><br/><br/>


</div> <!-- ROW -->
	
	
 <div class="modal-footer" style="text-align:center;">
	<button type="button" class="btn btn-success" name="guardar" id="guardar"><span class="fa fa-save">&nbsp;</span>Guardar</button>
	<button type="button" class="btn btn-warning" name="cancelar" data-dismiss="modal" id="cancelar"><span class="fa fa-caret-square-o-right">&nbsp;</span>Cancelar</button><br/><br/>
 </div>

</form>
