 $(document).ready(function() {
 
	$('.dropdown-toggle').dropdownHover().dropdown();
	$("#tooltips li").tooltip({
        placement : 'right'
    });
	
 });
 
function diasEntreFechas(f1)
{
 // Here are the two dates to compare
var fechaActual = new Date()
var diaActual = fechaActual.getDate();
var mmActual = fechaActual.getMonth() + 1;
var yyyyActual = fechaActual.getFullYear();
var date1 = ''+f1+'';
//var date1='2016-02-22';
var date2 = ''+yyyyActual+'-'+mmActual+'-'+diaActual+'';


// First we split the values to arrays date1[0] is the year, [1] the month and [2] the day
date1 = date1.split('-');
date2 = date2.split('-');

// Now we convert the array to a Date object, which has several helpful methods
date1 = new Date(date1[0], date1[1], date1[2]);
date2 = new Date(date2[0], date2[1], date2[2]);

// We use the getTime() method and get the unixtime (in milliseconds, but we want seconds, therefore we divide it through 1000)
date1_unixtime = parseInt(date1.getTime() / 1000);
date2_unixtime = parseInt(date2.getTime() / 1000);

// This is the calculated difference in seconds
var timeDifference = date2_unixtime - date1_unixtime;

// in Hours
var timeDifferenceInHours = timeDifference / 60 / 60;

// and finaly, in days :)
var timeDifferenceInDays = timeDifferenceInHours  / 24;
  // console.log(timeDifferenceInDays);
 //  console.log('Dias2: ' + date2);
 return timeDifferenceInDays;
}

function ocultarAdminvehiculos() {

 $('#adm-vehiculos').css('display','none');
 $('#adm-vehiculosoff').css('display','');
 }

function cargarAdminvehiculos(titulo, pagina) {
$('#preloader').css('z-index','2');
$('#adm-vehiculosoff').css('display','none');
   var title = '<h4>'+titulo+'</h4>';
   var body = 'php/'+pagina;

   
	$.ajax({
	  url: body,
	  type: 'POST',
	  data: $('#vntAjax').serialize(),
	  success: function(data) {
                
                $('#adm-vehiculos').css('display','');
	        $('#adm-vehiculos-contenido').html(data);
                $('#preloader').css('z-index','-2');
		
	  }, 
	  error: function (data, textStatus, xhr) { 
	           $('#adm-vehiculos').css('display','');
	        console.log("(" + xhr.responseText + ")");
	  }
	});
 }

 function cargarMensaje(id) {
   alert(id);
 }

 function ComandosGPS(id) {

  cargarPaginaModal('Lista de comandos disponibles', 'lcomandos.php');
idvehiculocarro = id;

 }
 
 function ejecutarOperacion(operacion, pagina) {
   var body = 'php/'+pagina;
   $('#carga').show();
   
	$.ajax({
	  url: body,
	  type: 'POST',
	  data: $('#vntAjax').serialize(),
	  success: function(data) {
	     alert(data);
 	     $('#carga').hide();
	  }, 
	  error: function (data, textStatus, xhr) { 
	        $('#carga').hide();
	        console.log("(" + xhr.responseText + ")");
	  }
	});
 }
 
 function cargarPagina(titulo, pagina) {
   var title = '<h4>'+titulo+'</h4>';
   var body = 'php/'+pagina;
   $('#carga').show();
   $('#preloader').css('z-index','2');
	$.ajax({
	  url: body,
	  type: 'POST',
	  data: $('#vntAjax').serialize(),
	  success: function(data) {
		$('#vntTitulo').html("");
		$('#vntContenido').html("");
		$('#vntTitulo').html(title);
		$('#vntContenido').html(data);
		$('#vntModal').modal('show');
		$('#carga').hide();
                $('#preloader').css('z-index','-2');
	  }, 
	  error: function (data, textStatus, xhr) { 
	        $('#carga').hide();
	        console.log("(" + xhr.responseText + ")");
	  }
	});
 }
 
  function cargarRutaPagina(titulo, pagina) {
   var title = '<h4>'+titulo+'</h4>';
   var body = pagina;
   $('#carga').show();
   $('#preloader').css('z-index','2');
	$.ajax({
	  url: body,
	  type: 'POST',
	  data: $('#vntAjax').serialize(),
	  success: function(data) {
                
		$('#vntTitulo').html(title);
		$('#vntContenido').html(data);
		$('#vntModal').modal('show');
		$('#carga').hide();
                $('#preloader').css('z-index','-2');
	  }, 
	  error: function (data, textStatus, xhr) { 
	        $('#carga').hide();
	        console.log("(" + xhr.responseText + ")");
	  }
	});
 }

 
  function cargarListado(titulo, pagina) {
   var title = '<h4>'+titulo+'</h4>';
   var body = 'listados/'+pagina;
   $('#carga').show();
    $('#preloader').css('z-index','2');
	$.ajax({
	  url: body,
	 /* type: 'POST',
	  data: {$('#vntAjax').serialize();},*/
	  success: function(data) {
                 
		$('#vntTitulo').html(title);
		$('#vntContenido').html(data);
		$('#vntModal').modal('show');
		$('#carga').hide();
                $('#preloader').css('z-index','-2');
	  }, 
	  error: function (data, textStatus, xhr) { 
	        $('#carga').hide();
	        console.log("(" + xhr.responseText + ")");
	  }
	});
 }

 function iniciarSesion() {
    var user = $('#usuario').val();
    var pass = $('#clave').val();
    $('#carga').show();

	$.ajax({
	  url: 'php/vsesion.php',
	  type: 'POST',
	  data: {vUser:user, vPass:pass},
	  success: function(data) {
	     $('#carga').hide();
	     data = $.trim(data);
		// Redireccionar
		if (data == 'ok') {
		  alert('Bienvenido');
		  window.location.href = 'mapa.php';
		} else if (data == 'admin') {
		  window.location.href = '../gps1/admin.php';
		} else {
		  alert(data);
		}
	  }, 
	  error: function (data, textStatus, xhr) { 
	  	$('#carga').hide();
		console.log("(" + xhr.responseText + ")");
	  }
	});
	
 }
 
  function cerrarSesion() {
    $('#carga').show();
	
	$.ajax({
	  url: 'php/sesion.php',
	  type: 'POST',
	  success: function(data) {
	     $('#carga').hide();
	     data = $.trim(data);
		// Redireccionar
		if (data == 'ok') {
		  window.location.href = 'index.php';
		} else {
		  alert(data);
		}
	  }, 
	  error: function (data, textStatus, xhr) { 
	  	$('#carga').hide();
		console.log("(" + xhr.responseText + ")");
	  }
	});
	
 }
 
 /*function cargarPagina(titulo, pagina, form) {
   var title = '<h4>'+titulo+'</h4>';
   var body = 'php/'+pagina;
   
	var request = $.ajax({
	  type: "POST",
	  url: body,
	  data: $(form).serialize(),
	 
	  success: function(data) {
		$('#vntTitulo').html(title);
		$('#vntContenido').html(data);
		$('#vntModal').modal('show');
	  },
	  error: function (data, textStatus, jqXHR) { 
		  var err = eval("(" + xhr.responseText + ")");
		  console.log(err.Message);
	  }*/
	//});
 //}
 
 function cargarPaginaModal(titulo, pagina) {
   var title = '<h4>'+titulo+'</h4>';
   var body = 'php/'+pagina;
   $('#carga').show();
   $('#preloader').css('z-index','2');
	$.ajax({
	  url: body,
	  success: function(data) {
	     $('#carga').hide();
		$('#vntTitulo2').html(title);
		$('#vntContenido2').html(data);
		$('#vntModal2').modal('show');
                $('#preloader').css('z-index','-2');
	  },error: function (data, textStatus, xhr) { 
	  	$('#carga').hide();
		console.log("(" + xhr.responseText + ")");
	  }
	});
 }
 
  function cargarRutaPaginaModal(titulo, pagina) {
   var title = '<h4>'+titulo+'</h4>';
   var body = pagina;
   $('#carga').show();
   $('#preloader').css('z-index','2');
	$.ajax({
	  url: body,
	  success: function(data) {
	     $('#carga').hide();
		$('#vntTitulo2').html(title);
		$('#vntContenido2').html(data);
		$('#vntModal2').modal('show');
                $('#preloader').css('z-index','-2');
	  },error: function (data, textStatus, xhr) { 
	  	$('#carga').hide();
		console.log("(" + xhr.responseText + ")");
	  }
	});
 }
 
 /*
 function cajaAjax(idDiv, pagina, datos) {
    var vCaja = $('#'+idDiv);
	var vDatos = datos.split('|');
	var ajaxDatos = [];
	var vTotalDatos = vDatos.length();
	 $.ajax({
		url: pagina,
		type: "POST",
		dataType: "json",
		data: JSON.stringify({vDatos}),
		  success: function(msg){
			$('.answer').html(msg);
		  },
		  
		
	 });
	 
 } 
 */
 
 function randomColor() {
	return '#' + ("000000" + Math.random().toString(16).slice(2, 8).toUpperCase()).slice(-6);
 }
 
function cargarBarraGeocerca() {
  var drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.MARKER,
    drawingControl: true,
    drawingMode: null,
    drawingControlOptions: {
      position: google.maps.ControlPosition.BOTTOM_CENTER,
      drawingModes: [
        google.maps.drawing.OverlayType.CIRCLE,
        google.maps.drawing.OverlayType.POLYGON,
        google.maps.drawing.OverlayType.RECTANGLE
      ]
    },
    circleOptions: {
      fillColor: '#00ab45',
      fillOpacity: .8,
      strokeWeight: 1,
      clickable: true,
      editable: true,
      zIndex: 1
    },
	rectangleOptions: {
      fillColor: '#00ab45',
      fillOpacity: .8,
      strokeWeight: 1,
      clickable: true,
      editable: true,
      zIndex: 1
    }, 
	polygonOptions: {
      fillColor:"#1E90FF",
      strokeColor:"#1E90FF",
      clickable: true,
      editable: true,
      zIndex: 1
    },
    polylineOptions: {
      strokeColor:"#FF273A",
      strokeWeight: 1
    }
  });
  
  drawingManager.setMap(mapa);
  google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
  
  var element = event.overlay;
  
        // Eliminar figuras al hacer clic dentro del area
	/*google.maps.event.addListener(element, 'click', function(e) {
		element.setMap(null);
	});*/
	
	
	// Obtiene las rutas de los Circulos, Poligonos y Rectangulos
   if (event.type == google.maps.drawing.OverlayType.CIRCLE) {
    var radius = event.overlay.getRadius();
	var ruta = element.getCenter().lat()+","+element.getCenter().lng();
	alert("Circulo dibujado: "+ruta+"  Radio: "+radius);
   }else if (event.type == google.maps.drawing.OverlayType.POLYGON) {
   	var ruta = event.overlay.getPath().getArray();
   	//var ruta = event.overlay.getPaths().getArray();
	alert("Poligono dibujado "+ruta[0][0]);
   }else if (event.type == google.maps.drawing.OverlayType.RECTANGLE) {
    var ruta = event.overlay.getBounds().getNorthEast().lat()+","+event.overlay.getBounds().getNorthEast().lng();
    ruta = ruta + ","+ event.overlay.getBounds().getSouthWest().lat()+","+event.overlay.getBounds().getSouthWest().lng();
  
	alert("Rectangulo dibujado "+ruta);
   }
	
	
	
	// Obtiene las rutas de los Circulos y rectangulos al Modificar
	google.maps.event.addListener(element, 'bounds_changed', function(e) {
	 if (event.type == google.maps.drawing.OverlayType.CIRCLE) {
		 var radius = element.getRadius();
		 var ruta = element.getCenter().lat()+","+element.getCenter().lng();
		 alert("Circulo dibujado: "+ruta+"  Radio: "+radius);
     }else if (event.type == google.maps.drawing.OverlayType.RECTANGLE) {
		     var ruta = event.overlay.getBounds().getNorthEast().lat()+","+event.overlay.getBounds().getNorthEast().lng();
    ruta = ruta + ","+ event.overlay.getBounds().getSouthWest().lat()+","+event.overlay.getBounds().getSouthWest().lng();
		 alert("Rectangulo dibujado "+ruta);
     }
   
	});
	
	// Obtiene las rutas de los Poligonos al Modificar
    google.maps.event.addListener(element.getPath(), 'set_at', function(index, obj) {
		if (event.type == google.maps.drawing.OverlayType.POLYGON) {
		var ruta = element.getPath().getArray();
		  alert("Poligono dibujado "+ruta);
		}
	});
	
	
  });

 

}
 
 
  