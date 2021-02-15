<?php 

//defined('_GPS_') or die("Acceso restringido");

?> 
<script>

function radioCheck(obj) {

  if ($(obj).is(':checked')) {
    //$(obj).removeAttr('checked');
	$(obj).attr('checked',false);
  } else {
    //$(obj).attr('checked','checked');
	$(obj).attr('checked',true);
  }
}

</script>


<form id="favatares">

<table class="table table-hover" style="border:2px solid #DDD;">
	<tbody style="text-align:center;">
	  <?php
	    $img = 1;
		
		// Filas
		for($i = 0;$i < 2;$i++) {
		  echo "<tr>";
		    for($j = 0;$j < 3; $j++) {
			  $imagen = "images/usuarios/avatar0".$img.".png";

			  //if ( file_exists($_SERVER['DOCUMENT_ROOT']."GPS"."/".$imagen)) {
			   echo "<td><img src='{$imagen}' style=\"width:128px;height:128px;\" 
			   class='img-rounded' onclick=\"radioCheck(img{$img});\" /><br/>
			   <input type='radio' name='img' id='img{$img}' value='{$img}' /></td>";
			  /*} else {
			   echo "no cargado";
			  }*/
			  $img++;
			}
		  echo "</tr>";
		}
		
	  ?>
	</tbody>
</table>

<div class="modal-footer" style="text-align:right;">
  <button type="button" class="btn btn-success">Guardar</button>
  <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button><br/><br/>
</div>

</form>
