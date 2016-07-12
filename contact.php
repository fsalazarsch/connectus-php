<?php 

	/* ==========================  Define variables ========================== */

	#Your e-mail address
	define("__TO__", "contacto@connectus.cl");

	#Message subject
	define("__SUBJECT__", "");

	#Success message
	define('__SUCCESS_MESSAGE__', "Tu mensaje ha sido enviado, nos comunicaremos contigo a la brevedad. Gracias!");

	#Error message 
	define('__ERROR_MESSAGE__', "Ooops, tu mensaje no ha podido ser enviado");

	#Messege when one or more fields are empty
	define('__MESSAGE_EMPTY_FILDS__', "Por favor llena todos los campos");

	/* ========================  End Define variables ======================== */

	//Send mail function
	function send_mail($to,$subject,$message,$headers){
		if(@mail($to,$subject,$message,$headers)){
			echo json_encode(array('info' => 'success', 'msg' => __SUCCESS_MESSAGE__));
		} else {
			echo json_encode(array('info' => 'error', 'msg' => __ERROR_MESSAGE__));
		}
	}

	//Check e-mail validation
	function check_email($email){
		if(!@eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
			return false;
		} else {
			return true;
		}
	}

	//Get post data
	if(isset($_POST['name']) and isset($_POST['mail']) and isset($_POST['comment'])){
		
		$name 	 = $_POST['name'];
		$mail 	 = $_POST['mail'];
		$fono 	 = $_POST['fono'];
		$website  = $_POST['website'];
		$comment = $_POST['comment'];

		if($name == '') {
			echo json_encode(array('info' => 'error', 'msg' => "Por favor ingresa tu nombre."));
			exit();
		} else if($mail == '' or check_email($mail) == false){
			echo json_encode(array('info' => 'error', 'msg' => "Por favor ingresa un e-mail válido."));
			exit();
		} else if($comment == ''){
			echo json_encode(array('info' => 'error', 'msg' => "Por favor ingresa un mensaje."));
			exit();
		} else {
			//Send Mail
			$to = __TO__;
			$subject = __SUBJECT__ . ' ' . $name;
			$message = '
			<html>
			<head>
			  <title>Mail de '. $name .'</title>
			</head>
			<body>
			  <table class="table">
				<tr>
				  <th align="right">Nombre:</th>
				  <td align="left">'. $name .'</td>
				</tr>
				<tr>
				  <th align="right">E-mail:</th>
				  <td align="left">'. $mail .'</td>
				</tr>

				<tr>
				  <th align="right">Teléfono:</th>
				  <td align="left">'. $fono .'</td>
				</tr>

				<tr>
				  <th align="right">Asunto:</th>
				  <td align="left">'. $subject .'</td>
				</tr>
				<tr>
				  <th align="right">Mensaje:</th>
				  <td align="left">'. $comment .'</td>
				</tr>
			  </table>
			</body>
			</html>
			';

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: ' . $mail . "\r\n";

			send_mail($to,$subject,$message,$headers);
		}
	} else {
		echo json_encode(array('info' => 'error', 'msg' => __MESSAGE_EMPTY_FILDS__));
	}
 ?>