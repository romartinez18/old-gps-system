<style>

      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
 .modal-body {
   margin:10px 10px 10px 10px;
 }
 #pag {
   width:100%;
 }
 #map {
   height: 400px;
   width:100%;
 }
</style>
<body>

<form class="form-inline" role="form" name="vntAjax" id="vntAjax">
      
	  <div class="row"><!--  Fila 2 Mapa -->
	  	<div class="col-md-3">fsfsdasdsad</div>
		<div class="col-md-9" id='map'></div>
          </div><!--  Fin Fila 2  -->
	
	
 <div class="modal-footer" style="text-align:center;">
	<button type="button" class="btn btn-success" name="guardar" id="guardar"><span class="fa fa-save">&nbsp;</span>Guardar</button>
	<button type="button" class="btn btn-warning" name="cancelar" data-dismiss="modal" id="cancelar"><span class="fa fa-caret-square-o-right">&nbsp;</span>Cancelar</button><br/><br/>
 </div>

</form>

</body>
    <script>
     var map;
     
  function cargarBarraGeocerca() {
  var drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: google.maps.drawing.OverlayType.MARKER,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
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
  
  drawingManager.setMap(map);
  google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
  
  var element = event.overlay;
  
        // Eliminar figuras al hacer clic dentro del area
	google.maps.event.addListener(element, 'click', function(e) {
		element.setMap(null);
	});
	
	// Obtiene las rutas de los Circulos y rectangulos al Modificar
	google.maps.event.addListener(element, 'bounds_changed', function(e) {
	 if (event.type == google.maps.drawing.OverlayType.CIRCLE) {
		 var radius = element.getRadius();
		 var ruta = element.getBounds();
		 alert("Circulo dibujado: "+ruta+"  Radio: "+radius);
     }else if (event.type == google.maps.drawing.OverlayType.RECTANGLE) {
		 var ruta = element.getBounds();
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
	
	
	
	// Obtiene las rutas de los Circulos, Poligonos y Rectangulos
   if (event.type == google.maps.drawing.OverlayType.CIRCLE) {
    var radius = event.overlay.getRadius();
	var ruta = event.overlay.getBounds();
	alert("Circulo dibujado: "+ruta+"  Radio: "+radius);
   }else if (event.type == google.maps.drawing.OverlayType.POLYGON) {
    //var radius = event.overlay.getRadius();
	
	var ruta = event.overlay.getPath().getArray();
	alert("Poligono dibujado "+ruta);
   }else if (event.type == google.maps.drawing.OverlayType.RECTANGLE) {
    var ruta = event.overlay.getBounds();

	alert("Rectangulo dibujado "+ruta);
   }
  });

}

function loadMap() {
	var mapOpc = {
			zoom: 12,
			//mapTypeId:google.maps.MapTypeId.HYBRID,
			mapTypeId:google.maps.MapTypeId.TERRAIN,
			center: {lat:10.2437116667, lng:-67.582555},
			panControl: false,
			zoomControl: true,
			scaleControl: true
			//heading: 90
		};


        map = new google.maps.Map(document.getElementById('map'), mapOpc);
		  //console.log("LOAD MAP");
          cargarBarraGeocerca();
      }

    //window.onload = loadGoogleMapsScript();
	window.onload = loadMap();

  </script>
