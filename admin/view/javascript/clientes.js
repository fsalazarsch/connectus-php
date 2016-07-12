$(document).ready(function(){

	getListaDriver('MAIL', 'driver_mail');
	getListaDriver('SMS', 'driver_sms');


	$(document).on('click', '#btn_edit_driver', function()
	{
		var id_cliente = $(this).attr('data-cliente');
		get_info(id_cliente);
		get_info_failover(id_cliente);
	});

	$(document).on('click', '#btn_update_driver', function()
	{
		var id_cliente = $("#cliente_seleccionado").val();
		if(id_cliente == ''){ alert("Error al tratar de editar driver."); return false;}

		updateDriver(id_cliente);
		updateFailover(id_cliente);

	});

	function get_info_failover(id_cliente)
	{
		if(id_cliente 	== ''){alert("Problemas al detectar cliente."); return false;}

		var datos = {
			'cliente'	: id_cliente
		}

		$.ajax({
			async:true,
			type: 'POST',
			data: datos,
			url: 'index.php?route=cliente/failover/getInfo&token=' + getURLVar('token'),
			dataType: 'json',
			success: function(data) {
				
				if(data.estado)
				{
					$('#chk_failover').prop('checked', true);

				}else{
					$('#chk_failover').prop('checked', false);
				}

			}
		});	
	}

	function get_info(id_cliente)
	{
		if(id_cliente 	== ''){alert("Problemas al detectar cliente."); return false;}

		var datos = {
			'cliente'	: id_cliente
		}

		$.ajax({
			async:true,
			type: 'POST',
			data: datos,
			url: 'index.php?route=conector/driver/getInfo&token=' + getURLVar('token'),
			dataType: 'json',
			success: function(data) {
				
				if(data.estado)
				{
					$("#cliente_seleccionado").val(id_cliente);
					$("#nombre_cliente").text(data.cliente);
					$('#driver_mail option[value='+data.driver_mail+']').prop('selected', true);
					$('#driver_sms option[value='+data.driver_sms+']').prop('selected', true);

				}else{
					$("#cliente_seleccionado").val('');
					console.log("Problemas al actualizar los datos.");
				}

			}
		});	

	}

	function updateFailover(id_cliente)
	{
		var chk_failover = false;

		if(id_cliente 	== ''){return false;} // si no existe el id cliente abortamos

		if( $('#chk_failover').prop('checked') ) {
		    chk_failover = true;
		}

		
		var datos = {
			'id_cliente'	: id_cliente,
			'failover'	: chk_failover,
		}

		$.ajax({
			async:true,
			type: 'POST',
			data: datos,
			url: 'index.php?route=cliente/failover/updFailover&token=' + getURLVar('token'),
			dataType: 'json',
			success: function(data) {
				
				if(!data.estado){
					console.log("Problemas al actualizar los datos.\n"+data.error);
				}

			}
		});

		return false;
	}

	function updateDriver(id_cliente)
	{
		if(id_cliente 	== ''){alert("Problemas al detectar cliente."); return false;}
		var driver_mail = $("#driver_mail").val();	if(driver_mail 	== 0){alert("Debe seleccionar un Driver para el envío de Mails."); return false;}
		var driver_sms 	= $("#driver_sms").val();	if(driver_sms 	== 0){alert("Debe seleccionar un Driver para el envío de SMSs."); return false;}



		var datos = {
			'id_cliente'	: id_cliente,
			'driver_mail'	: driver_mail,
			'driver_sms'	: driver_sms
		}

		$.ajax({
			async:true,
			type: 'POST',
			data: datos,
			url: 'index.php?route=conector/driver/updConector&token=' + getURLVar('token'),
			dataType: 'json',
			success: function(data) {
				
				if(data.estado)
				{
					$("#cliente_seleccionado").val('');
					$('#modal_edit_driver').modal('hide');
					
					alert("Actualizado correctamente!");

				}else{
					$("#cliente_seleccionado").val('');
					console.log("Problemas al actualizar los datos.");

					alert("Error al tratar de actualizar drivers!");
				}

			}
		});	
	}

	

	function getListaDriver(driver, select)
	{

		if(!driver || !select){
			alert("Error al obtener datos");
			console.log("problemas al obtener driver y/o select");
			console.log(driver);
			console.log(select);
			return false;
		}

		var datos = {
			'driver'	: driver
		}

		$.ajax({
			async:true,
			type: 'POST',
			data: datos,
			url: 'index.php?route=conector/driver/getLista&token=' + getURLVar('token'),
			dataType: 'json',
			success: function(data) {
				
				if(data.estado){

					$.each(data.conectores, function(index, value){

						$("#"+select).append("<option value='"+value.id_conector+"'>"+value.glosa+"</option>");
					});

				}else{

					console.log("Problemas al actualizar los datos.");
				}

			}
		});

	}


	function getURLVar(key)
	{
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