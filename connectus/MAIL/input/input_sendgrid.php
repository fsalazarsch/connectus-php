<?php

    require_once __DIR__."/../Models/Estado.php";
    

    $error_log = '../logs/log_input_sendgrid.txt';

    $data = file_get_contents("php://input");
    $objectos = json_decode($data, true);

    $arch = fopen($error_log, 'a');

    fwrite($arch, "Inicio " . date('Y-m-d G:i:s'));
    fwrite($arch, "\n".$data);

    if (is_array($objectos) || is_object($objectos))
    {
        foreach ($objectos as $obj)
        {
            switch ($obj['event']) {

                case 'processed':
                case 'deferred':
                case 'delivered':
                case 'bounce':
                case 'unsubscribe':

                    # Guardamos el estado del mail

                        $estado = new Estado();

                        $estado->destinatario   = $obj['email'];
                        $estado->id_envio       = $obj['id_envio'];
                        $estado->estado         = $obj['event'];

                        $estado->updateEstado();

                    break;

                case 'open':
                case 'click':
                case 'spamreport':
                        # hacemos un sumatoria

                        if($obj['event'] == 'spamreport'){
                            $evento = 'spam';
                        }else{
                            $evento = $obj['event'];
                        }

                        $estado = new Estado();

                        $estado->destinatario   = $obj['email'];
                        $estado->id_envio       = $obj['id_envio'];
                        $estado->estado         = $evento;

                        $estado->getSuma();

                        $total = ($estado->suma + 1);

                        $estado->suma = $total;

                        $estado->updateSuma();

                    break;

                default:
                    # Guardamos el estado del mail
                        $estado = new Estado();
                        $estado->destinatario   = $obj['email'];
                        $estado->id_envio       = $obj['id_envio'];
                        $estado->estado         = $obj['event'];
                        $estado->updateEstado();
                        
                break;
            }
        }

        $arch = fopen($error_log, 'a');

        fWrite($arch, "\n -->"  . $obj['event'] );
        fWrite($arch, "\n -->"  . $obj['email'] );
        fWrite($arch, "\n -->"  . $obj['id_envio'] );
    }

    fwrite($arch,"\nEjecucion terminada\n\n\n");
    fclose($arch);

?>
