$(document).ready(function(){


	$(document).on('click', '#slc_all', function(){

		if( $(this).prop('checked') ) {
			// se acaba de seleccionar
		    $(".chk_prog").prop('checked', true);
		}else{
			// se acaba de deseleccionar
			$(".chk_prog").prop('checked', false);
		}

	});

	$(document).on('click', '#btn-upd-sms', function(){
		var id = $("#idsms").val();
		update_sms_programado(id);
	});

	$(document).on('click', '#edit-unico', function(){
		var id = $(this).attr('data-id');
		obtenerFechaSMS(id);
	});

	$(document).on('click', '#edit-masivo', function(){
		var id = $(this).attr('data-id');
		obtenerFechaSMS(id);
	});

	$(document).on('click', '#delete-progamados', function(){
		delete_programados();
	});



	$('#button-filter').on('click', function() {

		var token = $("#token").val();

        var url = 'index.php?route=sms/programados&token='+ getURLVar('token')+"&tipo="+ getURLVar('tipo');

        var filter_nombre = $('input[name=\'filter_nombre\']').val();

        if (filter_nombre) {
            url += '&filter_nombre=' + encodeURIComponent(filter_nombre); 
        }

        var filter_fecha = $('input[name=\'filter_fecha\']').val();

        if (filter_fecha) {
            url += '&filter_fecha=' + encodeURIComponent(filter_fecha); 
        }

        location = url;
    });


	$('#refresh').on('click' , function(){
		var d = document.getElementById("spin");
		d.className = d.className + " fa-spin";		

		location = window.location.href;
	});

	function update_sms_programado(idsms)
	{

		var fecha 	= $("#input_fecha").val();	if(fecha == ''){alert("debe ingresar una fecha."); return false;}
		var hora 	= $("#input_hora").val();	if(hora == ''){alert("debe ingresar una hora."); return false;}


		var datos = {
			'sms':idsms,
			'fecha':fecha,
			'hora':hora
		}

		$.ajax({
			async:true,
			type: 'POST',
			data: datos,
			url: 'index.php?route=sms/programados/updSMS&token=' + getURLVar('token'),
			dataType: 'json',
			success: function(data) {
				
				if(data.estado){

					location = window.location.href;

				}else{

					console.log("Problemas al actualizar los datos.");
				}

			}
		});	
	}

	function obtenerFechaSMS(idsms)
	{
		var datos = {
			'sms':idsms
		}

		$.ajax({
			async:true,
			type: 'POST',
			data: datos,
			url: 'index.php?route=sms/programados/getDataSMS&token=' + getURLVar('token'),
			dataType: 'json',
			success: function(data) {
				
				if(data.estado){

					var nombre = data.nombre_envio;
					var fecha = data.fecha;
					var hora = data.hora;

					$("#input_titulo").val(nombre);
					$("#input_fecha").val(fecha);
					$("#input_hora").val(hora);
					$("#idsms").val(idsms);

				}else{

					alert("Problemas al obtener datos.");
					console.log(data.error);
				}

			}
		});	
	}


	function delete_programados(){

		var slc = new Array();

		$('.chk_prog:checked').each(function(){
		    slc.push($(this).attr('data-id'));
		});

		if(slc.length == 0){
			alert("debe seleccionar algunos mensajes.");
			return false;
		}

		var sms = slc.join();

		var datos = {
			'sms': sms
		}

		$.ajax({
			async:true,
			type: 'POST',
			data: datos,
			url: 'index.php?route=sms/programados/delete&token=' + getURLVar('token'),
			dataType: 'json',
			success: function(data) {
				
				if(data.estado){


					$.each(data.eliminados, function(a,idsms){
						$("#tr_"+idsms).remove();
					});

					location = window.location.href;

				}else{
					location = window.location.href;
					console.log(data.error);
				}

			}
		});	

	}


	function error () {
		alert('Este envio no tiene datos, presione actualizar para refrescar estados.');
	}

	function getURLVar(key) {
		var value = [];

		var query = String(document.location).split('?');

		if (query[1]) {
			var part = query[1].split('&');

			for (i = 0; i < part.length; i++) {
				var data = part[i].split('=');

				if (data[0] && data[1]) {
					value[data[0]] = data[1];
				}
			}

			if (value[key]) {
				return value[key];
			} else {
				return '';
			}
		}
	}

});



