<?php 
  
define('DB_DRIVER_CONNECTUS', 'mpdo');
define('DB_HOSTNAME_CONNECTUS', 'localhost');
define('DB_USERNAME_CONNECTUS', 'connectu_connect');
define('DB_PASSWORD_CONNECTUS', 'cOnNectUs_05041977_.#');
define('DB_DATABASE_CONNECTUS', 'connectu_adm_connectus');
define('DB_PORT_CONNECTUS', '3306');
define('DB_PREFIX_CONNECTUS', '');

//define('HTTP_CATALOG', 'http://connectus.cl/');
//define('HTTPS_CATALOG', 'http://assertsoft.cl/developer/connectus/platx/');

$string_connection = "mysql:host=".DB_HOSTNAME_CONNECTUS.";dbname=".DB_DATABASE_CONNECTUS;
$conexion = new PDO($string_connection,DB_USERNAME_CONNECTUS,DB_PASSWORD_CONNECTUS);

if(isset($_GET["id_envio"]) )
{
	$data = $_GET["id_envio"];
	$id_contacto = $_GET["id_contacto"];
	$sql = "SELECT datos_envio_programado ,tipo_envio as tipo, nombre_envio, id_mensaje, correo_remitente FROM envio
			WHERE id_envio = ". $data;

	$stmt = $conexion->prepare($sql);
	$stmt->execute();

	$registros = $stmt->fetchAll();
	          	
	$datos_envio = $registros[0]['datos_envio_programado'];
	$nombre = utf8_encode($registros[0]['nombre_envio']);
	$tipo = $registros[0]['tipo'];

	$contactos = array();

	//$json = trim(preg_replace('/\s+/', ' ', $datos_envio));
	$json = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $datos_envio);

	$array_envio = json_decode($json, true);

	$envios = $array_envio['destinatarios'];

	//** TRAE MENSAJE

	$sql2 = "SELECT cuerpo FROM mensaje
			WHERE id_mensaje = ". $registros[0]['id_mensaje'];

	$cuerpo_email = html_entity_decode($conexion->query($sql2)->fetchColumn(), ENT_QUOTES, 'UTF-8');

	//** FIN TRAE MENSAJE
	for ($i=0; $i < sizeof($envios['valores']); $i++) {

		if ($envios['valores'][$i]['id_contacto'] == $id_contacto) {
			
			for ($j=0; $j < sizeof($envios['valores'][$i]); $j++) {

				if ($envios['campos_de_contacto'][$j] == 'email') {
					$contactos[strtolower($envios['campos_de_contacto'][$j])] = $envios['valores'][$i][$envios['campos_de_contacto'][$j]];
					$indexMail = $j;
				}else{

					if ($j == (sizeof($envios['valores'][$i]) - 1) ) {
						$contactos[strtolower($envios['nombre_columnas'][$indexMail])] = $envios['valores'][$i][$envios['campos_de_contacto'][$indexMail]];
						$contactos[strtolower($envios['nombre_columnas'][$j])] = $envios['valores'][$i][$envios['campos_de_contacto'][$j]];	

					}else{
						
						$contactos[strtolower($envios['nombre_columnas'][$j])] = $envios['valores'][$i][$envios['campos_de_contacto'][$j]];	
					}
					
				}
			}

		}

	}



 	$arraySearch = array();
    $arrayReplace = array();

    $message  = "<html dir='ltr' lang='es'>" . "\n";
	$message .= " <head>" . "\n";				
	$message .= "    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>" . "\n";
	$message .= "    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' type='text/css'>" . "\n";
	$message .= " <title>".$nombre."</title> </head>" . "\n";
	$message .= "  <body > <br>
				<div align='center'>
				<table border='0' cellpadding='0' cellspacing='0' height='39' id='table26' width='600'>
					<tbody>
						<tr>
							<td valign='top'>
								
				
				<div style='font-family:Verdana, Arial, Helvetica, sans-serif; text-align:center; font-size:10px; color:#999999;' valign='top'>
				Para asegurar la entrega de nuestros e-mail en su correo, por favor agregue <span style='color:#0073ae'>".$registros[0]['correo_remitente']."</span> a su libreta de direcciones<br>
				Si usted no visualiza bien este mail, haga <a href='http://connectus.cl/viewmail/index2.php?id_contacto=%recipient.id_contacto%&id_envio=@id_envio' target='_blank'>click aqu&iacute;</a> </div>"  . "\n";
	///$message .= 	 html_entity_decode(($array_envio['message']), ENT_QUOTES, 'UTF-8')  . "\n";
				$message .= 	 utf8_encode($cuerpo_email) . "\n";

	if ($tipo != 'unico') {
		$message .= " <br> <footer style='font-family:Verdana, Arial, Helvetica, sans-serif; text-align:center; font-size:10px; color:#999999;' valign='top'>Este correo electrónico fue enviado a %recipient.email% <br>Para anular tu suscripción haz click 
		<a href='http://connectus.cl/admin/index.php?route=common/login&id_lista=@id_lista&id_envio=@id_envio&id_contacto=%recipient.id_contacto%'>aquí</a></footer>";
	}	
						 
	$message .= " </td>
				</tr>
			</tbody>
		</table></div>" . "\n";
	$message .= " </body>" . "\n"; 
	$message .= "</html>" . "\n";

	
    foreach ($envios['nombre_columnas'] as $key ) {
    	array_push($arraySearch, "%".strtolower($key)."%");
    	array_push($arrayReplace, $contactos[strtolower($key)]);
    }
    

    $mensaje_formateado = $message;
    
    $mensaje_formateado = str_ireplace($arraySearch,$arrayReplace,$message);

    $mensaje_formateado = str_ireplace("@id_lista", $id_lista, $mensaje_formateado);

	$mensaje_formateado = str_ireplace("%recipient.email%", $contactos['email'], $mensaje_formateado);
	$mensaje_formateado = str_ireplace("%recipient.id_contacto%", $contactos['id_contacto'], $mensaje_formateado);
	$mensaje_formateado = str_ireplace("@id_envio", $data, $mensaje_formateado);
	

    echo $mensaje_formateado;
	
} 




	