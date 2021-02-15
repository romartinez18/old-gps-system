<?php 
//$callBack = $_GET['callback'];
//$contenidoMensaje = $_GET['contenidoMensaje'];
$contenidoMensaje = $_POST['contenidoMensaje'];
//$contenidoMensaje ='[{"telefono": "04128523967","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04127770306","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04125004183","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04125004716","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04127845045","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04123463320","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04125005142","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04125005048","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04127468917","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04127770306","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04127770384","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04127983012","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04121765218","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04123559908","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04128997176","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04121409697","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04127547943","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04121360646","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04128847648","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04128850231","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04121360685","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04128849320","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04121337505","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04128849826","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04128551032","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04128551225","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04120375122","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04127770027","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04128549134","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04126831663","mensaje": "adminip123456 186.95.3.168 9800"},{"telefono": "04126831797","mensaje": "adminip123456 186.95.3.168 9800"}]';

 //if (isset($_GET['callback'])) {

$array = json_decode($contenidoMensaje);
file_put_contents("data.txt","\n // -Contenido crudo:".$contenidoMensaje,FILE_APPEND);
//file_put_contents("data.txt","\n // -Contenido Decodificado:".$array,FILE_APPEND);
$insertar="";
if (isset($array) && !empty($array) && count($array) > 0) {
  foreach($array as $obj){
        $telefono = $obj->telefono;
        $mensaje = $obj->mensaje;
		file_put_contents("data.txt","\n // -telefono:".$telefono,FILE_APPEND);
		file_put_contents("data.txt","\n // -mensaje :".$mensaje,FILE_APPEND);
		$insertar.="INSERT IGNORE INTO salida(celular,mensaje,estatus) VALUES ('".$telefono."','".$mensaje."',0);";
	  // echo "Telefono: ".$telefono." Mensaje: ".$mensaje."<br>";
	
  }


$link = mysqli_connect("localhost", "root", "", "localiza_gps");

/* check connection */
if (mysqli_connect_errno()) {
   printf("Connect failed: %s\n", mysqli_connect_error());
   exit();
}

/* execute multi query */
if (mysqli_multi_query($link, $insertar)) {
   do {
       /* store first result set */
       if ($Error = mysqli_store_result($link)) {
           //do nothing since there's nothing to handle
           mysqli_free_result($Error);
       }
       /* print divider */
       if (mysqli_more_results($link)) {
           //I just kept this since it seems useful
           //try removing and see for yourself
       }
	   usleep(250000);
   } while (mysqli_next_result($link));
  $Error= 1;
}

/* close connection */
mysqli_close($link);

//$Error= mysql_query($insertar,$con);
//consulta todos los empleados



	 //mysqli_prepare($Sql);

	if (isset($Error) && $Error <= 0) {
	 //$x = json_decode('[{"Estatus":"Error","Codigo":-1,"Mensaje":"'.$Error.'"}]');
	  //echo json_encode($x);
	  echo json_encode("[{'Estatus':'Error','Codigo':-1,'Mensaje':'".$Error."'}]");
	} else {
	  //$x = json_decode('[{"Estatus":"Success","Codigo":0,"Mensaje":"Mensajes guardados en salida"}]');
	  //echo json_encode($x);
	  echo json_encode("[{'Estatus':'Success','Codigo':0,'Mensaje':'Mensajes guardados en salida'}]");
	}
  //echo $callback." MENSAJE DE PRUEBA"; 
 //}

 
 
} // if isset($array) && !empty($array) && count($array) > 0

?>