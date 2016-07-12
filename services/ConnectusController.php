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
		$this->mailgun = new Mailgun("key-5ca40c04a54914f964c4d2531744748f");									  
		$this->domain = "sandbox580ea252acb74166b16e8b68e424e75a.mailgun.org";	        

		//Datos del servidor sms SOAP
		ini_set("soap.wsdl_cache", "0");
		
		$this->clientServerSms = new SoapClient(dirname(__FILE__) .'/server_teleco.xml',array(
		'exceptions'=>true,
		'cache_wsdl'=>WSDL_CACHE_MEMORY,
		'encoding'=>'utf-8',
		'stream_context' => stream_context_create(array('http' => array('protocol_version' => 1.0) ) )));
					
		$this->clientIdSms = 'telecotesting';
		$this->clientPassSms = 'matias2015';
		$this->clientAni = '56442147839';
        $this->largo_max_mensaje = 50;
		$this->conDB = new mysqli(DB_HOST_REST, DB_USERNAME_REST, DB_PASSWORD_REST, DB_NAME_REST);

	}	

/*Metodos de Mailgun*/

/*
* Enviar nuevo Mail
*
* @return retorna response de mailgun con parametros -> id y message
*/
	public function sendMail($remitente, $destinatario, $asunto, $mensaje, $connectusKey ){				   
		$response = array();
	//	if($this->checkConnectusKey($connectusKey)){
			$responseMail = $this->mailgun->sendMessage($this->domain, array(
			'from'    => $remitente,
			'to'      => $destinatario,
			'subject' => $asunto,
			'text'    => $mensaje,
			'o:tracking' => true,
			'o:tag'   => array($connectusKey)
			));											  										
			return $responseMail;
			
	//	}else{
	//		$response["Mensaje"] = "Tu api key no es valida porfavor registate en connectus o contactanos al siguiente correo: info@connectus.com";
	//		return $response;
	//	}
	}

	/*
	* Metodo para traer mail por id
	*
	* @return 
	*/
	public function getSendMailById($messageid, $connectusKey){	
		$response = array();
	//	if($this->checkConnectusKey($connectusKey)){
			
			$queryString = array(		
			'message-id'      => $messageid,			
			);
			
			# Make the call to the client.
			$result = $this->mailgun->get($this->domain."/events", $queryString);

			return $result;			
	//	}else{
	//		$response["Mensaje"] = "Tu api key no es valida porfavor registate en connectus o contactanos al siguiente correo: info@connectus.com";
	//		return $response;
	//	}
	}
	
    /*
	* Metodo para traer mail por id
	*
	* @return 
	*/
	public function getAllSendMail($connectusKey){	
		$response = array();
		//if($this->checkConnectusKey($connectusKey)){
			
			$queryString = array(		
			'tags'      => $connectusKey,			
			);
			
			# Make the call to the client.
			$result = $this->mailgun->get($this->domain."/events", $queryString);
			
			//foreach($result as &$items){
			//	$response["Mensaje"] = $items;
			//}

			return $result;			
		//}else{
		//	$response["Mensaje"] = "Tu api key no es valida porfavor registate en connectus o contactanos al siguiente correo: info@connectus.com";
		//	return $response;
		//}
	}

	
	
	/*
	* Metodo para traer los desinscritos
	*
	* @return 
	*/
	public function getDesinscritos($connectusKey){	
		$response = array();
		//if($this->checkConnectusKey($connectusKey)){

			# Make the call to the client.
			$result = $this->mailgun->get($this->domain."/unsubscribes", array(
			'limit' => 5,
			'skip' => 10
			));												  	
			return $result;		

//		}else{
//			$response["Mensaje"] = "Tu api key no es valida porfavor registate en connectus o contactanos al siguiente correo: info@connectus.com";
//			return $response;
//		}
	}

	/*
	* Enviar email masivos
	*
	* @return retorna response de mailgun con parametros -> id y message
	*/
	public function sendMassMail($remitente, $destinatarios = array(), $asunto, $mensaje, $connectusKey){	
		set_time_limit(20000);
		$response = array();
		//if($this->checkConnectusKey($connectusKey)){
			$result = array();							
			foreach ($destinatarios as &$destinatario) {
				$responseMail = $this->mailgun->sendMessage($this->domain, array(
				'from'    => $remitente,
				'to'      => $destinatario,
				'subject' => $asunto,
				'text'    => $mensaje,
				'o:tracking' => true,
				'o:tag'   => array($connectusKey)
				));	
				array_push($result, $responseMail);											  	
			}

			return $result;													
		//}else{
		//	$response["Mensaje"] = "Tu api key no es valida porfavor registate en connectus o contactanos al siguiente correo: info@connectus.com";
		//	return $response;
		//}
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
	public function sendSms($numero, $mensaje, $connectusKey){
		$response = array();

       // if($this->checkConnectusKey($connectusKey)){
            // Consulta si el mensaje es menor a 50 caracteres si es asi lo envia de forma unica
            if(strlen($mensaje) < 50){
                $params = array(
                    "clientid" => $this->clientIdSms,
                    "clientpassword" => $this->clientPassSms,
                    "ani" => $this->clientAni,
                    "dnis" => $numero,
                    "message" => $mensaje
                );

                $result = $this->clientServerSms->__call("submitMsg", array($params));

                return $result;
            }else{
                //En el caso de que el mensaje se mayor a 50 caracteres se analiza y divide cada 50 caracteres
                $mensaje_a_separar = wordwrap($mensaje,50,'/');
                $mensaje_separado = explode('/',$mensaje_a_separar);
                foreach($mensaje_separado as &$separado){
                    $params = array(
                        "clientid" => $this->clientIdSms,
                        "clientpassword" => $this->clientPassSms,
                        "ani" => $this->clientAni,
                        "dnis" => $numero,
                        "message" => $separado
                    );

                   $result =  $this->clientServerSms->__call("submitMsg", array($params));
                    array_push($response, $result);
                }
                return $response;
            }
        //}else{
          //  $response["Mensaje"] = "Tu api key no es valida porfavor registate en connectus o contactanos al siguiente correo: info@connectus.com";
			//return $response;
//		}
	}

	/*
	* Enviar mensajes masivos
	*
	* @return un arreglo con todos los mensajes enviados
	* submitMsgResult: {
	* code: 0
	* message: "Message Queued"
	* id: 135048428
	* }
	*/
	public function sendMassSms($id_lista, $mensaje, $connectusKey){
		set_time_limit(20000);
		$response = array();

        /* el siguiente metodo debe ser ejecutado por el backend al momento del envio y asi mandar un array con los
         * datos de los contactos pertenecientes a la lista espefificada con el id_lista y recorrer
         * las acciones para recorrer y enviar masivo con lista esta completo solo debe ser ejecutado por el backend
         */
        /////Se debe sacar una ves que funcione el backend y cambiar los parametros a ($lista = array(), $mensaje, $connectusKey)
        $lista = $this->listasDeContactos($id_lista);
        ////************************//////

		//if($this->checkConnectusKey($connectusKey)){

            foreach ($lista as &$contacto) {
                // Consulta si el mensaje es menor a 50 caracteres si es asi lo envia de forma unica
                if(strlen($mensaje) < 50){
                    $params = array(
                        "clientid" => $this->clientIdSms,
                        "clientpassword" => $this->clientPassSms,
                        "ani" => $this->clientAni,
                        "dnis" => $contacto['numero'],
                        "message" => str_ireplace('@nombre',$contacto['nombre'],$mensaje)
                    );

                    /* Invoke webservice method with your parameters, in this case: Function1 */
                    $result = $this->clientServerSms->__call("submitMsg", array($params));
                    array_push($response,$result);

                }else{
                    //En el caso de que el mensaje se mayor a 50 caracteres se analiza y divide cada 50 caracteres
                    $mensaje_a_separar = wordwrap($mensaje,$this->$largo_max_mensaje,'/');
                    $mensaje_separado = explode('/',$mensaje_a_separar);
                    foreach($mensaje_separado as &$separado){
                        $params = array(
                            "clientid" => $this->clientIdSms,
                            "clientpassword" => $this->clientPassSms,
                            "ani" => $this->clientAni,
                            "dnis" => $contacto['numero'],
                            "message" => str_ireplace('@nombre',$contacto['nombre'],$separado)
                        );

                        /* Invoke webservice method with your parameters, in this case: Function1 */
                        $result = $this->clientServerSms->__call("submitMsg", array($params));
                        array_push($response,$result);
                    }
                }
            }

            return $response;
//		}else{
//			$response["Mensaje"] = "Tu api key no es valida porfavor registate en connectus o contactanos al siguiente correo: info@connectus.com";
//			return $response;
//		}
	}

	/*
	* Consultar creditos restantes de sms a teleco
	*
	* @return 
	*
	* getMyCreditsResult: {
	* code: 0 <-- 0 es sin errores
	* quota: 99.5
	* message: "Success"
	* }
	*/
	public function getMyCreditsSms(){

		/* Set your parameters for the request */
		$params = array(
		"clientid" => $this->clientIdSms,
		"clientpassword" => $this->clientPassSms
		);

		/* Invoke webservice method with your parameters, in this case: Function1 */
		$response = $this->clientServerSms->__soapCall("getMyCredits", array($params));

		return $response;

	}

	/*
	* Consultar el estado de un mensaje con el id
	*
	* @return 
	*
	* enquireMsgStatusResponse: {
	* deliverydate : 2015-06-20 01:58:14.324796 <-- 0 es sin errores
	* id: 135048428
	* message: "Success"
	* }
	*/
	public function getMsgStatusById($idMensaje, $connectusKey){
		$response = array();
//		if($this->checkConnectusKey($connectusKey)){
			/* Set your parameters for the request */
			$params = array(
			"clientid" => $this->clientIdSms,
			"clientpassword" => $this->clientPassSms,
			"messageId" => $idMensaje
			);

			/* Invoke webservice method with your parameters, in this case: Function1 */
			$result = $this->clientServerSms->__soapCall("enquireMsgStatus", array($params));

			return $result;

//		}else{
//			$response["Mensaje"] = "Tu api key no es valida porfavor registate en connectus o contactanos al siguiente correo: info@connectus.com";
//			return $response;
//		}
	}

	//Metodos de validacion de connectusKey
	public function checkConnectusKey($connectusKey) {		 		         		 
		// fetching customer by token
		$stmt = $this->conDB->prepare("SELECT * FROM user WHERE token = ?");
		$stmt->bind_param("s", $connectusKey);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows > 0) {            
			$stmt->fetch();
			$stmt->close();                       
			return true;            
		} else {
			$stmt->close();
			// no existe connectus key en la base de datos
			return false;
		}
	}


    /*
     * * Este metodo trae los dontactos de una lista segun el id lista
     * *
     * * Retorna un array con el nombre y numero de cada contacto
     * */
    public function listasDeContactos($id_lista){

        //Se debe consultar y parametrizar otro campo ademas de nombre
        $stmt = $this->conDB->prepare("SELECT numero, firstname FROM contacto WHERE id_lista = ?");
        $stmt->bind_param("s", $id_lista);
        $stmt->execute();
        $stmt->bind_result($numero,$firstname);

        $array = array();
        while ($stmt->fetch()){
            $string = array();
            $string['nombre'] = $firstname;
            $string['numero'] = $numero;
            array_push($array,$string);
        }
        $stmt->close();
        return $array;

    }
}
