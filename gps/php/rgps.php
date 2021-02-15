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
  <label for="serial">Serial</label>:&nbsp;&nbsp;
	<input type="text" name="serial" id="serial" class="form-control input-sm" placeholder="Serial GPS" />&nbsp;&nbsp;
 </div><br/><br/>

 <?php  
   
   $Sql = "select * from marcagps order by nombre asc";
   $Consulta = $Conexion->prepare($Sql);  
   $Consulta->execute();
   
  ?>
 
 <div class="form-group"> 
  <label for="marca">Marca</label>:&nbsp;&nbsp;
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
 </div>&nbsp;&nbsp;&nbsp;&nbsp;
 
 <?php  
   
   $Sql = "select * from modelogps order by nombre asc";
   $Consulta = $Conexion->prepare($Sql);  
   $Consulta->execute();
   
 ?>
 
 <div class="form-group">
  <label for="modelo">Modelo</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <select name="modelo" class="form-control input-sm">
	    <option value="0"></option>
	    <?php 
		  while ($Filas = $Consulta->fetchObject())
		  {
		    echo "<option value=\"".$Filas->idmodelo."\">".$Filas->nombre."</option>";
		  }
		?>
	  </select>
	</div>&nbsp;&nbsp;
	  <button type="button" class="btn btn-success btn-sm" name="vmodelo" id="vmodelo" onclick="cargarPaginaModal('Modelo GPS','modelos.php');">
	    <span class="fa fa-plus">&nbsp;</span>
	  </button>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 </div>

 
 <div class="form-group">
  <label for="activo">Activo</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <input type="checkbox" name="activo" id="activo" checked="checked" class="form-control input-sm" />
	</div>&nbsp;&nbsp;
 </div>

 <h4 style="text-align:center;">Celulares Autorizados</h4><br/>
 
 <div class="form-group">&nbsp;&nbsp;&nbsp;
  <label for="celular">Celular 1</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-phone-square" style="color:#68ffa4;"></i></span>
      <input type="text" name="celular1" id="celular1" class="form-control input-sm" placeholder="Celular 1" />
	</div>&nbsp;&nbsp;
 </div>
 
  <div class="form-group">
  <label for="celular">Celular 2</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-phone-square" style="color:#68ffa4;"></i></span>
      <input type="text" name="celular2" id="celular2" class="form-control input-sm" placeholder="Celular 2" />
	</div>&nbsp;&nbsp;
 </div>
 
  <div class="form-group">
  <label for="celular">Celular 3</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-phone-square" style="color:#68ffa4;"></i></span>
      <input type="text" name="celular3" id="celular3" class="form-control input-sm" placeholder="Celular 3" />
	</div>&nbsp;&nbsp;
 </div>
 
 <br/><br/>

 <div class="form-group">
  <label for="diacorte">Dia de Corte</label>:&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
      <input type="date" name="diacorte" id="diacorte" class="form-control input-sm" placeholder="Dia de Corte" />
	</div>
 </div><br/><br/>

</div> <!-- ROW -->
	
	
 <div class="modal-footer" style="text-align:center;">
	<button type="button" class="btn btn-success" name="guardar" id="guardar"><span class="fa fa-save">&nbsp;</span>Guardar</button>
	<button type="button" class="btn btn-warning" name="cancelar" data-dismiss="modal" id="cancelar"><span class="fa fa-caret-square-o-right">&nbsp;</span>Cancelar</button><br/><br/>
 </div>

</form>

<script language="javascript">
	
	$("#celular1").mask("9999-9999999");
	$("#celular2").mask("9999-9999999");
	$("#celular3").mask("9999-9999999");
	
	//email.add(Validate.Email);
	//telefono.add(Validate.Numericality);
	//telefono.add(Validate.Length,{is:8});	
</script>
