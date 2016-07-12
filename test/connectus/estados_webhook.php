<?php
define('DB_DRIVER_CONNECTUS', 'mpdo');
define('DB_HOSTNAME_CONNECTUS', 'localhost');
define('DB_USERNAME_CONNECTUS', 'connectu_test');
define('DB_PASSWORD_CONNECTUS', 'Connectus.2016;');
define('DB_DATABASE_CONNECTUS', 'connectu_adm_test');
define('DB_PORT_CONNECTUS', '3306');
define('DB_PREFIX_CONNECTUS', '');



	$string_connection = "mysql:host=".DB_HOSTNAME_CONNECTUS.";dbname=".DB_DATABASE_CONNECTUS;
	$conexion = new PDO($string_connection,DB_USERNAME_CONNECTUS,DB_PASSWORD_CONNECTUS);
	#$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$error_log = '/home/connectus/public_html/test/connectus/Logs/estados_log.txt';

	$handle = fopen('php://input','r');
	$jsonInput = fgets($handle);

	$sss = str_ireplace('mandrill_events=', '', rawurldecode(urldecode($jsonInput)));
	$decoded = json_decode($sss,true);

	$arch = fopen($error_log, 'a');
	fwrite($arch, "Inicio   " . date('Y-m-d G:i:s') . '-->' ."\n" .json_encode($decoded)."\n");
	fclose($arch);

	foreach ($decoded as $key => $respuesta) {


		switch ($respuesta['event']) {

			case 'send':
			case 'soft_bounce':
			case 'deferral':
			case 'reject':
			case 'rejected':
			case 'hard_bounce':
			case 'unsub':

				# Guardamos el estado del mail
					$sql = "UPDATE detalle_envio 
							SET estado = '". $respuesta['event'] ."' 
							WHERE id_respuesta_servidor = '". $respuesta['_id']."' ";
				break;

			case 'open':
			case 'click':
			case 'spam':
					# hacemos un sumatoria

					$sql = "SELECT estado_".$respuesta['event']." AS suma
							FROM detalle_envio WHERE id_respuesta_servidor = '". $respuesta['_id']."' ";

					$sth = $conexion->prepare($sql);
					$sth->execute();
					$result = $sth->fetch();

					$total = $result['suma'] + 1;

					$sql = "UPDATE detalle_envio 
							SET estado_".$respuesta['event']." = ".$total." 
							WHERE id_respuesta_servidor = '". $respuesta['_id']."' ";

				break;

			default:
				# Guardamos el estado del mail
					$sql = "UPDATE detalle_envio 
							SET estado = '". $respuesta['event'] ."' 
							WHERE id_respuesta_servidor = '". $respuesta['_id']."' ";
			break;

		}


		//$sql = "UPDATE detalle_envio set estado = '". $respuesta['event'] ."' WHERE id_respuesta_servidor = '". $respuesta['_id']."' ";
		
		$stmt = $conexion->prepare($sql);
		$stmt->execute(); 

		$sql = "\n" . $sql . "\n";

		$arch = fopen($error_log, 'a');
		fWrite($arch,$sql);
		fclose($arch);
	}



echo "Error_log: " . filesize($error_log) . " bytes"; 

$arch = fopen($error_log, 'a');
fwrite($arch,"\nEjecucion terminada\n");
fclose($arch);

?>