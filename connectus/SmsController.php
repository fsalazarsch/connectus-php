<?php

class SmsController{

	private $conDB;	
	private $clientServerSms;	
	private $clientIdSms;	
	private $clientPassSms;
	private $clientAni;
    private $largo_max_mensaje;

	function __construct() {
		
		include_once dirname(__FILE__) . '/async_soap/SoapClient.php';

		ini_set("soap.wsdl_cache", "0");

		$this->clientServerSms = new SoapClient(dirname(__FILE__) .'/server_teleco.xml',array(
		'exceptions'=>true,
		'cache_wsdl'=>WSDL_CACHE_NONE,
		'encoding'=>'utf-8',
		'stream_context' => stream_context_create(array('http' => array('protocol_version' => 1.0) ) )));
					
		$this->clientIdSms = 'telecotesting';
		$this->clientPassSms = 'matias2015';
		$this->clientAni = '56442147839';
        $this->largo_max_mensaje = 160;

        $string_connection = "mysql:host=localhost;dbname=connectu_adm_connectus;charset=utf8";
		$this->conDB = new PDO($string_connection,'connectu_connect','cOnNectUs_05041977_.#');				        		
		//$this->conDB->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		$this->conDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	}	


	/**Metodos de teleco**/

	/*
	* Enviar nuevo Sms
	*
	* @return
	* submitMsgResult: {
	* code: 0
	* message: "Message Queued"
	* id: 135048428
	* }
	*/
	public function sendSms($numero, $mensaje, $remitente, $connectusKey, $momento_envio, $id_empresa = '', $titulo , $is_predefinido,$nombre_mensaje_predefinido ,$id_envio_programado = ''){

		$id_envio = 0;

		try{
			
				if ($momento_envio == 'ahora') {

					$id_mensaje = $this->addSms($mensaje, $remitente, $is_predefinido, $id_empresa, $titulo, $nombre_mensaje_predefinido);

					$id_envio = $this->addEnvio('en proceso' ,$remitente, $id_mensaje, 'unico','SMS', $titulo ,$id_empresa);			

				}elseif ($momento_envio == 'programado'){

					$id_envio = $this->updateEnvio($id_envio_programado);

				}		

				//Se envían mensajes y guardan en tabla detalle_envío
	        	$numeroMensajesEnviados = $this->sendSMStoTeleco($numero, $mensaje, $id_envio);

	        	if ($numeroMensajesEnviados > 1) {
	        		return 'Se han enviado '.$numeroMensajesEnviados.' mensajes exitosamente';
	        	} else if ($numeroMensajesEnviados == 1) {
	        		return 'El mensaje fue enviado exitosamente';
	        	} else {
	        		return 'Problemas con el servidor. Inténtelo más tarde.';
	        	}
	    	
	    } catch(Exception $e){
	    	$this->write_log('ERROR Envío SMS Único - datos: Numero-> '.$numero .' , id_envio->'.$id_envio.'-'.$id_envio_programado.' , mensajeISO-> '
	    		.$mensaje .' , remitente-> '.$remitente .' , momento_envio-> '.$momento_envio .' , id_empresa-> '
	    		.$id_empresa .' , titulo-> '.$titulo .', Error:'.$e->getMessage().' - '.$e->getFile().' - line: '.$e->getLine()
	    		.' - Trace:'.$e->getTraceAsString());

	    	if ($id_envio != 0) {
	    		$this->updateEnvioEstado($id_envio, 'Error');
	    	} else if ($id_envio_programado != 0){
	    		$this->updateEnvioEstado($id_envio_programado, 'Error');
	    	} 

			return 'Problemas con el servidor. Inténtelo más tarde.';
		}
		
	}

	public function sendSmsRest($numero, $mensaje, $id_empresa){	
		
		$id_envio = 0;

		try {

			$numero = $this->checkAndParseCellphoneNumber($numero);

			$equivalentes = $this->getEquivalencia('equivalencia_sms');

			$valor1 = $equivalentes['valor1'];
			$valor2 = $equivalentes['valor2'];

			$consumo = 1 / $valor2;

			if ($this->checkCreditosDisponibles($id_empresa,$consumo)) {
				$id_mensaje = $this->addSms($mensaje, '', 0, $id_empresa, 'API');	

				$id_envio = $this->addEnvio('en proceso' , '', $id_mensaje, 'API','SMS', 'Enviado desde API' ,$id_empresa);			
				
				$numeroMensajesEnviados = $this->sendSMStoTeleco($numero, $mensaje, $id_envio,'si');

			 	$this->insertConsumo($id_empresa , 0, $consumo, $consumo);

			 	//$this->write_log('Mensaje REST ENVIADO: Numero-> '.$numero.' , id_envio-> '.$id_envio . ' , id_teleco-> '.$numeroMensajesEnviados[0]);

			 	if (count($numeroMensajesEnviados) == 1) {
			 		return $numeroMensajesEnviados[0];
			 	} else if (count($numeroMensajesEnviados) > 1) {
			 		return $numeroMensajesEnviados;
			 	} else {
			 		return '004';
			 	}
			 	

		        return ($numeroMensajesEnviados[0]);
			}else{
				return '044';
			}

		} catch(Exception $e){
	    	$this->write_log('ERROR Envío SMS REST - datos: Numero-> '.$numero .' , id_envio->'.$id_envio.'-'.$id_envio_programado.' , mensaje-> '
	    		.$mensaje .' , id_empresa-> '
	    		.$id_empresa .', Error:'.$e->getMessage().' - '.$e->getFile().' - line: '.$e->getLine()
	    		.' - Trace:'.$e->getTraceAsString());

	    	if ($id_envio != 0) {
	    		$this->updateEnvioEstado($id_envio, 'Error');
	    	} else if ($id_envio_programado != 0){
	    		$this->updateEnvioEstado($id_envio_programado, 'Error');
	    	} 

			return '004';

		}
		
		
	}

	
	public function sendMassSms($mensaje, $connectusKey, $id_empresa, $momento_envio, $is_predefinido, $result = array(), $titulo = '', $id_lista, $nombre_mensaje_predefinido, $remitente,  $id_envio_programado){
			//$api->sendMassSms($array2['mensaje_a_enviar'], '', $array2['id_empresa'], 'programado', 0, $array2['destinatarios'], '', $array2['id_lista'] , '' , $array2['remitente'], $job['id_envio']);
		
		$id_envio = 0;
		set_time_limit(20000); //para que se pueda ejecutar por un buen tiempo, hasta 5 H app

		try {
			
			if ($momento_envio == 'ahora') {

				$id_mensaje = $this->addSms($mensaje, $remitente, $is_predefinido, $id_empresa, $titulo,$nombre_mensaje_predefinido);										

				$id_envio = $this->addEnvio('en proceso' ,$remitente, $id_mensaje, 'masivo','SMS', $titulo, $id_empresa, $id_lista);

			}elseif ($momento_envio == 'programado') {

				$id_envio = $this->updateEnvio($id_envio_programado);

			}

			
			$arraySearch = array();
			for ($i=0; $i < sizeof($result['nombre_columnas']) - 1; $i++) { 
				array_push($arraySearch, "%".$result['nombre_columnas'][$i]."%");                    
			}   

			$contactos = array();
			for ($i=0; $i < sizeof($result['valores']); $i++) {                                    
				$mensaje_formateado = $mensaje;
				for ($j=0; $j < sizeof($result['valores'][$i]); $j++) {	                    

					//$contactos[$i][strtolower($result['nombre_columnas'][$j])] = $result['valores'][$i][$result['campos_de_contacto'][$j]];
					$contactos[$i][strtolower($result['campos_de_contacto'][$j])] = $result['valores'][$i][$result['campos_de_contacto'][$j]];

					if ( $j < sizeof($arraySearch)) {
						$mensaje_formateado = str_ireplace($arraySearch[$j], $result['valores'][$i][$result['campos_de_contacto'][$j]] ,$mensaje_formateado);
					}                        
				}            


				$contactos[$i]['mensaje'] = $mensaje_formateado;

			}
			
			
			  
			
			////FORMA NO ASYNC ANTERIOR
			foreach ($contactos as $contacto) { 
				//Se envían mensajes y guardan en tabla detalle_envío
				$this->sendSMStoTeleco($contacto['celular'], $contacto['mensaje'], $id_envio);
				
			}
			
			/*
			//##############################################################
			//##############################################################
			//################# NUEVO ENVÍO ASYNC #################
			//##############################################################
			//##############################################################
			//##############################################################

			
			$client = new SoapClientAsync(dirname(__FILE__) .'/server_teleco.xml',array(
									'exceptions'=>true,
									'cache_wsdl'=>WSDL_CACHE_NONE,
									'encoding'=>'utf-8',
									'stream_context' => stream_context_create(array('http' => array('protocol_version' => 1.0) ) )));
			

			// $options = array(
			//     'connection_timeout' => 40,
			//     'trace'              => true
			// );								

			// $client = new SoapClientAsync(dirname(__FILE__) .'/server_teleco.xml',$options);	

			//$client::$debug = true;
			$client::$async = true;

			$requestIds = array();
			$requestNumbers = array();

			foreach ($contactos as $contacto) { 
				//Se envían mensajes y guardan en tabla detalle_envío
				//$this->sendSMStoTeleco($contacto['celular'], $contacto['mensaje'], $id_envio);

				//Seteo
				$number = $contacto['celular'];
				$message = $contacto['mensaje'];

				//Se setea formato
				$number = $this->checkAndParseCellphoneNumber($number);

				//Se limpia y sacan acentos al mensaje
				$message = $this->messageClean($message); 

				//Revisa compañía en tabla de portabilidad
				$compania = $this->check_compania($number);

				$unsplitMessage = wordwrap($message,$this->largo_max_mensaje,'|',true);
				$splitMessage = explode('|',$unsplitMessage);			

				foreach($splitMessage as &$readyMessage){

					$params = array(
						"clientid" => $this->clientIdSms,
						"clientpassword" => $this->clientPassSms,
						"ani" =>  $this->clientAni,
						"dnis" => $number,
						"message" => utf8_encode($readyMessage)
						);

					//DEBUG
					//$this->fakeSendCron($params["message"]);

					if ($compania != 'SIN EMPRESA') {

						//Manda porque está dentro de la tabla de portabilidad

						//$result = $this->clientServerSms->__soapCall('submitMsg' ,array($params));
						$requestIds[] = $client->submitMsg($params);
						$requestNumbers[end($requestIds)] = array($number, $compania);

						

						

					} else {
						//No envía porque no está en la tabla de portabilidad

						//DEBUG
						//$this->addDetalleEnvio('-1', $id_envio, $telefono); 
						$this->addDetalleEnvio('0', $id_envio, $number, $compania);
					}

				}


				
			}

			print_r($requestIds);
			//$responses = $client->run($requestIds);
			$responses = $client->run();

			foreach ($responses as $id => $response) {
			    
			    if ($response instanceof SoapFault) {
			       
			        print 'SoapFault: ' . $response->faultcode . ' - ' . $response->getMessage() . "\n";
			    } else {
			       

			        $arrayName = json_decode(json_encode($response), true); 

			        $mensaje = ' ### Number:'.$requestNumbers[$id][0]."\n";  
			        $mensaje .= ' ID_teleco : ' . $arrayName['submitMsgResult']['id'] . ' - CODE : '. $arrayName['submitMsgResult']['code'] . ' - message : '. $arrayName['submitMsgResult']['message'] ."\n";  
			        $mensaje .= ' ### http_code:'.$arrayName['__curl_info']['http_code']. ' - URL : '. $arrayName['__curl_info']['url'] ."\n";  

			        //print $mensaje;
			        $this->write_logCrone($mensaje);

					$this->addDetalleEnvio($arrayName['submitMsgResult']['id'], $id_envio, $requestNumbers[$id][0], $requestNumbers[$id][1]);

					//var_dump($response);

			    }
			}

			
			// foreach ($responses as $id => $response) {
			// //foreach ($responses as $response) {
			    
			//     if ($response instanceof SoapFault) {
			    
			//         print 'SoapFault: ' . $response->faultcode . ' - ' . $response->getMessage() . "\n";

			//     } else {
			       

			//         //print 'Response A SECAS : ' . $response->submitMsg . " |  ".  json_encode($response->submitMsg) ."\n";
			//         //print 'Response A submitMsgResult : ' . $response->submitMsgResult . " |  ".  json_encode($response->submitMsgResult) ."\n"; 
			//         $result = $response->submitMsgResult;
			//         print 'Response is x: ' . $result . "\n"; 
			        
			//         $arrayName = json_decode(json_encode($result), true);  

			// 		$this->addDetalleEnvio($arrayName['submitMsgResult']['id'], $id_envio, $number, $compania);

			//     }
			// }
			

			// for ($i=0; $i < count($responses); $i++) { 
			// 	# code...
			// 	print 'Response is: ' . $responses[$i]->submitMsgResult . "\n"; 
			// 	//print 'Response decode is: ' . json_encode($responses[$i]) . "\n"; 
			// }
			



			//DEBUG
						


			//##############################################################
			//##############################################################
			//##############################################################
			//################# FIN NUEVO ENVÍO ASYNC #################
			//##############################################################
			//##############################################################
			//##############################################################
			//############################################################## 
			*/
			

		} catch (Exception $e) {
			
			$this->write_logCrone('ERROR Envío SMS MASIVO - datos: id_envio->'.$id_envio.'-'.$id_envio_programado.' , remitente-> '.$remitente .' , momento_envio-> '.$momento_envio .' , id_empresa-> '
    		.$id_empresa .' , titulo-> '.$titulo .', Error:'.$e->getMessage().' - '.$e->getFile().' - line: '.$e->getLine()
    		.' - Trace:'.$e->getTraceAsString());

			if ($id_envio != 0) {
				$this->updateEnvioEstado($id_envio, 'Error');
			} else if ($id_envio_programado != 0){
				$this->updateEnvioEstado($id_envio_programado, 'Error');
			} 

		}
			
	}


	public function addSms($cuerpo, $remitente, $guardar, $id_empresa = '', $titulo = '', $nombre_mensaje_predefinido = '') {		
		$sql  = "INSERT INTO mensaje SET tipo = 'SMS' ";		
		$sql .= ", cuerpo = '" . $cuerpo . "'";
		$sql .= ", is_predefinido = " . ($guardar);
		$sql .= ", fecha_creacion = NOW()";		
		$sql .= ", remitente = '" . $remitente . "'";

		$sql .= ", id_empresa = '" . $id_empresa . "'";


		if($guardar == 0){
			$sql .= ", titulo = '" . ($titulo) . "'";
		}else{
			$sql .= ", titulo = '" . ($nombre_mensaje_predefinido) . "'";
		}    	
		   	

		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();		
		
		$id_mail = $this->conDB->lastInsertId();

		return $id_mail;
	}
	
	
	public function insertConsumo($id_empresa , $consumo_mail, $consumo_sms, $valor) {	

		$cuenta = "SELECT id_cuenta_corriente FROM cuenta_corriente WHERE id_empresa = ".$id_empresa;
		$stmt = $this->conDB->prepare($cuenta);
		$stmt->execute();
		$result = $stmt->fetch();

		$sql  = "INSERT INTO consumo SET ";		
		$sql .= "id_cuenta_corriente = '" . $result['id_cuenta_corriente'] . "'";
		$sql .= ", sms_consumidos = " . $consumo_sms;
		$sql .= ", fecha = NOW()";		
		$sql .= ", mail_consumidos = '" . $consumo_mail . "'";
		$sql .= ", valor = ". $valor;

		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();	

		$this->actualizarCuenta($id_empresa,$consumo_sms);	
		
		$id_consumo = $this->conDB->lastInsertId();

		return $id_consumo;
	}


	public function getEquivalencia($id_parametro)
	{
		$parametro = "SELECT * FROM parametro WHERE id_parametro = '".$id_parametro."'";
		$stmt = $this->conDB->prepare($parametro);
		$stmt->execute();
		$result = $stmt->fetch();

		return $result;
	}


	public function addEnvio($estado ,$remitente, $id_mensaje, $tipo_envio, $tipo_mensaje, $nombre_envio, $id_empresa = '', $id_lista = ''){			
    	
    	$sql =  "INSERT INTO envio SET";
    	$sql .= " estado = '". $estado ."'";
    	$sql .= ",remitente = '". $remitente ."'";		
		$sql .=',id_mensaje =' . $id_mensaje;		
		$sql .=",tipo_envio = '" . $tipo_envio . "'";
		$sql .=",tipo_mensaje = '" . $tipo_mensaje . "'";
		$sql .=",cuando_enviar = NOW()";		
		$sql .=",nombre_envio = '" . ($nombre_envio) . "'";				

		if ($id_lista != '') {
			$sql .= ",id_lista = " . $id_lista;
		}		


		$sql .= ",id_empresa = " . $id_empresa;


		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();
		$id_envio = $this->conDB->lastInsertId();					

		return $id_envio;    	
   	}

   	public function updateEnvio($id_envio){			
    	
    	return $this->updateEnvioEstado($id_envio, 'en proceso');    	
   	}

   	public function updateEnvioEstado($id_envio, $estado_envio){			
    	
    	$sql =  "UPDATE envio SET estado = '".$estado_envio."' WHERE id_envio = ".$id_envio;    	

		$stmt = $this->conDB->prepare($sql);
				

		if (!$stmt->execute()){
			$this->write_log('Error - SmsController - updateEnvioEstado - detalle:'.implode(":",$stmt->errorInfo()));
			return 0;
		} else {
			return $id_envio;    		
		}

   	}   

   	public function addDetalleEnvio($id_respuesta_servidor, $id_envio, $destinatario, $compania){		

		$sql  = "INSERT INTO detalle_envio SET ";
		$sql .=	" id_respuesta_servidor =  '" . $id_respuesta_servidor . "'";

        if($id_respuesta_servidor == '-1'){
	    	$sql .= ",estado = 'UNKNOWN'" ;
		} else if ($id_respuesta_servidor == '0') {
			$sql .= ",estado = 'INVALID_DNS'" ;
        } else {
        	$sql .= ",estado = 'en proceso'" ;
        }
        $sql .=",fecha = NOW()";		
		$sql .=	",destinatario =  '" . $destinatario . "'";
		$sql .= ",id_envio = " . $id_envio;

		$sql .= ", empresa_telefono_receptor = '" . $compania . "'";

		//$this->write_logCrone('ADD DETALLE - SQL:'.$sql);


		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();		
		
		$id_envio = $this->conDB->lastInsertId();

		return $id_envio;
   	}

   	/*metodos para asignar empresa de telefonia */
   	public function check_compania($numero)
	{
		$numero_preparado = strlen($numero) > 8 ? substr($numero, strlen($numero) - 8 ) : $numero;

		$cantidad_caracteres = array(6, 5, 4);
		$compania = 'SIN EMPRESA';	

		foreach ($cantidad_caracteres as $value) {
			$finder = $this->find_rango($numero_preparado, $value);
			if (!empty($finder['compania'])) {
				$compania = $finder['compania'];
				break;
			}
		}

		return $compania;
	}

	public function find_rango($numero, $length)
	{
		$rango = substr($numero, 0, $length);
		$sql = "SELECT compania FROM tabla_numeracion WHERE rango = ".  $rango ;
		
		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch();

		return $result;
	}

	
	public function getMsgStatusById($idMensaje){

		try {
			$response = array();		
			$params = array(
			"clientid" => $this->clientIdSms,
			"clientpassword" => $this->clientPassSms,
			"messageId" => $idMensaje
			);
			
			$result = $this->clientServerSms->__soapCall("enquireMsgStatus", array($params));

			return $result;	

		} catch (Exception $e) {
			$this->write_logCrone('ERROR getMsgStatusById - Error:'.$e->getMessage().' - '.$e->getFile().' - line: '.$e->getLine()
	    		.' - Trace:'.$e->getTraceAsString());
		}

		
	}
	

	public function getMensajesRecibidosById(){		
		//if($this->checkConnectusKey($connectusKey)){	
			
			$params = array(
			"clientid" => $this->clientIdSms,
			"clientpassword" => $this->clientPassSms,
			"count" => 10
			);

			
			$result = $this->clientServerSms->__soapCall("getReceivedMessages", array($params));

			//$arrayName = json_decode(json_encode($result), true);                          

			//return $arrayName['getReceivedMessagesResult']['messages'];
			

			return $result;

		/*}else{
			$response["Mensaje"] = "Tu api key no es valida porfavor registate en connectus o contactanos al siguiente correo: info@connectus.com";
			return $response;													
		}*/	
	}

	public function checkCreditosDisponibles($id_empresa, $requeridos){
		$sql = "SELECT id_cuenta_corriente AS cuenta FROM cuenta_corriente WHERE id_empresa = " . $id_empresa;
		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch();
		//variables sql y stmt toman nuevos valores.
		$sql = "SELECT (saldo_sms - consumidos_sms) AS disponibles FROM cuenta_corriente WHERE id_cuenta_corriente = " . $result['cuenta'];
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
		$sql = "SELECT saldo_sms AS saldo, consumidos_sms AS usados from cuenta_corriente WHERE id_empresa = " . $id_empresa;

		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetch();

		$nuevo_saldo_sms = $result['saldo'];
		$consumidos = $result['usados'] + $consumo;

		$sql = "UPDATE cuenta_corriente SET saldo_sms = " . $nuevo_saldo_sms . ", consumidos_sms = " . $consumidos . " WHERE id_empresa = " . $id_empresa;
		$stmt = $this->conDB->prepare($sql);
		$stmt->execute();
		//$result = $stmt->fetch();

	}

	public function write_log($cadena)
	{
		$arch = fopen(DIR_LINK . 'connectus/Logs/sms_log.txt', 'a');
		date_default_timezone_set('America/Santiago');
		fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r('-------------------------', true) . "\n");
		fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r($cadena, true) . "\n");

		fclose($arch);
	}

	public function write_logCrone($cadena)
	{
		$arch = fopen('/home/connectus/public_html/' . 'connectus/Logs/adminconnectuslog.txt', 'a');
		date_default_timezone_set('America/Santiago');
		fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r('-------------------------', true) . "\n");
		fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r($cadena, true) . "\n");

		fclose($arch);
	}

	#### Se añade FAKE SEND PARA PRUEBAS

	public function fakeSend($cadena)
	{

		$cadena = str_ireplace('&', ' - ', $cadena); //Se sacan espacios
		$arch = fopen(DIR_LINK . 'connectus/Logs/sms_fake_send.txt', 'a');
		date_default_timezone_set('America/Santiago');
		fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r($cadena, true) . "\n");

		fclose($arch);
	}

	public function fakeSendCron($cadena)
	{
		
		$cadena = str_ireplace('&', ' - ', $cadena); //Se sacan espacios
		$arch = fopen('/home/connectus/public_html/' . 'connectus/Logs/sms_fake_send.txt', 'a');
		date_default_timezone_set('America/Santiago');
		fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r($cadena, true) . "\n");

		fclose($arch);
	}

	#### CONTROL DE CAMBIO - VALIDA Y ACTUALIZA FORMATO - fabrizio 20150922 #####

	public function checkAndParseCellphoneNumber($cellphone){		

		$formatNumber = $cellphone;
		$formatNumber = str_ireplace(' ', '', $formatNumber); //Se sacan espacios
		$formatNumber = str_ireplace('+', '', $formatNumber); //Se saca +
		$formatNumber = str_ireplace('(', '', $formatNumber); //Se saca (
		$formatNumber = str_ireplace(')', '', $formatNumber); //Se saca )
		
		//Si largo = 8, se antepone 569
		if (strlen($formatNumber) == 8){ 
			$formatNumber = '569'.$formatNumber;
		}

		return $formatNumber;


	}

	public function messageClean($message) {
		$message = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $message ); 
		$message = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $message ); 
		$message = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $message ); 
		$message = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $message ); 
		$message = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $message ); 
		$message = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $message ); 		
		$message = str_replace("@", "!", $message ); 

		return $message;
	}

	public function getMessageStatus($id_message) {	

		$cuenta = "SELECT estado FROM detalle_envio WHERE id_respuesta_servidor = '".$id_message."'";
		$stmt = $this->conDB->query($cuenta);
		//$stmt->execute();
		$result = $stmt->fetch();

		//$this->model_sms_envio->getEnvioSMSUsuario($user_id, $data);


		if (empty($result['estado'])) {
			return '046';
		} else {
			$estado_format = str_ireplace(' ', '_', $this->traducirEstado($result['estado'])); //Se sacan espacios
			return $this->messageClean($estado_format);
		}
		
	}


	public function traducirEstado($estado)
    {
        $retorno = '';

        if($estado == 'DELIVERED' || $estado == 'CONFIRMED DELIVERY'){
            $retorno = 'Confirmado';

        } else if($estado == 'UNDELIVERED' || $estado == 'UNKNOWN'){
            $retorno = 'No entregado';

        } else if($estado == 'WAITING FOR CONFIRMATION' || $estado =='ROUTING'|| $estado == 'INCOMMING' || $estado == 'DEFERRED' || $estado ==  'SENT' ){
            $retorno = 'Esperando confirmación';

        } else if ($estado == 'en proceso' ) {
            $retorno = 'En proceso';

        } else if ($estado == 'desconocido') {
            $retorno = 'Pendiente';

        } else if ($estado == 'terminado') {
            $retorno = 'Terminado';
            
        } else if ($estado == 'INVALID_DNS') {
            $retorno = 'DNS Inválido';

        }

        return $retorno;

    }


	public function sendSMStoTeleco($number, $message, $id_envio, $rest = ''){

		//Se setea formato
		$number = $this->checkAndParseCellphoneNumber($number);

		//Se limpia y sacan acentos al mensaje
		$message = $this->messageClean($message); 

		//Revisa compañía en tabla de portabilidad
		$compania = $this->check_compania($number);

		$unsplitMessage = wordwrap($message,$this->largo_max_mensaje,'|',true);
		$splitMessage = explode('|',$unsplitMessage);

		$sendMessages = 0;

		$id_mensaje;

		foreach($splitMessage as &$readyMessage){

			$params = array(
				"clientid" => $this->clientIdSms,
				"clientpassword" => $this->clientPassSms,
				"ani" =>  $this->clientAni,
				"dnis" => $number,
				"message" => utf8_encode($readyMessage)
				);

			//DEBUG
			//$this->fakeSendCron($params["message"]);

			if ($compania != 'SIN EMPRESA') {

				//Manda porque está dentro de la tabla de portabilidad

				$result = $this->clientServerSms->__soapCall('submitMsg' ,array($params));

				//DEBUG
				$arrayName = json_decode(json_encode($result), true);  

				//DEBUG
				//$this->addDetalleEnvio('-1', $id_envio, $telefono);
				$this->addDetalleEnvio($arrayName['submitMsgResult']['id'], $id_envio, $number, $compania);

				$id_mensaje[] = $arrayName['submitMsgResult']['id'];

			} else {
				//No envía porque no está en la tabla de portabilidad

				//DEBUG
				//$this->addDetalleEnvio('-1', $id_envio, $telefono);
				$this->addDetalleEnvio('0', $id_envio, $number, $compania);
			}

			$sendMessages++;
		}

		if (empty($rest)) {
			return $sendMessages;
		} else {
			return $id_mensaje;
		}

		

	}


	public function sendSMStoTelecoASYNC($number, $message, $id_envio, $rest = ''){

		//Se setea formato
		$number = $this->checkAndParseCellphoneNumber($number);

		//Se limpia y sacan acentos al mensaje
		$message = $this->messageClean($message); 

		//Revisa compañía en tabla de portabilidad
		$compania = $this->check_compania($number);

		$unsplitMessage = wordwrap($message,$this->largo_max_mensaje,'|',true);
		$splitMessage = explode('|',$unsplitMessage);

		$sendMessages = 0;

		$id_mensaje;

		foreach($splitMessage as &$readyMessage){

			$params = array(
				"clientid" => $this->clientIdSms,
				"clientpassword" => $this->clientPassSms,
				"ani" =>  $this->clientAni,
				"dnis" => $number,
				"message" => utf8_encode($readyMessage)
				);

			//DEBUG
			//$this->fakeSendCron($params["message"]);

			if ($compania != 'SIN EMPRESA') {

				//Manda porque está dentro de la tabla de portabilidad

				$result = $this->clientServerSms->__soapCall('submitMsg' ,array($params));

				//DEBUG
				$arrayName = json_decode(json_encode($result), true);  

				//DEBUG
				//$this->addDetalleEnvio('-1', $id_envio, $telefono);
				$this->addDetalleEnvio($arrayName['submitMsgResult']['id'], $id_envio, $number, $compania);

				$id_mensaje[] = $arrayName['submitMsgResult']['id'];

			} else {
				//No envía porque no está en la tabla de portabilidad

				//DEBUG
				//$this->addDetalleEnvio('-1', $id_envio, $telefono);
				$this->addDetalleEnvio('0', $id_envio, $number, $compania);
			}

			$sendMessages++;
		}

		if (empty($rest)) {
			return $sendMessages;
		} else {
			return $id_mensaje;
		}

		

	}



}
