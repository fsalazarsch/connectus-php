<?php
    

    $error_log = 'recibidos_celcom.txt';

    $handle = fopen('php://input','r');
    $jsonInput = fgets($handle);

    $arch = fopen($error_log, 'a');

    fwrite($arch, "Inicio " . date('Y-m-d G:i:s'));
    fWrite($arch, "\n -->"  . json_encode($jsonInput));
    fWrite($arch, "\n "     . json_encode($_REQUEST));



    if(     isset($_REQUEST['nro_movil']) 
        AND isset($_REQUEST['nro_corto']) 
        AND isset($_REQUEST['texto']) 
        AND isset($_REQUEST['idms']) )
    {
        header('Content-type: text/plain; charset=utf-8');
        echo '<status>000</status>';
        fWrite($arch, "\nR: <status>000</status>");

        $exito = true;

    }else{
        header('Content-type: text/plain; charset=utf-8');
        echo '<status>001</status>';
        fWrite($arch, "\nR: <status>000</status>");

        $exito = false;
    }

/*
    if($exito)
    {
        # Guardar estado en BD

        require_once __DIR__.'/../SMS/Models/DetalleEnvio.php';

        $detalle = new DetalleEnvio();

        $detalle->id_respuesta  = $_POST['id_msg'];
        $detalle->estado        = $_POST['id_std'];

        $detale->editByIdMsg();
    }
        
*/
    
    fwrite($arch,"\nEjecucion terminada\n\n\n");
    fclose($arch);
?>