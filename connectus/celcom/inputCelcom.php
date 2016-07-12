<?php
    

    $error_log = '../Logs/celcom_log.txt';

    $handle = fopen('php://input','r');
    $jsonInput = fgets($handle);

    $arch = fopen($error_log, 'a');

    fwrite($arch, "Inicio " . date('Y-m-d G:i:s'));
    fWrite($arch, "\n "     . json_encode($_REQUEST));



    if(isset($_REQUEST['id_msg']) AND isset($_REQUEST['id_std'])){
        header('Content-type: text/plain; charset=utf-8');
        echo '<status>000</status>';
        fWrite($arch, "\nR: <status>000</status>");

    }else{
        header('Content-type: text/plain; charset=utf-8');
        echo '<status>001</status>';
        fWrite($arch, "\nR: <status>000</status>");
    }




    /*
    echo "<br><br>";
    echo "IDMSG: ".$_POST['id_msg']."<br>";
    echo "IDSTD: ".$_POST['id_std']."<br>";
    */


    //fclose($arch);

    /*foreach ($decoded as $key => $respuesta) {


        switch ($respuesta['event']) {

            case 'send':
            case 'soft_bounce':
            case 'deferral':
            case 'reject':
            case 'rejected':
            case 'hard_bounce':
            case 'unsub':

                # Guardamos el estado del mail
                    $sql = "UPDATE detalle_envio 
                            SET estado = '". $respuesta['event'] ."' 
                            WHERE id_respuesta_servidor = '". $respuesta['_id']."' ";
                break;

            case 'open':
            case 'click':
            case 'spam':
                    # hacemos un sumatoria

                    $sql = "SELECT estado_".$respuesta['event']." AS suma
                            FROM detalle_envio WHERE id_respuesta_servidor = '". $respuesta['_id']."' ";

                    $sth = $conexion->prepare($sql);
                    $sth->execute();
                    $result = $sth->fetch();

                    $total = $result['suma'] + 1;

                    $sql = "UPDATE detalle_envio 
                            SET estado_".$respuesta['event']." = ".$total." 
                            WHERE id_respuesta_servidor = '". $respuesta['_id']."' ";

                break;

            default:
                # Guardamos el estado del mail
                    $sql = "UPDATE detalle_envio 
                            SET estado = '". $respuesta['event'] ."' 
                            WHERE id_respuesta_servidor = '". $respuesta['_id']."' ";
            break;

        }


        //$sql = "UPDATE detalle_envio set estado = '". $respuesta['event'] ."' WHERE id_respuesta_servidor = '". $respuesta['_id']."' ";
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute(); 

        $sql = "\n" . $sql . "\n";

        $arch = fopen($error_log, 'a');
        fWrite($arch,$sql);
        fclose($arch);
    }
    
    */


    #echo "Error_log: " . filesize($error_log) . " bytes \n ".$arch; 

    //$arch = fopen($error_log, 'a');
    
    fwrite($arch,"\nEjecucion terminada\n\n\n");
    fclose($arch);

?>