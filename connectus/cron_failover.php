<?php 

	require_once __DIR__.'/SMS/SMSController.php';
	require_once __DIR__.'/SMS/Models/Failover.php';


	/**
	 * Cargamos la API de envío de SMS
	 */

	$sms = new SMSController(0, 'Lyric');

    if(!is_null($sms->error)){
    	# si no logramos cargar la API abortamos misión
        echo "Error: ".$sms->error;
        return false;
    }
    
    $api = $sms->getAPI();

    /**
	 * Cargamos la clase Failover, que nos traera los sms 
	 * y datos correspondientes necesarios para el envío
	 */

	$failover = new Failover();

	# obtenemos los clientes con failover activo
	$clientes = $failover->getClientes();	

	foreach ($clientes as $key => $cliente)
	{
		# obtenemos los envíos con failover activo
		$envios = $failover->getEnvios($cliente['id_cliente']);

		foreach ($envios as $key => $envio)
		{
			# obtenemos el envío individual que necesita ser enviado 
			$detalle_envios = $failover->getDetalleEnvio($envio);

			if($envio['tipo_envio'] == 'masivo')
			{
				$campos = $failover->getCampos($envio['id_lista']);
				
				foreach ($detalle_envios as $key => $detalle)
				{
					# obtenemos los datos del contacto
					$contacto = $failover->getDatosContacto($detalle['id_contacto']);

					# una vez tenemos el envío individual procedemos a enviarlo
					if($channel = $api->sendFailoverMasivo($detalle, $campos, $contacto))
					{
						# si todo sale bien, actualizamos el estado del SMS
						$failover->updEstado('DELIVERY', $detalle['id_detalle_envio'], $channel);
					}
				}
			} else {
				# UNICO / API

				foreach ($detalle_envios as $key => $detalle)
				{
					# obtenemos los datos del contacto
					$contacto = $failover->getDatosContacto($detalle['id_contacto']);

					# una vez tenemos el envío individual procedemos a enviarlo
					if($channel = $api->sendFailoverIndividual($detalle, $contacto))
					{
						# si todo sale bien, actualizamos el estado del SMS
						$failover->updEstado('DELIVERY', $detalle['id_detalle_envio'], $channel);
					}
				}
			}
		}
	}