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


	$(document).on('click', '.delete_archivo', function(e)
	{
		e.preventDefault();
		var id = $(this).attr('data-id');

		if(confirm("¿Desea eliminar el archivo?")){
			delete_archivo(id);
		}else{
			return false;
		}
		
		
	});



	$('#button-filter').on('click', function() {

		var token = $("#token").val();

        var url = 'index.php?route=reportes/sms&token='+ getURLVar('token');

        var filter_name = $('input[name=\'filter_name\']').val();

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name); 
        }

        var filter_fecha = $('input[name=\'filter_fecha\']').val();

        if (filter_fecha) {
            url += '&filter_fecha=' + encodeURIComponent(filter_fecha);
        }

        var filter_fecha_hasta = $('input[name=\'filter_fecha_hasta\']').val();

        if (filter_fecha_hasta) {
            url += '&filter_fecha_hasta=' + encodeURIComponent(filter_fecha_hasta); 
        }

        location = url;
    });


	$('#refresh').on('click' , function(){
		var d = document.getElementById("spin");
		d.className = d.className + " fa-spin";		

		location = window.location.href;
	});

	function delete_archivo(id)
	{

		var datos = {
			'repo':id
		}

		$.ajax({
			async:true,
			type: 'POST',
			data: datos,
			url: 'index.php?route=reportes/sms/delete&token=' + getURLVar('token'),
			dataType: 'json',
			success: function(data) {
				
				if(data.estado){

					alert("Reporte: "+id+" eliminado!");

					$("#tr_"+id).hide('slow', function(){
						$(this).remove();
					})

				}else{
					alert("Error al tratar de eliminar reporte!");
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



