<?php	
require dirname(__FILE__) . '/SmsController.php';
	$conDB = null;
	

	define('DB_DRIVER_CONNECTUS', 'mpdo');
	define('DB_HOSTNAME_CONNECTUS', 'localhost');
	define('DB_USERNAME_CONNECTUS', 'connectu_test');
	define('DB_PASSWORD_CONNECTUS', 'Connectus.2016;');
	define('DB_DATABASE_CONNECTUS', 'connectu_adm_test');
	define('DB_PORT_CONNECTUS', '3306');
	define('DB_PREFIX_CONNECTUS', '');
	
	$string_connection = "mysql:host=".DB_HOSTNAME_CONNECTUS.";dbname=".DB_DATABASE_CONNECTUS. ";charset=utf8";
	$conexion = new PDO($string_connection,DB_USERNAME_CONNECTUS,DB_PASSWORD_CONNECTUS);


	$api = new SmsController();
	$response = $api->getMensajesRecibidosById();
	$error_log = '/home/connectus/public_html/connectus/Logs/recibidos.txt';

	$arrayName = json_decode(json_encode($response), true);  
	
	$result['status'] = $arrayName['getReceivedMessagesResult']['messages'];

	for ($i=0; $i < sizeof($arrayName['getReceivedMessagesResult']['messages']['smsMessage']) ; $i++) { 

		$sqlSelect = "SELECT * FROM sms_recibido where id_respuesta_servidor = ". $arrayName['getReceivedMessagesResult']['messages']['smsMessage'][$i]['id'] ;
		$stmt = $conexion->prepare($sqlSelect);
		$stmt->execute();
		$result = $stmt->fetch();		

		//$arch = fopen($error_log, 'a');
		//fwrite($arch, "Inicio   " . date('Y-m-d G:i:s') . '-->' ."\n" .json_encode($result)."\n");
		//fclose($arch);

		if ($result == null) {

			//$arch = fopen($error_log, 'a');
			//fwrite($arch, "\nINSERTAR RECIBIDO   " . date('Y-m-d G:i:s') . '-->' ."\n" .json_encode($arrayName['getReceivedMessagesResult']['messages']['smsMessage'][$i])."\n");
			//fclose($arch);

			$sql = "INSERT INTO sms_recibido SET mensaje = '".$arrayName['getReceivedMessagesResult']['messages']['smsMessage'][$i]['message'] ."'
				 ,fecha = '".$arrayName['getReceivedMessagesResult']['messages']['smsMessage'][$i]['date']."'
				 , id_respuesta_servidor = '".$arrayName['getReceivedMessagesResult']['messages']['smsMessage'][$i]['id']."'
				 , destinatario = '".$arrayName['getReceivedMessagesResult']['messages']['smsMessage'][$i]['dnis']."'
				 , remitente = '".$arrayName['getReceivedMessagesResult']['messages']['smsMessage'][$i]['ani']."'";

			$stmt = $conexion->prepare($sql);
			$stmt->execute();
		}		
	}

	$conexion = null;
	$trabajos_programados = null;	
			
?>