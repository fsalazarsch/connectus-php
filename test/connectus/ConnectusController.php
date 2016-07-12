<?php
use Mailgun\Mailgun; 

class ConnectusController{

	private $mailgun;	
	private $domain;
	private $conDB;
	private $clientServerSms;	 
	private $clientIdSms;	
	private $clientPassSms;
	private $clientAni;
    private $largo_max_mensaje;

	function __construct() {
		require_once dirname(__FILE__) . '/libs/mailgun/vendor/autoload.php';
		include_once dirname(__FILE__) . '/libs/Config.php';

		//Datos del servidor mailgun
		//datos antiguos de prueba 
		$this->mailgun = new Mailgun("key-d3251f3ed6df763b9c8c3cba12794fe4");	
		//$this->mailgun = new Mailgun("key-b65c35f0951103cac7e47c481fc87ee5");



		//$this->domain = "connectus.assertsoft.cl";	
		// dominio antiguo de prueba 
		$this->domain = "sandbox016db1bbc60245e597370304333a73d8.mailgun.org";	
		//DB ASSERTSO_ADM_CONNECTUS

		$string_connection = "mysql:host=localhost;dbname=connectu_adm_test; charset=utf8";
		$this->conDB = new PDO($string_connection,'connectu_test','Connectus.2016;');				        

	}	

	/*Metodos de Mailgun*/	

	/*
	* Enviar nuevo Mail
	*
	* @return retorna response de mailgun con parametros -> id y message
	*/
	public function sendMail($nombre_remitente, $email_remitente, $destinatario, $asunto, $mensaje, $connectusKey, $id_empresa, $momento_envio, $is_predefinido,$id_envio_programado = '', $nombre_predefinido = null ){				   
		$response = array();
		//if($this->checkConnectusKey($connectusKey)){	
		$equivalentes = $this->getEquivalencia('equivalencia_mail');

		$valor1 = $equivalentes['valor1'];
		$valor2 = $equivalentes['valor2'];
		$consumo = 1 / $valor2;


		// nos aseguramos de que $nombre_predefinido si tenga un valor
		if(empty($nombre_predefinido)){
	        $nombre_predefinido = $asunto;
	    }


		//if ($this->checkCreditosDisponibles($id_empresa,$consumo)) {
			$id_envio = 0;
			if ($momento_envio == 'ahora') {

				$id_mensaje = $this->addMail($asunto, htmlspecialchars($mensaje, ENT_QUOTES) , $nombre_remitente, $email_remitente, $is_predefinido, $id_empresa, $nombre_predefinido);
				$id_envio = $this->addEnvio('en proceso' ,$nombre_remitente,$email_remitente,'' ,$id_mensaje,'unico','MAIL',$asunto, $is_predefinido, $id_empresa);			

			}elseif ($momento_envio == 'programado') {

				$id_envio = $this->updateEnvio('en proceso',$id_envio_programado);
			}

			if ($momento_envio == 'reenviar') {

				$mensaje1 = html_entity_decode($mensaje, ENT_QUOTES, 'UTF-8');	
				
				$mensaje = $mensaje1;		

				$id_envio_mailgun = md5($id_envio_programado);

				$this->updateDetalleEnvio($id_envio_programado, $id_envio_mailgun);
				$this->updateFechaEnvio($id_envio_programado);

				$id_envio = $id_envio_programado;


			}else{

				$id_envio_mailgun = md5($id_envio);

				$this->addDetalleEnvio($id_envio_mailgun, $id_envio, $destinatario);			
			}
			
			//Se deja directo en envío
		 	//$this->insertConsumo($id_empresa , $consumo, 0 , $consumo);


			$remitente = $nombre_remitente.' <'.$email_remitente.'>';
				$responseMail = $this->mailgun->sendMessage($this->domain, array(
				'from'    => $remitente,
				'to'      => $destinatario,
				'subject' => $asunto,
				'html'    => $mensaje,
				'o:tracking' => true,
				'o:tag'   => array($id_envio_mailgun)
				));		

			$this->write_log('Correo unico enviado exitosamente con los siguientes datos: destinatario-> '.$destinatario.' , remitente-> '. $remitente .' , id_envio-> '.$id_envio . ' , id_envio_mailgun-> '.$id_envio_mailgun);
											  										
			return $responseMail;
		//}else{
		//	return null;
		//}
			/*}else{
				$response["Mensaje"] = "Tu api key no es valida porfavor registate en connectus o contactanos al siguiente correo: info@connectus.com";
				return $response;													
			}*/	
	}


	/*
	* Enviar nuevo Mail
	*
	* @return retorna response de mailgun con parametros -> id y message
	*/
	public function sendMailRest($nombre_remitente, $email_remitente, $destinatario, $asunto, $mensaje, $id_empresa, $nombre_predefinido = null ){				   
		$response = array();
		$equivalentes = $this->getEquivalencia('equivalencia_mail');

		$valor1 = $equivalentes['valor1'];
		$valor2 = $equivalentes['valor2'];
		$consumo = 1 / $valor2;

		// nos aseguramos de que $nombre_predefinido si tenga un valor
		if(empty($nombre_predefinido)){
	        $nombre_predefinido = $asunto;
	    }
		
		if ($this->checkCreditosDisponibles($id_empresa,$consumo)) {

			$id_mensaje = $this->addMail($asunto, htmlspecialchars($mensaje, ENT_QUOTES) , $nombre_remitente, $email_remitente, 0, $id_empresa, $nombre_predefinido);

			$id_envio = $this->addEnvio('en proceso' ,$nombre_remitente,$email_remitente,'' ,$id_mensaje,'API','MAIL',$asunto, 0, $id_empresa);			

			$id_envio_mailgun = md5($id_envio);

			$this->addDetalleEnvio($id_envio_mailgun, $id_envio, $destinatario);			

			//$consumo = $detalles_insertados / $valor2;
			
		 	$this->insertConsumo($id_empresa , $consumo, 0 , $consumo);

			$remitente = $nombre_remitente.' <'.$email_remitente.'>';

			$message  = '<html dir="ltr" lang="en">' . "\n";
			$message .= '  <head>' . "\n";				
			$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
			$message .= '  </head>' . "\n";
			$message .= '  <body>' . html_entity_decode($mensaje, ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
			$message .= '</html>' . "\n";

			$responseMail = $this->mailgun->sendMessage($this->domain, array(
														'from'    => $remitente,
														'to'      => $destinatario,
														'subject' => $asunto,
														'html'    => $message,
														'o:tracking' => true,
														'o:tag'   => array($id_envio_mailgun)
														));			

			$this->write_log('Correo unico(REST) enviado exitosamente con los siguientes datos: destinatario-> '.$destinatario.' , remitente-> '. $remitente .' , id_envio-> '.$id_envio . ' , id_envio_mailgun-> '.$id_envio_mailgun);								  										
			
			return $id_envio_mailgun;
		}else{
			return 'No posees los creditos suficientes para realizar el envio';
		}
	}


	/*
	* Enviar email masivos
	*
	* @return retorna response de mailgun con parametros -> id y message
	*/
	public function sendMassMail($nombre_remitente, $email_remitente, $result = array(), $asunto, $mensaje, $connectusKey, $id_lista, $is_predefinido, $titulo, $id_empresa, $momento_envio, $id_envio_programado = '', $nombre_predefinido = null){

		$contactos = array();

		$emailsArray = array();
		$recipentsArray = array();		
		$remitente = $nombre_remitente.' <'.$email_remitente.'>';		


		// nos aseguramos de que $nombre_predefinido si tenga un valor
		if(empty($nombre_predefinido)){
	        $nombre_predefinido = $asunto;
	    }



		//Armo el arreglo de contactos con sus columnas respectivas 
		$indexMail = 0;
		for ($i=0; $i < sizeof($result['valores']); $i++) {
			for ($j=0; $j < sizeof($result['valores'][$i]); $j++) {
				
				if ($result['campos_de_contacto'][$j] == 'email') {
					$contactos[$i][strtolower($result['campos_de_contacto'][$j])] = $result['valores'][$i][$result['campos_de_contacto'][$j]];
					$indexMail = $j;
				}else{

					if ($j == (sizeof($result['valores'][$i]) -1) ) {
						$contactos[$i][strtolower($result['nombre_columnas'][$indexMail])] = $result['valores'][$i][$result['campos_de_contacto'][$indexMail]];

						$contactos[$i][strtolower($result['nombre_columnas'][$j])] = $result['valores'][$i][$result['campos_de_contacto'][$j]];	

					}else{
						
						$contactos[$i][strtolower($result['nombre_columnas'][$j])] = $result['valores'][$i][$result['campos_de_contacto'][$j]];	
					}
					
				}
			}
		}

		$correos_malos = 0;
		$correos_buenos = 0;

		foreach ($contactos as &$valores) {											

			if(!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $valores['email']  )){
				$correos_malos++;
			}else{
				$correos_buenos++;
			}
	    }

	    $equivalentes = $this->getEquivalencia('equivalencia_mail');
		$valor1 = $equivalentes['valor1'];
		$valor2 = $equivalentes['valor2'];		
		$consumo = $correos_buenos / $valor2;

	    //if ($this->checkCreditosDisponibles($id_empresa,$consumo)) {
	    	if ($momento_envio == 'ahora') {

			$id_mensaje = $this->addMail($titulo,  htmlspecialchars($mensaje, ENT_QUOTES)  ,$nombre_remitente, $email_remitente, $is_predefinido, $id_empresa, $nombre_predefinido);

			$id_envio = $this->addEnvio('en proceso' ,$nombre_remitente,$email_remitente,$id_lista,$id_mensaje,'masivo','MAIL',$asunto, $correos_malos, $id_empresa);
			

			}elseif ($momento_envio == 'programado') {

				$id_envio = $this->updateEnvioMasivo($id_envio_programado,$correos_malos);
			}

			$detalles_insertados = 0;
			
			foreach ($contactos as &$valores) {
				
				if(preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $valores['email'])){

					$id_envio_mailgun = md5($valores['id_contacto']);
					$valores['id_envio'] = $id_envio_mailgun;

					$this->addDetalleEnvio($id_envio_mailgun, $id_envio, $valores['email'],$valores['id_contacto']);
				
					$detalles_insertados++;

					$recipentsArray[$valores['email']] = $valores;

	            	array_push($emailsArray,$valores['email']);
				}else{

					$id_envio_mailgun = md5($valores['id_contacto']);				
					$this->addDetalleEnvio($id_envio_mailgun, $id_envio, $valores['email']);
				}

		    }		

		    //Se deja directo en envío
		 	//$this->insertConsumo($id_empresa , $consumo, 0 , $consumo);	    
		    
		    $countEmails = array_chunk($emailsArray, 998,true); 		    		   
		   
		    $arraySearch = array();
		    $arrayReplace = array();

		    foreach ($result['nombre_columnas'] as $key ) {
		    	array_push($arraySearch, "%".strtolower($key)."%");
		    	array_push($arrayReplace, "%recipient.".strtolower($key)."%");
		    }
		    

		    $mensaje_formateado = str_ireplace($arraySearch,$arrayReplace,$mensaje);

		    $mensaje_formateado = str_ireplace("@id_lista", $id_lista, $mensaje_formateado);
		    $mensaje_formateado = str_ireplace("@id_envio", $id_envio, $mensaje_formateado);
		    //colocar aca el id usuario para saber quien envio

		    $responseMail = '';
		    for ($i=0; $i < sizeof($countEmails) ; $i++) { 
		    	$emails = implode(',', $countEmails[$i]);

		    	$responseMail = $this->mailgun->sendMessage($this->domain, array(
				'from'    => $remitente,
				'to'      => $emails,
				'subject' => $asunto,
				'html'    => $mensaje_formateado,
				'o:tracking' => true,
				'o:tag'   => array('%recipient.id_envio%'),
	            'recipient-variables' => json_encode($recipentsArray)));
		    }	    
			
			$this->write_log('Mensaje masivo enviado exitosamente con los siguientes datos: remitente-> '.$remitente.' , id_envio-> '.$id_envio . ' , id_empresa-> '.$id_empresa . ' , Contactos-> '.$emailsArray);
			return $responseMail;
	    //}else{
	    //	return null;
	    //}	
	}

	public function getEquivalencia($id_parametro){
		$parametro = "SELECT * FROM parametro WHERE id_parametro = '".$id_parametro."'";
		$stmt = $this->conDB->prepare($parametro);
		$stmt->execute();
		$result = $stmt->fetch();

		return $result;
	}

	public function addMail($titulo, $cuerpo, $remitente, $correo_remitente, $guardar, $usuario = '', $archivo = '', $nombre_predefinido = null) {		
		$sql  = "INSERT INTO mensaje SET tipo = 'MAIL'";
		$sql .= ", titulo = '" . $titulo . "'";
		$sql .= ", cuerpo = '" . $cuerpo . "'";
		$sql .= ", is_predefinido = " . $guardar;
		$sql .= ", fecha_creacion = NOW()";
		$sql .= ", id_empresa = '" . $usuario . "'";
		$sql .= ", remitente = '" . $remitente . "'";
		$sql .= ", correo_remitente = '" . $correo_remitente . "'";		
		$sql .= ", nombre_predefinido = '" . $nombre_predefinido . "'"; 

		if(isset($archivo)){
			if($archivo != ''){
				$sql .= ", archivo = '" . $archivo . "'";
			}
		}    	

		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();		
		
		$id_mail = $this->conDB->lastInsertId();

		return $id_mail;
	}

	public function addEnvio($estado ,$remitente,$correo_remitente,$id_lista, $id_mensaje,$tipo_envio,$tipo_mensaje,$nombre_envio, $correos_malos,$id_empresa){			
    	
    	$sql =  "INSERT INTO envio SET";
    	$sql .= " estado = '". $estado ."'";
    	$sql .= ",remitente = '". $remitente ."'";
		$sql .= ",correo_remitente = '" . $correo_remitente ."'";
		if ($id_lista != '') {
			$sql .= ",id_lista = " . $id_lista;
		}		
		$sql .=',id_mensaje =' . $id_mensaje;		
		$sql .=",tipo_envio = '" . $tipo_envio . "'";
		$sql .=",tipo_mensaje = '" . $tipo_mensaje . "'";
		$sql .=",cuando_enviar = NOW()";
		$sql .= ", id_empresa = '" . $id_empresa . "'";
		$sql .=",nombre_envio = '" . $nombre_envio . "'";
		$sql .=",correos_malos = '" . $correos_malos . "'";				


		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();
		$id_envio = $this->conDB->lastInsertId();					

		return $id_envio;    	
   	}

   	public function updateEnvio($estado , $id_envio){			
    	
    	$sql =  "UPDATE envio SET estado = '".$estado."' WHERE id_envio = ". $id_envio ;    	

		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();		

		return $id_envio;    	
   	}

   	public function updateDetalleEnvio($id_envio, $id_envio_mailgun){
    	
    	$sql =  "UPDATE detalle_envio SET estado = 'en proceso' , id_respuesta_servidor = '". $id_envio_mailgun ."' WHERE id_envio = ". $id_envio;    	

		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();		

		return $id_envio;    	
   	}

   	public function updateFechaEnvio($id_envio){
    	
    	$sql =  "UPDATE envio SET cuando_enviar = NOW() WHERE id_envio = '". $id_envio ."'";    	

		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();		

		return $id_envio;    	
   	}

   	public function updateEnvioMasivo($id_envio, $correos_malos){			
    	
    	$sql =  "UPDATE envio SET estado = 'en proceso' , correos_malos = ". $correos_malos. " WHERE id_envio = '". $id_envio ."'";    	

		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();		

		return $id_envio;    	
   	}

   	public function addDetalleEnvio($id_respuesta_servidor, $id_envio, $destinatario, $id_contacto = ''){		

		$sql  = "INSERT INTO detalle_envio SET ";
		$sql .=	" id_respuesta_servidor =  '" . $id_respuesta_servidor . "'";
		$sql .= ",estado = 'en proceso'" ;
		$sql .=	",destinatario =  '" . $destinatario . "'";
		$sql .= ",id_envio = " . $id_envio;

		if ($id_contacto != ''){
			$sql .= ",id_contacto = " . $id_contacto;
		}

		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();		
		
		$id_envio = $this->conDB->lastInsertId();

		return $id_envio;
   	}

	/*
	* Metodo para traer mail por id
	*
	* @return  
	*/
	public function getSendMailById($messageid){	
		$response = array();

		$queryString = array(		
		'tags'      => $messageid,			
		);
		
		# Make the call to the client.
		$result = $this->mailgun->get($this->domain."/events", $queryString);

		return $result;			
												
	}

	public function insertConsumo($id_empresa , $consumo_mail, $consumo_sms, $valor) {	

		$cuenta = "SELECT id_cuenta_corriente FROM cuenta_corriente WHERE id_empresa = ".$id_empresa;
		$stmt = $this->conDB->prepare($cuenta);
		$stmt->execute();
		$result = $stmt->fetch();

		$sql  = "INSERT INTO consumo SET ";		
		$sql .= " id_cuenta_corriente = " . $result['id_cuenta_corriente'] ;
		$sql .= ", sms_consumidos = " . $consumo_sms;
		$sql .= ", fecha = NOW()";		
		$sql .= ", mail_consumidos = '" . $consumo_mail . "'";
		$sql .= ", valor = ". $valor;

		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();		
		
		$this->actualizarCuenta($id_empresa,$consumo_mail);

		$id_consumo = $this->conDB->lastInsertId();

		return $id_consumo;
	}

	public function checkCreditosDisponibles($id_empresa, $requeridos){
		$sql = "SELECT id_cuenta_corriente AS cuenta FROM cuenta_corriente WHERE id_empresa = " . $id_empresa;
		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch();
		//variables sql y stmt toman nuevos valores. 
		$sql = "SELECT (saldo_mail - consumidos_mail) AS disponibles FROM cuenta_corriente WHERE id_cuenta_corriente = " . $result['cuenta'];
		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch();

		if ($result['disponibles'] >= $requeridos) {
			return true;
		}else{
			return false;
		}
	}

	public function actualizarCuenta($id_empresa, $consumo){
		$sql = "SELECT saldo_mail AS saldo, consumidos_mail AS usados from cuenta_corriente WHERE id_empresa = " . $id_empresa;

		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch();

		$nuevo_saldo_mail = $result['saldo'] ;
		$consumidos = $result['usados'] + $consumo;

		$sql = "UPDATE cuenta_corriente SET saldo_mail = " . $nuevo_saldo_mail . ", consumidos_mail = " . $consumidos . " WHERE id_empresa = " . $id_empresa;
		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch();

	}

	public function write_log($cadena){

		$arch = fopen(DIR_LINK . 'connectus/Logs/mail_logs.txt', 'a');
		date_default_timezone_set('America/Santiago');
		fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r($cadena, true) . "\n");

		fclose($arch);
	}

   
}
