<?php	

	define('DB_DRIVER_CONNECTUS', 'mpdo');
	define('DB_HOSTNAME_CONNECTUS', 'localhost');
	define('DB_USERNAME_CONNECTUS', 'connectu_connect');
	define('DB_PASSWORD_CONNECTUS', 'cOnNectUs_05041977_.#');
	define('DB_DATABASE_CONNECTUS', 'connectu_adm_connectus');
	define('DB_PORT_CONNECTUS', '3306');
	define('DB_PREFIX_CONNECTUS', '');
	
	$string_connection = "mysql:host=".DB_HOSTNAME_CONNECTUS.";dbname=".DB_DATABASE_CONNECTUS. ";charset=utf8";
	$conexion = new PDO($string_connection,DB_USERNAME_CONNECTUS,DB_PASSWORD_CONNECTUS);
 
	$sql = "UPDATE 	envio AS env  
			SET 	estado = 'terminado'
			WHERE 	estado != 'terminado'
					AND env.tipo_mensaje = 'MAIL'
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