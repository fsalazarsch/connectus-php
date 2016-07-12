<?php
require '.././libs/Slim/Slim.php';
require '.././ConnectusController.php';
require '.././MandrillController.php';
require __DIR__.'/SMS/SMSController.php';
require '.././db.class.php';
require '../.././config.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app = \Slim\Slim::getInstance();

//Metodo post de Mailgun

$app->post('/send_email', function() use ($app) {

	verifyRequiredParams(array('nombre_remitente', 'email_remitente', 'destinatario', 'asunto', 'mensaje', 'username', 'password'));
			
	// reading post params
	$nombre_remitente = $app->request->post('nombre_remitente');
	$correo_remitente = $app->request->post('email_remitente');
	$destinatario = $app->request->post('destinatario');		  
	$asunto = $app->request->post('asunto');	   
	$mensaje = $app->request->post('mensaje');	  
	$username = $app->request->post('username');	  	
	$password = $app->request->post('password');	  	

	$check = checkUser($username, $password);

	if ($check['id_empresa'] != null) {	
		$conector = getConector($check['id_empresa']);
	
		if ($conector['id_conector_mail'] == 3) {
			$api = new MandrillController();	
		}elseif($conector['id_conector_mail'] == 2){
			$api = new ConnectusController();
		}else{
			//return '004';
			$result['response'] = '004';
		}	

		$response = $api->sendMailRest($nombre_remitente, $correo_remitente, $destinatario, $asunto, $mensaje, $check['id_empresa']);

		if ($response != '044' || $response != '004') {

			$result['response'] = '101';
			$result['id_mensaje'] = $response;
			
		} else {

			$result['response'] = $response;
		}


	}else{
		$result['response'] = '010';
	}	
	
	
	echoResponse(200,$result);
   
});


$app->get('/get_status_email_sent', function() use ($app) {
	
	verifyRequiredParams(array('id_mensaje', 'username', 'password'));
	
	$username = $app->request()->get('username');	  	
	$password = $app->request->get('password');
	$idMensaje = $app->request->get('id_mensaje');	

	$check = checkUser($username, $password);

	if ($check['id_empresa'] != null) {

		$conector = getConector($check['id_empresa']);
	
		if ($conector['id_conector_mail'] == 3) {
			$api = new MandrillController();	
		}elseif($conector['id_conector_mail'] == 2){
			$api = new ConnectusController();
		}else{
			//return '004'; 
			$result['response'] = '004';
		}

		$estado = $api->getMessageStatus($idMensaje);

		if ($estado == '046') {
			$result['response'] = '046';
		} else {
			$result['response'] = '101';
			$result['estado'] = strtolower($estado);
			$result['id_mensaje'] = $idMensaje;
		}
		

	}else{
		$result['response'] = '010';
	}

	echoResponse(200,$result);

   
});

//Metodos post de teleco

$app->post('/send_sms', function() use ($app) {

	verifyRequiredParams(array('mensaje','numero','username', 'password'));

	// reading post params
	$mensaje = $app->request()->post('mensaje');    
	$numero = $app->request()->post('numero');  

	$username = $app->request->post('username');	  	
	$password = $app->request->post('password');

	$check = checkUser($username, $password);

	//echoResponse(200,"OK");

	if ($check['id_empresa'] != null) {

		# llamamos al controlador que nos devolvera la api/driver correspondiente
        $sms = new SMSController($check['id_empresa']);

        if(!is_null($sms->error)){
            echo "Error: ".$sms->error;
            return false;
        }

        $api = $sms->getAPI();

		$response = $api->sendSmsRest($numero, $mensaje, $check['id_empresa']);

		if ($response != '044' || $response != '004') {

			$result['response'] = '101';
			if (count($response) == 1) {
				$result['id_mensaje'] = $response;
			} else if (count($response) > 1) {
				$result['id_mensaje'] = $response;
			}
			
		} else {

			$result['response'] = $response;
		}
		

		
	}else{
		$result['response'] = '010';
	}

	echoResponse(200,$result);
   
});


$app->get('/get_status_sms_sent', function() use ($app) { 
	
		verifyRequiredParams(array('id_mensaje', 'username', 'password'));

        // reading post params
        $idMensaje = $app->request()->get('id_mensaje');        
        $username = $app->request->get('username');	  	
		$password = $app->request->get('password');       
			
		$check = checkUser($username, $password);

		if ($check['id_empresa'] != null) {
			
			# llamamos al controlador que nos devolvera la api/driver correspondiente
	        $sms = new SMSController($check['id_empresa']);

	        if(!is_null($sms->error)){
	            echo "Error: ".$sms->error;
	            return false;
	        }

	        $api = $sms->getAPI();

			$estado = $api->getMessageStatus($idMensaje);

			if ($estado == '046') {
				$result['response'] = '046';
			} else {
				$result['response'] = '101';
				$result['estado'] = strtolower($estado);
				$result['id_mensaje'] = $idMensaje;
			}

		}else{
			$result['response'] = '010';
		}
		echoResponse(200,$result);
   
});

$app->get('/get_credits', function() use ($app) {
	
	verifyRequiredParams(array('username', 'password'));
	
	$username = $app->request()->get('username');	  	
	$password = $app->request->get('password');

	$check = checkUser($username, $password);

	if ($check['id_empresa'] != null) {

		$credits = getCredits($check['id_empresa']);
	
		if (!empty($credits)) {
			$result['response'] = '101';
			$result['sms'] = $credits['saldo_sms'];
			$result['email'] = $credits['saldo_mail'];
		} else {
			$result['response'] = '004';
		}
		

	}else{
		$result['response'] = '010';
	}

	echoResponse(200,$result);

   
});

/*
$app->post('/get_messages', function() use ($app) {	
        
			
		$api = new SmsController();
		$response = $api->getMensajesRecibidosById();

		$arrayName = json_decode(json_encode($response), true);  
		
				
		echoResponse(200,$arrayName['getReceivedMessagesResult']['messages']['smsMessage'][0]);
   
});
*/
//Metodos Varios

function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        //$response["error"] = true;
        //$response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        $response["response"] = '040';
        echoResponse(400, $response);
        $app->stop();
    }
}

function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

function checkUser($username, $password)
{

	$sql =  "SELECT id_empresa FROM assert_user WHERE username = '". $username ."' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . ($password) . "'))))) OR password = '" . (md5($password)) . "')";    	

	$bd = new database();
	$bd->conectar('connectu_test');

	$resultado = $bd->consulta($sql)->fetch();

	$bd->desconectar();

	return $resultado;		
}

function getConector($id_empresa){
	
	$sql = 'SELECT id_conector_mail FROM cuenta_corriente WHERE id_empresa = '.$id_empresa;

	$bd = new database();
	$bd->conectar('connectu_adm_test');

	$resultado = $bd->consulta($sql)->fetch();

	$bd->desconectar();

	return $resultado;

}

function getCredits($id_empresa){
	
	$sql = 'SELECT saldo_sms - consumidos_sms as saldo_sms , saldo_mail - consumidos_mail as saldo_mail FROM cuenta_corriente WHERE id_empresa = '.$id_empresa;

	$bd = new database();
	$bd->conectar('connectu_adm_test');

	$resultado = $bd->consulta($sql)->fetch();

	$bd->desconectar();

	return $resultado;

}

$app->run();
