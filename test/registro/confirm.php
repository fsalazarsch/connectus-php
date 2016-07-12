<?php 
	
	$data = array();
    $data['estado'] = true;

	# Comprobamos los datos enviados

    if(!isset($_GET['correo']) OR empty($_GET['correo'])){
        $data['error']  = 'Variable correo vacía.';
        $data['estado'] = false;
    }else{
        $correo = $_GET['correo'];
    }

    if(!isset($_GET['token']) OR empty($_GET['token'])){
        $data['error']  = 'Variable token vacía.';
        $data['estado'] = false;
    }else{
        $token = $_GET['token'];
    }


    require 'models/Cliente.php';

    $cliente = new Cliente();
	$cliente->setEmail($correo);
	
	if(!$cliente->confirm($token))
	{
		$data['error']  = 'Error al confirmar correo.';
		$data['estado'] = false;
	}

	if($data['estado'])
	{
		$html = file_get_contents(__DIR__.'/views/confirmado.html');
        echo $html;
	}else
	{
		header('Location: /admin');
	}
	


?>