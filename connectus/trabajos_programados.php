<?php	

	require_once __DIR__.'/SMS/SMSController.php';
	
	$conDB = null;
	

	define('DB_DRIVER_CONNECTUS', 'mpdo');
	define('DB_HOSTNAME_CONNECTUS', 'localhost');
	define('DB_USERNAME_CONNECTUS', 'connectu_connect');
	define('DB_PASSWORD_CONNECTUS', 'cOnNectUs_05041977_.#');
	define('DB_DATABASE_CONNECTUS', 'connectu_adm_connectus');
	define('DB_PORT_CONNECTUS', '3306');
	define('DB_PREFIX_CONNECTUS', '');

	define('HTTPS_CATALOG', 'http://connectus.cl/');
	
	$string_connection = "mysql:host=".DB_HOSTNAME_CONNECTUS.";dbname=".DB_DATABASE_CONNECTUS. ";charset=utf8";
	$conexion = new PDO($string_connection,DB_USERNAME_CONNECTUS,DB_PASSWORD_CONNECTUS);

	$sql = 'SELECT datos_envio_programado, id_envio, tipo_mensaje, tipo_envio FROM envio 
			WHERE cuando_enviar <= NOW() 
			AND tipo_mensaje = "SMS"
			AND estado = "pendiente"';//' and id_envio = 1231';

	$stmt = $conexion->prepare($sql);
	$stmt->execute();

	$trabajos_programados = null;	

	foreach($stmt->fetchAll() as $row ) {            	
		$envios = array('datos' => $row['datos_envio_programado'],
						'id_envio' => $row['id_envio'],
						'tipo_mensaje' => $row['tipo_mensaje'],
						'tipo_envio' => $row['tipo_envio']);
        
        $trabajos_programados[] = $envios;

    }         

/*
foreach($trabajos_programados as $job) {
	echo "id_envio:". $job['id_envio'] . " - tipo_mensaje:".$job['tipo_mensaje']." - datos:".$job['datos']."<br>";
}
*/
	

	
	if($trabajos_programados != null){
		foreach($trabajos_programados as $job) {			

			if ($job['tipo_envio'] == 'unico') {
				
				
				$json = trim(preg_replace('/\s+/', ' ', $job['datos']));
								
				$array = json_decode($json, true);


		        # llamamos al controlador que nos devolvera la api/driver correspondiente
		        $sms = new SMSController($array['id_empresa']);

		        if(!is_null($sms->error)){
		            echo "Error: ".$sms->error;
		            return false;
		        }

		        $api = $sms->getAPI();

				
                $api->sendSms($array['destinatario'], $array['mensaje_a_enviar'], $array['remitente'], '', 'programado', $array['id_empresa'], '', 0,'',$job['id_envio']);


			}elseif ($job['tipo_envio'] == 'masivo') {
				
				
				$json = trim(preg_replace('/\s+/', ' ', $job['datos']));
								
				$array2 = json_decode($json, true);

				# llamamos al controlador que nos devolvera la api/driver correspondiente
		        $sms = new SMSController($array2['id_empresa']);

		        if(!is_null($sms->error)){
		            echo "Error: ".$sms->error;
		            return false;
		        }

		        $api = $sms->getAPI();
        		
				$api->sendMassSms($array2['mensaje_a_enviar'], '', $array2['id_empresa'], 'programado', 0, $array2['destinatarios'], '', $array2['id_lista'] , '' , $array2['remitente'], $job['id_envio']);
			}

				
			
		}	
	}



?>