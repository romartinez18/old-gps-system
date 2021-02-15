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
  <label for="marca">Marca GPS</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <select name="marca" class="form-control input-sm" onchange="">
	    <option value="0"></option>
	        <?php 
		  while ($Filas = $Consulta->fetchObject())
		  {
		    echo "<option value=\"".$Filas->idmarca."\">".$Filas->nombre."</option>";
		  }
		?>
	  </select>
	</div>&nbsp;&nbsp;&nbsp;
	  <button type="button" class="btn btn-success btn-sm" name="vmarca" id="vmarca" onclick="cargarPaginaModal('Marca GPS','marcas.php');">
	    <span class="fa fa-plus">&nbsp;</span>
	  </button>
 </div>

 <?php  
   
   $Sql = "select * from modelogps order by nombre asc";
   $Consulta = $Conexion->prepare($Sql);  
   $Consulta->execute();
   
  ?>
  
 <div class="form-group"> 
  <label for="marca">Modelo GPS</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <select name="modelo" class="form-control input-sm" onchange="">
	    <option value="0"></option>
	        <?php 
		  while ($Filas = $Consulta->fetchObject())
		  {
		    echo "<option value=\"".$Filas->idmodelo."\">".$Filas->nombre."</option>";
		  }
		?>
	  </select>
	</div>&nbsp;&nbsp;&nbsp;
	  <button type="button" class="btn btn-success btn-sm" name="vmodelo" id="vmodelo" onclick="cargarPaginaModal('Modelo GPS','modelos.php');">
	    <span class="fa fa-plus">&nbsp;</span>
	  </button>
 </div><br/><br/>
 
  <div class="form-group">&nbsp;&nbsp;&nbsp;
  <label for="nombre">Nombre del Comando</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-th-large" style="color:#68ffa4;"></i></span>
      <input type="text" name="nombre" id="nombre" class="form-control input-sm" placeholder="Nombre" />
	</div>&nbsp;&nbsp;
 </div>
 
 <div class="form-group">&nbsp;&nbsp;&nbsp;
  <label for="comando">Comando</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-cc" style="color:#68ffa4;"></i></span>
      <input type="text" name="comando" id="comando" class="form-control input-sm" placeholder="Comando" />
	</div>&nbsp;&nbsp;
 </div><br/><br/>
 
  <div class="form-group">&nbsp;&nbsp;&nbsp;
  <label for="descripcion">Descripci&oacute;n</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-reorder" style="color:#68ffa4;"></i></span>
	  <textarea name="descripcion" id="descripcion" class="form-control input-sm" rows="3"></textarea>
	</div>&nbsp;&nbsp;
 </div><br/><br/>
 
</div> <!-- ROW -->
	
	
 <div class="modal-footer" style="text-align:center;">
	<button type="button" class="btn btn-success" name="guardar" id="guardar"><span class="fa fa-save">&nbsp;</span>Guardar</button>
	<button type="button" class="btn btn-warning" name="cancelar" data-dismiss="modal" id="cancelar"><span class="fa fa-caret-square-o-right">&nbsp;</span>Cancelar</button><br/><br/>
 </div>

</form>