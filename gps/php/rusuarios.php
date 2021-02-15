<?php

  error_reporting(E_ALL);
  
  require_once('../class/class.conexion.php');
  
  $Conexion = Conexion::singleton_conexion();
  
?> 

<style>
 .modal-body {
   margin:10px 10px 10px 10px;
 }

</style>

<form class="form-inline" role="form" name="vntAjax" id="vntAjax">
 <div class="row"> <!-- ROW -->

 <div class="form-group">
  <label for="cedula">Cedula/Rif</label>:&nbsp;&nbsp;
	  <select class="form-control input-sm" name="ci"><option value="0">V-</option><option value="1">J-</option><option value="2">E-</option></select>
	  <input type="text" name="cedula" id="cedula" class="form-control input-sm" placeholder="Cedula o Rif" />&nbsp;&nbsp;
	  <button type="button" class="btn btn-info" name="foto" id="foto" onclick="cargarPaginaModal('Avatares','avatares.php');">
	    <span class="fa fa-image">&nbsp;</span>Avatar</button>
 </div><br/><br/>

 <div class="form-group">&nbsp;&nbsp;
  <label for="email">Email</label>:&nbsp;
    <div class="input-group merged">
      <input type="email" name="email" id="email" class="form-control input-sm" placeholder="Email" />
	</div>
 </div>
 
 <div class="form-group">&nbsp;
  <label for="apellido">Apellidos</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-font" style="color:#6888ff;"></i></span>
      <input type="text" name="apellido" id="apellido" class="form-control input-sm" placeholder="Apellidos" />
	</div>&nbsp;&nbsp;
 </div>

 <div class="form-group">
  <label for="nombre">Nombre o Raz&oacute;n Social</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-font" style="color:#6888ff;"></i></span>
      <input type="text" name="nombre" id="nombre" class="form-control input-sm" placeholder="Nombre o Raz&oacute;n Soc." />
	</div>&nbsp;&nbsp;
 </div><br/><br/>
	
 <div class="form-group">&nbsp;&nbsp;&nbsp;
  <label for="tipo">Tipo</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <select name="tipo" class="form-control input-sm">
	    <option value="0">Administrador</option><option value="1" selected="selected">Cliente</option><option value="2">Distribuidor</option>
	  </select>
	</div>
 </div>

 <div class="form-group">
  <label for="cantidad">Cantidad Usuarios</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-sort-numeric-asc" style="color:#f7ae50;"></i></span>
      <input type="number" name="cantidad" id="cantidad" class="form-control input-sm" placeholder="Cantidad de Usuarios" />
	</div>&nbsp;&nbsp;&nbsp;&nbsp;
 </div>

 <div class="form-group">
  <label for="distribuidor">Distribuidor</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <select name="distribuidor" class="form-control input-sm">
	    <option value="0">Punto de Venta</option><option value="1">Instalador</option><option value="2">Punto de Venta e Instalador</option>
	  </select>
	</div>&nbsp;&nbsp;&nbsp;&nbsp;
 </div> <br/><br/>

 <?php  
   
   $Sql = "select * from estados order by estado asc";
   $Consulta = $Conexion->prepare($Sql);  
   $Consulta->execute();
   
  ?>
 
 <div class="form-group"> 
  <label for="estado">Estado</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <select name="estado" class="form-control input-sm" onchange="">
	    <option value="0"></option>
	    <?php 
		  while ($Filas = $Consulta->fetchObject())
		  {
		    echo "<option value=\"".$Filas->idestado."\">".$Filas->estado."</option>";
		  }
		?>
	  </select>
	</div>&nbsp;&nbsp;&nbsp;
 </div>
 
 <?php  
   
   $Sql = "select * from ciudades order by ciudad asc";
   $Consulta = $Conexion->prepare($Sql);  
   $Consulta->execute();
   
 ?>
 
 <div class="form-group">
  <label for="distribuidor">Ciudad</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <select name="ciudad" class="form-control input-sm">
	    <option value="0"></option>
	    <?php 
		  while ($Filas = $Consulta->fetchObject())
		  {
		    echo "<option value=\"".$Filas->idciudad."\">".$Filas->ciudad."</option>";
		  }
		?>
	  </select>
	</div>&nbsp;&nbsp;
 </div>


 <div class="form-group">	
  <label for="direccion">Direcci&oacute;n</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-home" style="color:#bdf7ab;"></i></span>
      <input type="text" name="direccion" id="direccion" class="form-control input-sm" placeholder="Direcci&oacute;n Fiscal" />
	</div>
 </div><br/><br/>
	
 <div class="form-group">
  <label for="celular">Celular</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-phone-square" style="color:#68ffa4;"></i></span>
      <input type="text" name="celular" id="celular" class="form-control input-sm" placeholder="Nro de Celular" />
	</div>&nbsp;&nbsp;
 </div>
	
 <div class="form-group">
  <label for="telefono">Tel&eacute;fono</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-tty" style="color:#89ffc6;"></i></span>
      <input type="text" name="telefono" id="telefono" class="form-control input-sm" placeholder="Nro de Tel&eacute;fono" />
	</div>
 </div><br/><br/>

 <div class="form-group">
  <label for="usuario">Usuario</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
      <input type="text" name="usuario" id="usuario" class="form-control input-sm" placeholder="Usuario" />
	</div>
 </div><br/><br/>

 <div class="form-group">&nbsp;&nbsp;
  <label for="clave">Clave</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-lock"></i></span>
      <input type="password" name="clave" id="clave" class="form-control input-sm" placeholder="Clave" />
	</div>&nbsp;&nbsp;&nbsp;&nbsp;
 </div>

 <div class="form-group">
  <label for="rclave">Repetir Clave</label>:&nbsp;&nbsp;
    <div class="input-group merged">
	  <span class="input-group-addon"><i class="fa fa-lock"></i></span>
      <input type="password" name="rclave" id="rclave" class="form-control input-sm" placeholder="Repetir Clave" />
	</div>
 </div><br/><br/>

</div> <!-- ROW -->
	
	
 <div class="modal-footer" style="text-align:center;">
	<button type="button" class="btn btn-success" name="guardar" id="guardar"><span class="fa fa-save">&nbsp;</span>Guardar</button>
	<button type="button" class="btn btn-warning" name="cancelar" data-dismiss="modal" id="cancelar"><span class="fa fa-caret-square-o-right">&nbsp;</span>Cancelar</button><br/><br/>
 </div>

</form>

<script language="javascript">
	var usuario = new LiveValidation('usuario');
	var clave = new LiveValidation('clave');
	var rclave = new LiveValidation('rclave');
		
	usuario.add(Validate.Length,{minimum:8,maximum:15});
	clave.add(Validate.Length,{minimum:8,maximum:15});
	rclave.add(Validate.Length,{minimum:8,maximum:15});
	
	$("#celular").mask("9999-9999999");
	$("#telefono").mask("9999-9999999");
	
	//email.add(Validate.Email);
	//telefono.add(Validate.Numericality);
	//telefono.add(Validate.Length,{is:8});	
</script>
