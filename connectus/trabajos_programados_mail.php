<?php	

	require_once __DIR__.'/MAIL/Models/Pendientes.php';

	require_once __DIR__.'/MAIL/MAILController.php';
	
	# llamamos al controlador que nos devolvera la api/driver correspondiente
    $pendientes = new Pendientes();


    $result = $pendientes->get();

    $trabajos_programados = null;	

	foreach($result as $row ) {            	
		$envios = array('id_empresa' => $row['id_empresa'],
						'datos' => $row['datos_envio_programado'],
						'id_envio' => $row['id_envio'],
						'tipo_envio' => $row['tipo_envio']);
        
        $trabajos_programados[] = $envios;

    }   

	
	if($trabajos_programados != null){
		foreach($trabajos_programados as $job) {			
				

			if ($job['tipo_envio'] == 'unico') {

				$json = trim(preg_replace('/\s+/', ' ', $job['datos']));
				$array = json_decode($json, true);	

				
			
				# llamamos al controlador que nos devolvera la api/driver correspondiente
		        $mail = new MAILController($job['id_empresa']);

		        if(!is_null($mail->error)){
		            echo "Error: ".$mail->error;
		            return false;
		        }

		        $api = $mail->getAPI();													

				$message  = "<html dir='ltr' lang='es'>" . "\n";
				$message .= " <head>" . "\n";				
				$message .= "    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>" . "\n";
				$message .= "    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' type='text/css'>" . "\n";
				$message .= " </head>" . "\n";
				$message .= "  <body> <br>
							<div align='center'>
								<table border='0' cellpadding='0' cellspacing='0' height='39' id='table26' width='600'>
									<tbody>
										<tr>
											<td valign='top'>

							<div style='font-family:Verdana, Arial, Helvetica, sans-serif; text-align:center; font-size:10px; color:#999999;' valign='top'>
							Para asegurar la entrega de nuestros e-mail en su correo, por favor agregue <span style='color:#0073ae'>".$array['email_remitente']."</span> a su libreta de direcciones<br>
							Si usted no visualiza bien este mail, haga <a href='". HTTPS_CATALOG ."viewmail/index2.php?id_contacto=%recipient.id_contacto%&id_envio=@id_envio' target='_blank'>click aqu&iacute;</a></div>"  . "\n";
				$message .= 	 html_entity_decode(($array['message']), ENT_QUOTES, 'UTF-8')  . "\n";			
				$message .= " 				</td>
										</tr>
									</tbody>
								</table>
							</div>

							" . "\n"; 
				$message .= " </body>" . "\n"; 
				$message .= "</html>" . "\n";
											
				$response = $api->sendMail($array['nombre_remitente'], $array['email_remitente'], $array['destinatario'], $array['asunto'], $message,
				 'connectusKey',$array['id_empresa'], 'programado',0,$job['id_envio']);
						
			}elseif ($job['tipo_envio'] == 'masivo') {

				
				$json = trim(preg_replace('/\s+/', ' ', $job['datos']));
				$array = json_decode($json, true);

				

				# llamamos al controlador que nos devolvera la api/driver correspondiente
		        $mail = new MAILController($job['id_empresa']);

		        if(!is_null($mail->error)){
		            echo "Error: ".$mail->error;
		            return false;
		        }

		        $api = $mail->getAPI();			
				
				$message  = "<html dir='ltr' lang='es'>" . "\n";
				$message .= " <head>" . "\n";				
				$message .= "    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>" . "\n";
				$message .= "    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' type='text/css'>" . "\n";
				$message .= " </head>" . "\n";
				$message .= "  <body> <br>
							<div align='center'>
								<table border='0' cellpadding='0' cellspacing='0' height='39' id='table26' width='600'>
									<tbody>
										<tr>
											<td valign='top'>

							<div style='font-family:Verdana, Arial, Helvetica, sans-serif; text-align:center; font-size:10px; color:#999999;' valign='top'>
							Para asegurar la entrega de nuestros e-mail en su correo, por favor agregue <span style='color:#0073ae'>".$array['email_remitente']."</span> a su libreta de direcciones<br>
							Si usted no visualiza bien este mail, haga <a href='". HTTPS_CATALOG ."viewmail/index2.php?id_contacto=%recipient.id_contacto%&id_envio=@id_envio' target='_blank'>click aqu&iacute;</a></div>"  . "\n";
				$message .= 	 html_entity_decode(($array['message']), ENT_QUOTES, 'UTF-8')  . "\n";			
				$message .= " <br> <div style='font-family:Verdana, Arial, Helvetica, sans-serif; text-align:center; font-size:10px; color:#999999;' valign='top'>Este correo electrónico fue enviado a %recipient.email% <br>Para anular tu suscripción haz clic <a href='" . HTTPS_CATALOG . "admin/index.php?route=common/login&id_lista=@id_lista&id_envio=@id_envio&id_contacto=%recipient.id_contacto%'>aquí</a></div>

											</td>
										</tr>
									</tbody>
								</table>
							</div>

							" . "\n"; 
				$message .= " </body>" . "\n"; 
				$message .= "</html>" . "\n";
				

					
				$api->sendMassMail($array['nombre_remitente'], $array['email_remitente'], $array['destinatarios'],  
											   $array['asunto'], $message, $array['connectusKey'], $array['id_lista'], 0, '', $array['id_empresa'],'programado',$job['id_envio']);
								
			}	
			
		}	
	}



?>