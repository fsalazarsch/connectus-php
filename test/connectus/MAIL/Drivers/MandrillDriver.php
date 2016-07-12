<?php

    require_once __DIR__.'/../MAILAbstract.php';


    class MandrillDriver extends MAILAbstract{



        function __construct() {

            require __DIR__.'/../../libs/mandrill/Mandrill.php';

            $this->mandrill = new Mandrill('mAE8PAPvxN-qCzL7JucPuA');

        }

    /*
    * Enviar nuevo Mail
    * recibe 11 parametros
    *
    * @return retorna response de mailgun con parametros -> id y message
    */
    public function sendMail($nombre_remitente, $email_remitente, $destinatario, $asunto, $message, $connectusKey, $id_empresa, $momento_envio,$is_predefinido ,$id_envio_programado, $nombre_predefinido, $nombre_envio, $id_consumo){                 


        $response = array();
        //if($this->checkConnectusKey($connectusKey)){  
        $equivalentes = $this->getEquivalencia('equivalencia_mail');
        $mensaje = $this->center_image($message);

        $valor1 = $equivalentes['valor1'];
        $valor2 = $equivalentes['valor2'];
        $consumo = 1 / $valor2;

        $email[] = array('email' => $destinatario,
                                  'type' => 'to');


        // nos aseguramos de que $nombre_predefinido si tenga un valor
        if(empty($nombre_predefinido)){
            $nombre_predefinido = $asunto;
        }

        // nos aseguramos de que $nombre_predefinido si tenga un valor
        if(empty($nombre_envio)){
            $nombre_envio = $asunto;
        }

        //if ($this->checkCreditosDisponibles($id_empresa,$consumo)) {
            $id_envio = 0;
            if ($momento_envio == 'ahora') {

                #echo "ASUNTO:".$asunto."<br>NOMBRE PREDEF: ".$nombre_predefinido;

                $id_mensaje = $this->addMail($asunto, htmlspecialchars($mensaje, ENT_QUOTES) , $nombre_remitente, $email_remitente, $is_predefinido, $nombre_envio, $id_empresa);
                
                $id_envio = $this->addEnvio('en proceso' ,$nombre_remitente, $email_remitente,  '' ,      $id_mensaje,    'unico',      'MAIL',     $nombre_envio, $is_predefinido, $id_empresa, $asunto , $id_consumo);            

            }elseif ($momento_envio == 'programado') {

                $id_envio = $this->updateEnvioEstado($id_envio_programado, 'en proceso');
            }

            if ($momento_envio == 'reenviar') {

                $mensaje1 = html_entity_decode($mensaje, ENT_QUOTES, 'UTF-8');  
                
                $mensaje = $mensaje1;       

                $id_envio = $id_envio_programado;


            }

            //Se deja directo en envío
            //$this->insertConsumo($id_empresa , $consumo, 0 , $consumo);

            try{

                
                $params = array(
                    'html' => $mensaje,
                    'subject' => $asunto,
                    'from_email' => $email_remitente,
                    'from_name' => $nombre_remitente,
                    'to' =>  $to[] = $email,
                    'track_opens' => true,
                    'track_clicks' => true);
                             
                $result = $this->mandrill->messages->send($params);

                $arrayName = json_decode(json_encode($result), true);  

                for ($i=0; $i < sizeof($arrayName); $i++) { 

                    $id_envio_mandrill = $arrayName[$i]['_id'];
                    
                    if ($momento_envio == 'reenviar') {

                        $this->updateDetalleEnvio($id_envio, $id_envio_mandrill);
                        $this->updateFechaEnvio($id_envio);

                    }else{

                        $this->addDetalleEnvio($arrayName[$i]['_id'], $id_envio, $destinatario, '',$arrayName[$i]['status']);                                           

                    }

                }

                //$this->write_log('Correo unico enviado exitosamente con los siguientes datos: destinatario-> '.$destinatario.' , remitente-> '. $nombre_remitente . ' '. $email_remitente .' , id_envio-> '.$id_envio . ' , id_envio_mandrill-> '.json_encode($result));
                return $result;

            } catch(Mandrill_Error $e) {
                
                $this->write_log('Error en correo unico con los siguientes datos: A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage());                                         
            
            }
        //}else{
        //  return 'No posees los creditos suficientes para realizar el envio';
        //}
        
    }


    /*
    * Enviar nuevo Mail
    *
    * @return retorna response de mailgun con parametros -> id y message
    */
    public function sendMailRest($nombre_remitente, $email_remitente, $destinatario, $asunto, $message, $id_empresa, $nombre_predefinido = null, $nombre_envio = null ){                   
        $response = array();
        $mensaje = $this->center_image($message);
        
        $equivalentes = $this->getEquivalencia('equivalencia_mail');

        $valor1 = $equivalentes['valor1'];
        $valor2 = $equivalentes['valor2'];
        $consumo = 1 / $valor2;


        // nos aseguramos de que $nombre_predefinido si tenga un valor
        if(empty($nombre_predefinido)){
            $nombre_predefinido = $asunto;
        }

        // nos aseguramos de que $nombre_envio si tenga un valor
        if(empty($nombre_envio)){
            $nombre_envio = $asunto;
        }
        
        if ($this->checkCreditosDisponibles($id_empresa,$consumo))
        {

            $id_consumo = $this->insertConsumo($id_empresa , $consumo, 0 , $consumo);


            $id_mensaje = $this->addMail($asunto, htmlspecialchars($mensaje, ENT_QUOTES) , $nombre_remitente, $email_remitente, 0,     $nombre_envio, $id_empresa);

            $id_envio = $this->addEnvio('en proceso' ,$nombre_remitente,$email_remitente,'' ,$id_mensaje,'API','MAIL',$nombre_envio, 0, $id_empresa, $asunto, $id_consumo);         
            
            

            $remitente = $nombre_remitente.' <'.$email_remitente.'>';

            /*
            $message  = '<html dir="ltr" lang="en">' . "\n";
            $message .= '  <head>' . "\n";              
            $message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
            $message .= '  </head>' . "\n";
            $message .= '  <body>' . html_entity_decode($mensaje, ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
            $message .= '</html>' . "\n";
            */
            //##### CAMBIO TEMPORAL

            $message  = "<html dir='ltr' lang='es'>" . "\n";
            $message .= " <head>" . "\n";               
            $message .= "    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>" . "\n";
            $message .= "    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' type='text/css'>" . "\n";
            $message .= " </head>" . "\n";
            $message .= "  <body> <br>
                        <div align='center'>
                            <table border='0' cellpadding='0' cellspacing='0' height='39' id='table26' width='600'>
                                <tbody>
                                    <tr>
                                        <td valign='top'>

                        <div style='font-family:Verdana, Arial, Helvetica, sans-serif; text-align:center; font-size:10px; color:#999999;' valign='top'>
                        Para asegurar la entrega de nuestros e-mail en su correo, por favor agregue <span style='color:#0073ae'>".$email_remitente."</span> a su libreta de direcciones<br></div>"  . "\n";
                        //Si usted no visualiza bien este mail, haga <a href='". HTTPS_CATALOG ."viewmail/index2.php?id_envio=@id_envio' target='_blank'>click aqu&iacute;</a></div>"  . "\n";
            $message .=      html_entity_decode(($mensaje), ENT_QUOTES, 'UTF-8')  . "\n";           
            $message .= " <br> <div style='font-family:Verdana, Arial, Helvetica, sans-serif; text-align:center; font-size:10px; color:#999999;' valign='top'>Este correo electrónico fue enviado a ".$destinatario." </div>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        " . "\n"; 
            $message .= " </body>" . "\n"; 
            $message .= "</html>" . "\n";


            //#### FIN CAMBIO TEMPORAL

            try{      

                $email = array('email' => $destinatario,
                               'type' => 'to');

                $to[] = $email;
           
                $params = array(
                    'html' => $message,
                    'subject' => $asunto,
                    'from_email' => $email_remitente,
                    'from_name' => $nombre_remitente,
                    'to' => $to,
                    'track_opens' => true,
                    'track_clicks' => true);
                             
                $result = $this->mandrill->messages->send($params);
                //
                
                $arrayName = json_decode(json_encode($result), true); 

                for ($i=0; $i < sizeof($arrayName); $i++) { 
                    $this->addDetalleEnvio($arrayName[$i]['_id'], $id_envio, $destinatario, '',$arrayName[$i]['status']);
                    $id_envio_mandrill = $arrayName[$i]['_id'];
                    $result = $id_envio_mandrill;
                }

                //$this->write_log('Correo unico(REST) enviado exitosamente con los siguientes datos: destinatario-> '.$destinatario.' , remitente-> '. $remitente .' , id_envio-> '.$id_envio . ' , id_envio_mandrill-> '.$id_envio_mandrill);
                

            } catch(Mandrill_Error $e) {
                
                $this->deleteConsumo($id_consumo);

                $this->write_log('Error en correo REST con los siguientes datos: A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage());                                          
                return '004';
            
            }   

            
            
            return $result;
        }else{
            return '044';
        }
    }


    public function sendMassMail($nombre_remitente, $email_remitente, $result = array(), $asunto, $message, $connectusKey, $id_lista, $is_predefinido, $titulo, $id_empresa, $momento_envio, $id_envio_programado = '', $nombre_predefinido = null, $nombre_envio = null ){
    
        
        $contactos = array();
        $mensaje = $this->center_image($message);

        $emailsArray = array();
        $recipentsArray = array();      
        $remitente = $nombre_remitente.' <'.$email_remitente.'>';               


        // nos aseguramos de que $nombre_predefinido si tenga un valor
        if(empty($nombre_predefinido)){
            $nombre_predefinido = $asunto;
        }
        // nos aseguramos de que $nombre_envio si tenga un valor
        if(empty($nombre_envio)){
            $nombre_envio = $asunto;
        }

        //$this->write_log('LLEGA a sendMassMail!!!!!!!');                                          

        //Armo el arreglo de contactos con sus columnas respectivas 
        $indexMail = 0;
        for ($i=0; $i < sizeof($result['valores']); $i++) {
            for ($j=0; $j < sizeof($result['valores'][$i]); $j++) {
                
                if ($result['campos_de_contacto'][$j] == 'email') {
                    $contactos[$i][strtolower($result['campos_de_contacto'][$j])] = $result['valores'][$i][$result['campos_de_contacto'][$j]];
                    $indexMail = $j;
                }else{

                    if ($j == (sizeof($result['valores'][$i]) - 1) ) {
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

            $id_mensaje = $this->addMail($titulo,  htmlspecialchars($mensaje, ENT_QUOTES)  ,$nombre_remitente, $email_remitente, $is_predefinido, $nombre_envio, $id_empresa);

            $id_envio = $this->addEnvio('terminado' ,$nombre_remitente,$email_remitente,$id_lista,$id_mensaje,'masivo','MAIL',$nombre_envio, $correos_malos, $id_empresa, $asunto);
            

            }elseif ($momento_envio == 'programado') {

                $id_envio = $this->updateEnvioMasivo($id_envio_programado,$correos_malos);
            }

            $detalles_insertados = 0;
            
            foreach ($contactos as &$valores) {
                
                if(preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $valores['email'])){

                    $detalles_insertados++;
                    
                    $var = array();

                    foreach ($result['nombre_columnas'] as $key ) {
                        $var[] = array('name' => strtolower($key), 
                                       'content' => $valores[strtolower($key)]);
                    }
                    
                    $recipentsArray[] = array('rcpt' => $valores['email'],
                                              'vars' => $var);

                    $emailsArray[] = array('email' => $valores['email']);
                }else{

                    $id_envio_mailgun = md5($valores['id_contacto']);           
                    $this->addDetalleEnvio($id_envio_mailgun, $id_envio, $valores['email'],$contactos[$i]['id_contacto'],'malo');
                }

            }       

            //Se deja directo en envío
            //$this->insertConsumo($id_empresa , $consumo, 0 , $consumo);       
                                   
           
            $arraySearch = array();
            $arrayReplace = array();

            foreach ($result['nombre_columnas'] as $key ) {
                array_push($arraySearch, "%".strtolower($key)."%");
                array_push($arrayReplace, "*|".strtolower($key)."|*");
            }
            

            $mensaje_formateado = str_ireplace($arraySearch,$arrayReplace,$mensaje);

            $mensaje_formateado = str_ireplace("@id_lista", $id_lista, $mensaje_formateado);

            $mensaje_formateado = str_ireplace("%recipient.email%", "*|email|*", $mensaje_formateado);
            $mensaje_formateado = str_ireplace("%recipient.id_contacto%", "*|id_contacto|*", $mensaje_formateado);
            $mensaje_formateado = str_ireplace("@id_envio", $id_envio, $mensaje_formateado);
            //colocar aca el id usuario para saber quien envio

            try{      
           
                $params = array(
                    'html' => $mensaje_formateado,
                    'subject' => $asunto,
                    'from_email' => $email_remitente,
                    'from_name' => $nombre_remitente,
                    'to' => $emailsArray,
                    'track_opens' => true,
                    'track_clicks' => true,
                    'merge_vars' => $recipentsArray);
                
                //DEBUG
                //$this->fakeSendCron($params["from_email"].' '.$params["to"]);             
                $result = $this->mandrill->messages->send($params);

                //
                //DEBUG
                $arrayName = json_decode(json_encode($result), true); 

                for ($i=0; $i < sizeof($arrayName); $i++) { 
                    $this->addDetalleEnvio($arrayName[$i]['_id'], $id_envio, $contactos[$i]['email'], $contactos[$i]['id_contacto'],$arrayName[$i]['status']);
                }
                

                //DEBUG
                /*
                for ($i=0; $i < sizeof($recipentsArray); $i++) { 
                    //addDetalleEnvio($id_respuesta_servidor, $id_envio, $destinatario, $id_contacto = '', $estado = '' )
                    $this->addDetalleEnvio('0', $id_envio, $contactos[$i]['email'], $contactos[$i]['id_contacto'],'failed');
                }
                */

                

            } catch(Mandrill_Error $e) {
                // Mandrill errors are thrown as exceptions
                //echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
                // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
                $this->write_log('Error en correo MASIVO con los siguientes datos: A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage());                                        
                throw $e;
            }

        //}else{
        //  $this->write_log('SIN Créditos - id_empresa:'.$id_empresa);
        //  return 'Sin Creditos';
        //}
    }
  



    public function getSendMailById($messageid){    

        try{
            $result = $this->mandrill->messages->info($messageid);

            return $result;     
        } catch(Mandrill_Error $e) {
            
            $this->write_log('Error al rescatar el estado este id no existe consulte mas tarde: ' . get_class($e) . ' - ' . $e->getMessage());  
            return "Error el id ingresado aun no se ha procesado consulte mas tarde ";
        }
                                                
    }


    public function get_status_from_server($id_detalle)
    {
        # se obtiene la información del servidor de mandrill
        # mandrill trabaja con webhook, asi que hay que considerar no tener este mertodo !
        return true;
    }

    
    
}
?>