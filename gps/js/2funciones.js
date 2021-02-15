 $(document).ready(function() {
 
	$('.dropdown-toggle').dropdownHover().dropdown();
	$("#tooltips li").tooltip({
        placement : 'right'
    });
	
 });

 
 function cargarPagina(titulo, pagina) {
   var title = '<h4>'+titulo+'</h4>';
   var body = 'php/'+pagina;
   $('#carga').modal('show');
   
	$.ajax({
	  url: body,
	  success: function(data) {
		$('#vntTitulo').html(title);
		$('#vntContenido').html(data);
		$('#carga').modal('hide');
		$('#vntModal').modal('show');
	  },
	  error: function(data) {
		console.log(data);
		$('#carga').modal('hide');
	  }
	});
 }
 
 function cargarPaginaModal(titulo, pagina) {
   var title = '<h4>'+titulo+'</h4>';
   var body = 'php/'+pagina;
   $('#carga').modal('show');
   
	$.ajax({
	  url: body,
	  success: function(data) {
		$('#vntTitulo2').html(title);
		$('#vntContenido2').html(data);
		$('#carga').modal('hide');
		$('#vntModal2').modal('show');
	  },
	  error: function(data) {
		console.log(data);
		$('#carga').modal('hide');
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
 
 
  