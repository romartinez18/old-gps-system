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

<div id="reportes">



<div class="panel panel-default">
   <div class="panel-heading">
       <div class="panel-title">
      <div class="row">
           
           <div class="col-md-4">Desde <input id="finicio" name="finicio"  type="datetime-local"/></div>
           <div class="col-md-4">Hasta <input id="ffin" name="ffin" type="datetime-local"/></div>
           <div class="col-md-2"><button id="boton" type="submit" class="btn btn-primary btn-block " id="boton"><span class="glyphicon glyphicon-search"></span> Buscar</div>
           <p id='buscar'></p>
       </div>
       </div>
   </div>
</div>


</div>
<div id='tablareporte'></div>

<script>

var barra = "<div class='progress'><div class='progress-bar progress-bar-striped active' role='progressbar' aria-valuenow='CARGANDO' aria-valuemin='0' aria-valuemax='100' style='width: 100%'></div>";
var direccion=[];

var delay = 50;
//$.each(direccion, function( index, value ) {
 //alert( index + ": " + value );
//console.log(value);

  var geocoder = new google.maps.Geocoder();
   var nextAddress = 0;
 /* var pos = value.split(",");
  var posicion= new google.maps.LatLng(parseFloat(pos[0]),parseFloat(pos[1]));
  console.log("Lat: "+pos[0]+"  Lng: "+pos[1]);
   console.log("Posicion: "+posicion.lat()+"."+posicion.lng());
   
  geocoder.geocode({'latLng': posicion}, function(results, status) {
       if (status === google.maps.GeocoderStatus.OK) {
           dir = results[0].formatted_address;
       } else {
        dir = "Desconocido";
       }
       console.log("Estatus: "+status);
       console.log("Direccion: "+results[0].formatted_address);
       $('#'+index).html(dir);
    });
*/ 
 // ====== Geocoding ======
//var  address= value;

//var addresses = direccion;

function getAddress(address, next) {
        var pos = address.split(",");
        var dir = "";
        var siguente =0;
  	var posicion= new google.maps.LatLng(parseFloat(pos[0]),parseFloat(pos[1]));
  	
  	//console.log("Lat: "+pos[0]+"  Lng: "+pos[1]);
  	// console.log("Posicion: "+posicion.lat()+"."+posicion.lng());
   
        geocoder.geocode({'latLng': posicion}, function (results,status)
          { 
            // If that was successful
            if (status == google.maps.GeocoderStatus.OK) {
              // Lets assume that the first marker is the one we want
              dir = results[0].formatted_address;
              siguente = (nextAddress) + 1;
              $('#'+siguente ).html(barra);
              $('#'+nextAddress).html(dir);
              
            }
            // ====== Decode the error status ======
            else {
              // === if we were sending the requests to fast, try this one again and increase the delay
              if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
                nextAddress--;
                delay++;
              } else {
                var reason="Code "+status;
                var msg = 'address="' + address+ '" error=' +reason+ '(delay='+delay+'ms)<br>';
               // console.log(msg);
                siguente = (nextAddress) + 1;
                $('#'+siguente ).html(barra);
                $('#'+nextAddress).html("Desconocido");
              }   
            }
            next();
          }
        );
} // function getAddress

// ======= Function to call the next Geocode operation when the reply comes back
 
  function theNext() {
        if (nextAddress < direccion.length) {
          setTimeout('getAddress("'+direccion[nextAddress]+'",theNext)', delay);
          nextAddress++;
        }
  }
  
  
$(document).ready(function(){

$("#boton").click(function(){


 var idvehiculo= idvehiculocarro;
 var finicio= $("#finicio").val();
 var ffin= $("#ffin").val();
 
 // Returns successful data submission message when the entered information is stored in database.
 var dataString = 'idvehiculo='+ idvehiculo+ '&finicio='+ finicio+ '&ffin='+ ffin;
 
  if(finicio==''||ffin=='') {
    alert("Debe colocar la fecha y la hora"); 
  } else {
    // AJAX Code To Submit Form.
    $("#buscar").html(barra);
    $.ajax({
     type: "POST",
     url: "json/reporte.php",
     data: dataString,
     cache: false,
      success: function(result){

      var objtabla= jQuery.parseJSON(result);
      console.log(result);
      console.log(objtabla);

      var tablar='<table id="reportetabla" class="table table-bordered"><thead class=" text-center"><tr><th>Fecha</th>';
      tablar += '<th>direccion</th><th>velocidad</th></tr></thead><tbody></tbody></table>';
      
      $('#tablareporte').html(tablar);
      var i = 1;
//   var direccion='';
   
      var j = 0;
      
       $.each(objtabla, function( key, val ) {
	direccion[j] = val.latitud+','+val.longitud;
	//direccion=direccion+''+val.latitud+','+val.longitud+'';
	tablar1= '<tr><td>'+val.fechahoraserver+'</td><td id='+i+'>'+val.latitud+','+val.longitud+'</td><td>'+val.velocidad+'</td></tr>';
	i++;
        j++;
	$('#reportetabla > tbody').append(tablar1);
       }); // for each
       
       //console.log(direccion);
$("#buscar").html("");
      var oTable = $('#reportetabla ').dataTable({
        "oTableTools": {
            "sSwfPath": "componentes/DataTable/exportador/copy_csv_xls_pdf.swf"
        },
	"oLanguage": {
            "sUrl": "componentes/DataTable/idioma.txt"
        },
        "sDom": "<'row-fluid'<'span4'l><'span4 'T><'span4'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
       
      }); // dataTable

      // ======= Call that function for the first time =======
      theNext();

//}); // $each
//}
    }}); // llamada ajax a json/reporte.php


  } // si campos fecha completos

//return false;

}); // boton clic


}); // Ready
 
</script>