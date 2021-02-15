  var cfg_datatable;
  var uniqueField;
  var className;
  var simpleClassName;
  var camposTitulos;
  var camposBD;
  var nameForm;
  
  function configDatatable(pk, nameClass, singleName, camposTit, cmpBD) {
  // On page load: datatable
  uniqueField = pk;
  className = nameClass;
  simpleClassName = singleName;	
  nameForm = 'form_'+singleName;
  camposBD = cmpBD;
  camposTitulos = camposTit;
  
  var arr = [];
  
  		for(var i = 0; i < camposBD.length; i++) { 
		  arr[i] = {"data":camposBD[i]}; 
		}
		arr[camposBD.length]= {"data":"functions", "sClass": "functions"}; 
  cfg_datatable = $('#table_'+className).dataTable({
    "ajax": "services/service."+className+".php?accion=get_"+className,
    "columns": arr,
    "aoColumnDefs": [
      { "bSortable": false, "aTargets": [-1] }
    ],
    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
	"language": {
                "url": "js/lang/datatables.spanish.json"
            }
    /*"oLanguage": {
      "oPaginate": {
        "sFirst":       " ",
        "sPrevious":    " ",
        "sNext":        " ",
        "sLast":        " ",
      },
      "sLengthMenu":    "Records per page: _MENU_",
      "sInfo":          "Total of _TOTAL_ records (showing _START_ to _END_)",
      "sInfoFiltered":  "(filtered from _MAX_ total records)"
    }*/
  });
  
  }

//$(document).ready(function(){
	
  function executeDatatable() { 
  
  // On page load: form validation
  jQuery.validator.setDefaults({
    success: 'valid',
    rules: {
      fiscal_year: {
        required: true,
        min:      2000,
        max:      2025
      }
    },
    errorPlacement: function(error, element){
      error.insertBefore(element);
    },
    highlight: function(element){
      $(element).parent('.field_container').removeClass('valid').addClass('error');
    },
    unhighlight: function(element){
      $(element).parent('.field_container').addClass('valid').removeClass('error');
    }
  });
  
  var formulario = $('#'+nameForm);
  formulario.validate();

  // Show message
  function show_message(message_text, message_type){
    $('#message').html('<p>' + message_text + '</p>').attr('class', message_type);
    $('#message_container').show();
    if (typeof timeout_message !== 'undefined'){
      window.clearTimeout(timeout_message);
    }
    timeout_message = setTimeout(function(){
      hide_message();
    }, 8000);
  }
  // Hide message
  function hide_message(){
    $('#message').html('').attr('class', '');
    $('#message_container').hide();
  }

  // Show loading message
  function show_loading_message(){
    $('#loading_container').show();
  }
  // Hide loading message
  function hide_loading_message(){
    $('#loading_container').hide();
  }

  // Show lightbox
  function show_lightbox(){
    $('.lightbox_bg').show();
    $('.lightbox_container').show();
  }
  // Hide lightbox
  function hide_lightbox(){
    $('.lightbox_bg').hide();
    $('.lightbox_container').hide();
  }
  // Lightbox background
  $(document).on('click', '.lightbox_bg', function(){
    hide_lightbox();
  });
  // Lightbox close button
  $(document).on('click', '.lightbox_close', function(){
    hide_lightbox();
  });
  // Escape keyboard key
  $(document).keyup(function(e){
    if (e.keyCode == 27){
      hide_lightbox();
    }
  });
  
  // Hide iPad keyboard
  function hide_ipad_keyboard(){
    document.activeElement.blur();
    $('input').blur();
  }

  // Add button
  $(document).on('click', '#add_'+simpleClassName, function(e){
    e.preventDefault();
    //$('.lightbox_content h2').text('Add '+simpleClassName);
    //$('#'+nameForm+' button').text('Add '+simpleClassName);
    $('#'+nameForm).attr('class', 'form add');
    $('#'+nameForm).attr('data-id', '');
    $('#'+nameForm+' .field_container label.error').hide();
    $('#'+nameForm+' .field_container').removeClass('valid').removeClass('error');
	  for(var i = 0; i < camposBD.length; i++) {
		  $('#'+nameForm+' #'+camposBD[i]).val('');
	  }
    show_lightbox();
  });

  // Add submit form
  $(document).on('submit', '#'+nameForm+'.add', function(e){
    e.preventDefault();
    // Validate form
    if (formulario.valid() == true){
      // Send information to database
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var form_data = $('#'+nameForm).serialize();
	  console.log(form_data);
      var request   = $.ajax({
        url:          "services/service."+className+'.php?accion=add_'+simpleClassName,
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          // Reload datable
          cfg_datatable.api().ajax.reload(function(){
            hide_loading_message();
            var unique = $('#'+uniqueField).val();
            show_message(simpleClassName+" '" + unique + "' agregado correctamente.", 'success');
          }, true);
        } else {
			console.log(output);
          hide_loading_message();
          show_message('Add request failed', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus, err){
        hide_loading_message();
		var err = JSON.parse(jqXHR.responseText);
		alert(err.Message);
        show_message('Add request failed: ' + jqXHR.responseText+'  '+textStatus, 'error');
      });
    }
  });

  // Edit button
  $(document).on('click', '.function_edit a', function(e){
    e.preventDefault();
    // Get information from database
    show_loading_message();
    var id      = $(this).data('id');
    var request = $.ajax({
      url:          "services/service."+className+'.php?accion=get_'+simpleClassName,
      cache:        false,
      data:         'id=' + id,
      dataType:     'json',
      contentType:  'application/json; charset=utf-8',
      type:         'get'
    });
    //request.done(function(output){
	request.done(function(output){
      if (output.result == 'success'){
        //$('.lightbox_content h2').text('Edit '+simpleClassName);
        //$('#'+nameForm+' button').text('Edit '+simpleClassName);
        $('#'+nameForm).attr('class', 'form edit');
        $('#'+nameForm).attr('data-id', id);
        $('#'+nameForm+' .field_container label.error').hide();
        $('#'+nameForm+' .field_container').removeClass('valid').removeClass('error');
		$.each(output.data[0], function (key, value) {
			$('#'+nameForm+' #'+key).val(value);	
		});
        hide_loading_message();
        show_lightbox();
      } else {
        hide_loading_message();
        show_message('Information request failed', 'error');
      }
    });
    request.fail(function(jqXHR, textStatus){
      hide_loading_message();
      show_message('Information request failed: ' + textStatus, 'error');
    });
  });
  
  // Edit submit form
  $(document).on('submit', '#'+nameForm+'.edit', function(e){
    e.preventDefault();
    // Validate form
    if (formulario.valid() == true){
      // Send information to database
      hide_ipad_keyboard();
      hide_lightbox();
      show_loading_message();
      var id        = $('#'+nameForm).attr('data-id');
      var form_data = $('#'+nameForm).serialize();
      var request   = $.ajax({
        url:          "services/service."+className+'.php?accion=edit_'+simpleClassName+'&id=' + id,
        cache:        false,
        data:         form_data,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          // Reload datable
          cfg_datatable.api().ajax.reload(function(){
            hide_loading_message();
            var unique = $('#'+uniqueField).val();
            show_message(simpleClassName+" '" + unique + "' editado correctamente.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('Edit request failed', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Edit request failed: ' + textStatus, 'error');
      });
    }
  });
  
  // Delete 
  $(document).on('click', '.function_delete a', function(e){
    e.preventDefault();
    var unique = $(this).data('name');
    if (confirm("Desea eliminar '" + unique + "'?")){
      show_loading_message();
      var id      = $(this).data('id');
      var request = $.ajax({
        url:          "services/service."+className+'.php?accion=delete_'+simpleClassName+'&id=' + id,
        cache:        false,
        dataType:     'json',
        contentType:  'application/json; charset=utf-8',
        type:         'get'
      });
      request.done(function(output){
        if (output.result == 'success'){
          // Reload datable
          cfg_datatable.api().ajax.reload(function(){
            hide_loading_message();
            show_message(simpleClassName+" '" + unique + "' eliminado correctamente.", 'success');
          }, true);
        } else {
          hide_loading_message();
          show_message('Delete request failed', 'error');
        }
      });
      request.fail(function(jqXHR, textStatus){
        hide_loading_message();
        show_message('Delete request failed: ' + textStatus, 'error');
      });
    }
  });
  
  
  } // end exec dataTable

//});