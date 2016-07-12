<?php
	
	require __DIR__."/SMS/SMSController.php";

	try {
		
		$conDB = null;
		
		define('DB_DRIVER_CONNECTUS', 'mpdo');
		define('DB_HOSTNAME_CONNECTUS', 'localhost');
		define('DB_USERNAME_CONNECTUS', 'connectu_connect');
		define('DB_PASSWORD_CONNECTUS', 'cOnNectUs_05041977_.#');
		define('DB_DATABASE_CONNECTUS', 'connectu_adm_connectus');
		define('DB_PORT_CONNECTUS', '3306');
		define('DB_PREFIX_CONNECTUS', '');
		
		$string_connection = "mysql:host=".DB_HOSTNAME_CONNECTUS.";dbname=".DB_DATABASE_CONNECTUS. ";charset=utf8";
		$conexion = new PDO($string_connection,DB_USERNAME_CONNECTUS,DB_PASSWORD_CONNECTUS);

		$sql = "SELECT D.* , E.id_empresa as empresa
				FROM detalle_envio  AS D
				INNER JOIN envio AS E
				ON D.id_envio = E.id_envio
				WHERE D.estado not in ('CONFIRMED DELIVERY','UNDELIVERED', 'INVALID_DNS') 
				AND E.tipo_mensaje = 'SMS' and D.id_respuesta_servidor NOT IN ('-1','0','')
				AND D.intentos <= 1500
				order by D.id_detalle_envio asc ";

		$stmt = $conexion->prepare($sql);
		$stmt->execute();	
		$enviosSms = null;	



		
		foreach($stmt->fetchAll() as $row ) {    

			$envios = array('id_envio' => $row['id_envio'],
							'id_detalle_envio' => $row['id_detalle_envio'],
							'id_respuesta_servidor' => $row['id_respuesta_servidor'],
							'num_channel' => $row['num_channel'],
							'estado' => $row['estado'],
							'intentos' => $row['intentos'],
							'empresa' => $row['empresa']
						);
	        
	        $enviosSms[] = $envios;

	    }                


	
		if($enviosSms != null){
			foreach($enviosSms as $key) {

				# llamamos al controlador que nos devolvera la api/driver correspondiente
		        $sms = new SMSController($key['empresa']);
		        $api = $sms->getAPI();		        

		        $status = $api->get_status_from_server($key['id_detalle_envio']);

				$intentos = ($key['intentos'] + 1);

				$sql =  "UPDATE detalle_envio SET estado = '" . $status . "' , intentos = " . $intentos . " WHERE id_detalle_envio = ". $key['id_detalle_envio'].";";						  		
				
				$stmt = $conexion->prepare($sql);
				$stmt->execute();

			}	
		}

		
		$sql = "UPDATE 	envio AS env  
				SET 	estado = 'terminado'
				WHERE 	estado != 'terminado'
						AND env.tipo_mensaje = 'SMS'
						AND (	SELECT 	COUNT(*) 
								FROM 	detalle_envio AS det
								WHERE 	det.estado IN ('en proceso')
										AND det.id_envio = env.id_envio ) = 0
						AND (	SELECT 	COUNT(*) 
								FROM 	detalle_envio AS det
								WHERE 	det.estado NOT IN ('en proceso')
										AND det.id_envio = env.id_envio ) > 0";

		$stmt = $conexion->prepare($sql);
		$stmt->execute();

		//echo "Hola 9";

		

	} catch (Exception $e) {
		echo 'ERROR:'.$e->getMessage().' - '.$e->getFile().' - line: '.$e->getLine().' - Trace:'.$e->getTraceAsString(); 
	}
	
	
?>