<?php	

	require dirname(__FILE__) . '/SmsController.php';
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

	$sql = "SELECT D.* FROM detalle_envio  AS D
			INNER JOIN envio AS E
			ON D.id_envio = E.id_envio
			WHERE D.estado not in ('CONFIRMED DELIVERY','UNDELIVERED', 'INVALID_DNS') 
			AND E.tipo_mensaje = 'SMS' and D.id_respuesta_servidor NOT IN ('-1','0','')
			AND D.intentos <= 1500
			order by D.id_detalle_envio desc ";

	$stmt = $conexion->prepare($sql);
	$stmt->execute();	
	$enviosSms = null;	

	foreach($stmt->fetchAll() as $row ) {            	
		$envios = array('id_envio' => $row['id_envio'],
						'id_respuesta_servidor' => $row['id_respuesta_servidor'],
						'estado' => $row['estado'],
						'intentos' => $row['intentos']);
        
        $enviosSms[] = $envios;

    }                

	
	if($enviosSms != null){
		foreach($enviosSms as $key) {						

			$api = new SmsController();

			$response = $api->getMsgStatusById($key['id_respuesta_servidor']); 

            $arrayName = json_decode(json_encode($response), true);                          

			$intentos = ($key['intentos'] + 1);


			$sql =  "UPDATE detalle_envio SET estado = '" . $arrayName['enquireMsgStatusResult']['status'] . "' , intentos = " . $intentos . " WHERE id_respuesta_servidor = '". $key['id_respuesta_servidor'] ."' ";						  		

			$stmt = $conexion->prepare($sql);
			$stmt->execute();

			/*
			$sql =  "UPDATE envio SET estado = 'terminado' WHERE id_envio = '". $key['id_envio']."' and estado != 'terminado'";   
			
			$stmt = $conexion->prepare($sql);
			$stmt->execute();            
			*/
			
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


		
?>