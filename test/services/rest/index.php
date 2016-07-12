<?php
require '../Slim/Slim.php';
require '../ConnectusController.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
     'debug' =>          false,
      'log.enabled' =>    true,
      'log.level' =>      \Slim\Log::DEBUG,
      'mode' =>           'production',
      'slim.errors' => fopen('./log/errors_slim.log', 'w'),
      'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter(array(
            'path' => './log/',
            'name_format' => 'Y-m-d',
            'message_format' => '%label% - %date% - %message%'
        ))
      )

);


//Metodo post de Mailgun

$app->post('/send_mail', function() use ($app) {

	verifyRequiredParams(array('remitente', 'destinatario', 'asunto', 'mensaje', 'connectus_key'));
			
	// reading post params
	$remitente = $app->request->post('remitente');
	$destinatario = $app->request->post('destinatario');
	$password = $app->request->post('password');	   
	$asunto = $app->request->post('asunto');	   
	$mensaje = $app->request->post('mensaje');	  
	$connectusKey = $app->request->post('connectus_key');	  	
	
	$api = new ConnectusController();
	$response = $api->sendMail($remitente, $destinatario, $asunto, $mensaje, $connectusKey);
		
	echoRespnse(200,$response);
   
});

$app->post('/send_mass_mail', function() use ($app) {

	verifyRequiredParams(array('remitente', 'destinatarios', 'asunto', 'mensaje', 'connectus_key'));
			
	// reading post params
	$remitente = $app->request->post('remitente');
	$destinatarios = $app->request->post('destinatarios');
	$password = $app->request->post('password');	   
	$asunto = $app->request->post('asunto');	   
	$mensaje = $app->request->post('mensaje');	  
	$connectusKey = $app->request->post('connectus_key');	  
	
	$destinatariosArray = explode(',',$destinatarios);
	
	$api = new ConnectusController();
	$response = $api->sendMassMail($remitente, $destinatariosArray, $asunto, $mensaje, $connectusKey);
		
	echoRespnse(200,$response);
   
});

$app->post('/get_send_mail_by_id', function() use ($app) {
	
	verifyRequiredParams(array('message_id','connectus_key'));
	
	$connectusKey = $app->request->post('connectus_key');	  
	$message_id = $app->request->post('message_id');	
	$api = new ConnectusController();
	$response = $api->getSendMailById($message_id, $connectusKey);
	
	echoRespnse(200,$response);
   
});

$app->post('/get_all_send_mail', function() use ($app) {
	
	verifyRequiredParams(array('connectus_key'));
	
	$connectusKey = $app->request->post('connectus_key');	  	
	$api = new ConnectusController();
	$result = $api->getAllSendMail($connectusKey);	
	
	echoRespnse(200,$result);
   
});

$app->post('/get_desinscritos', function() use ($app) {
	
	verifyRequiredParams(array('connectus_key'));
	
	$connectusKey = $app->request->post('connectus_key');	  
	$api = new ConnectusController();
	$response = $api->getDesinscritos($connectusKey);
		
	echoRespnse(200,$response);
   
});

//Metodos post de teleco

$app->post('/send_sms', function() use ($app) {

	verifyRequiredParams(array('mensaje','numero','connectus_key'));

	// reading post params
	$postVars = $app->request->post();
    $mensaje = $postVars['mensaje'];
	$numero = $postVars['numero'];
	$connectusKey = $postVars['connectus_key'];

	//$app->response()->header('Content-Type', 'application/json');
	//echo '{ "response": '.$numero.' }';

	
	$api = new ConnectusController();
	$response = $api->sendSms($numero, $mensaje, $connectusKey);
	
	//echoRespnse(200,$response);
	
  	$app->response()->header('Content-Type', 'application/json');
	echo '{ "response": "'.$response.'" }';
	
});

$app->post('/send_mass_sms', function() use ($app) {
	
	verifyRequiredParams(array('mensaje','id_lista','connectus_key'));

	// reading post params
	$mensaje = $app->request()->post('mensaje');
    $id_lista = $app->request()->post('id_lista');
	$connectusKey = $app->request->post('connectus_key');
	
	$api = new ConnectusController();	
	$response = $api->sendMassSms($id_lista, $mensaje, $connectusKey);
	
	echoRespnse(200,$response);

});

$app->post('/get_credits_sms', function() use ($app) {

		$api = new ConnectusController();
		$response = $api->getMyCreditsSms();
		
		echoRespnse(200,$response);
   
});

$app->post('/get_list', function() use ($app) {

    $api = new ConnectusController();
    $response = $api->listasDeContactos(0);

    echoRespnse(200,$response);

});

$app->post('/get_message_status', function() use ($app) {
	
		verifyRequiredParams(array('id_mensaje', 'connectus_key'));

        // reading post params
        $idMensaje = $app->request()->post('id_mensaje');        
        $connectusKey = $app->request()->post('connectus_key');        
			
		$api = new ConnectusController();
		$response = $api->getMsgStatusById($idMensaje, $connectusKey);
		
		echoRespnse(200,$response);
   
});

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
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}

function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

$app->run();

?>
