<?php
    
    require_once __DIR__.'/../MAILAbstract.php';


    class SendgridDriver extends MAILAbstract{

        public $sendgrid;



        function __construct($key = null) {

            require_once __DIR__.'/../../libs/sendgrid/sendgrid-php.php';
            
            # Ssilvac key
            #$this->sendgrid = new SendGrid('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');
            
            # Connectus key

            if(is_null($key)){
                $this->sendgrid = new SendGrid('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'); 
            }else{
                $this->sendgrid = new SendGrid($key);
            }

            

        }

    /*
    * Enviar nuevo Mail
    * recibe 11 parametros
    *
    * @return retorna response de mailgun con parametros -> id y message
    */
    public function sendMail($nombre_remitente, $email_remitente, $destinatario, $asunto, $message, $connectusKey, $id_empresa, $momento_envio,$is_predefinido , $id_envio_programado, $nombre_predefinido, $nombre_envio, $id_consumo){                 


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

                /*if ($momento_envio == 'reenviar') {

                    $this->updateDetalleEnvio($id_envio, $id_envio_mandrill);

                    $this->updateFechaEnvio($id_envio);

                }else{

                    $id_detalle_envio = $this->addDetalleEnvio('', $id_envio, $destinatario, '', 'en proceso');                                           

                }*/
                
                $id_detalle_envio = $this->addDetalleEnvio('', $id_envio, $destinatario, '', 'en proceso');


                $SGemail = new SendGrid\Email();
    
                $SGemail
                    ->addTo($destinatario)
                    ->setFrom($email_remitente)
                    ->setFromName($nombre_remitente)
                    ->setSubject($asunto)
                    ->setHtml($mensaje)
                    ->addCategory($id_empresa."-".$id_envio)
                    ->addUniqueArg("id_envio", $id_envio)
                    ->addUniqueArg("id_detalle_envio", $id_detalle_envio)
                    ->addUniqueArg("id_empresa", $id_empresa);

                
                $result = $this->sendgrid->send($SGemail);

                /*$codeResponse = $result->getCode();

                if($codeResponse == 200){
                    $this->updateDetalleEstado($id_detalle_envio, 'send');
                }*/

                return $result;

            } catch(\SendGrid\Exception $e) {
                
                $this->write_log('Error en correo unico con los siguientes datos: A Sendgrid error occurred: ' . get_class($e) . ' - ' . $e->getMessage());                                         
            
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

                $id_detalle_envio = $this->addDetalleEnvio('', $id_envio, $destinatario, '', 'en proceso');


                $SGemail = new SendGrid\Email();
    
                $SGemail
                    ->addTo($destinatario)
                    ->setFrom($email_remitente)
                    ->setFromName($nombre_remitente)
                    ->setSubject($asunto)
                    ->setHtml($message)
                    ->addCategory($id_empresa."-".$id_envio)
                    ->addUniqueArg("id_envio", $id_envio)
                    ->addUniqueArg("id_detalle_envio", $id_detalle_envio)
                    ->addUniqueArg("id_empresa", $id_empresa);

                
                $result = $this->sendgrid->send($SGemail);

                
            } catch(\SendGrid\Exception $e) {
                
                $this->deleteConsumo($id_consumo);

                $this->write_log('Error en correo REST con los siguientes datos: A sendgrid error occurred: ' . get_class($e) . ' - ' . $e->getMessage());                                          
                return '004';
            
            }   

            return $id_detalle_envio;
            
        }else{
            return '044';
        }
    }

    public function sendMassMail($nombre_remitente, $email_remitente,          $result = array(),         $asunto,          $message, $connectusKey,         $id_lista,           $is_predefinido, $titulo, $id_empresa,          $momento_envio, $id_envio_programado = '', $nombre_predefinido = null, $nombre_envio = null ){
    

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

                $this->updateEnvioEstado($id_envio ,'terminado');
            }

            $detalles_insertados = 0;
            
            $i = 0;
            foreach ($contactos as &$valores) {
                
                if(preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $valores['email'])){

                    $detalles_insertados++;

                    foreach ($result['nombre_columnas'] as $key ) {
                        $recipentsArray["%".$key."%"][$i] = $valores[strtolower($key)];
                        $recipentsArray["%".$key."%"][$i] = $valores[strtolower($key)];
                    }
                    

                    $emailsArray[$i] = $valores['email'];

                    #agregamos el detalle
                    $this->addDetalleEnvio('', $id_envio, $valores['email'], $contactos[$i]['id_contacto'], 'en proceso');

                    $i++;
                }else{

                    $id_envio_mailgun = md5($valores['id_contacto']);           
                    $this->addDetalleEnvio($id_envio_mailgun, $id_envio, $valores['email'],$contactos[$i]['id_contacto'],'malo');
                }

            }          

            //Se deja directo en envío
            //$this->insertConsumo($id_empresa , $consumo, 0 , $consumo);       
                                   
           
            $arraySearch = array();
            $arrayReplace = array();

            /*foreach ($result['nombre_columnas'] as $key ) {
                array_push($arraySearch, "%".strtolower($key)."%");
                array_push($arrayReplace, "*|".strtolower($key)."|*");
            }*/
            

            $mensaje_formateado = str_ireplace($arraySearch,$arrayReplace,$mensaje);

            $mensaje_formateado = str_ireplace("@id_lista", $id_lista, $mensaje_formateado);

            #$mensaje_formateado = str_ireplace("%recipient.email%", "*|email|*", $mensaje_formateado);
            #$mensaje_formateado = str_ireplace("%recipient.id_contacto%", "*|id_contacto|*", $mensaje_formateado);

            $mensaje_formateado = str_ireplace("%recipient.email%", "%Email%", $mensaje_formateado);
            $mensaje_formateado = str_ireplace("%recipient.id_contacto%", "%id_contacto%", $mensaje_formateado);
            

            $mensaje_formateado = str_ireplace("@id_envio", $id_envio, $mensaje_formateado);
            //colocar aca el id usuario para saber quien envio







            # AQUÍ EMPIEZA ALGORITMO PARA SEPARAR ENVÍOS


            $contador       = 0;
            $indice_global  = 0;
            $indice_envio   = 0;
            $envio_cada     = 1000;# cada n envíos
            $argenvio    = array();

            $parar = count($emailsArray); # parar al recorrer todos los contactos


            while ($contador < $envio_cada) {
                #echo "cnt: ".$contador." envío:".$indice_envio." indice global:".$indice_global."<br>";

                $argenvio[$indice_envio]['emails'][] = $emailsArray[$indice_global];

                foreach ($recipentsArray as $nombre_campo => $valor) {
                    $argenvio[$indice_envio]['recipientes'][$nombre_campo][] = $recipentsArray[$nombre_campo][$indice_global];
                }

                $contador ++;
                $indice_global++;                

                if($contador == $envio_cada){
                    $contador = 0;
                    $indice_envio++;
                }

                if($indice_global == $parar) break;

            }


            foreach ($argenvio as $env) {

                try{   

                    $SGemail = new SendGrid\Email();
        
                    $SGemail
                        ->setSmtpapiTos($env['emails'])
                        ->setFrom($email_remitente)
                        ->setFromName($nombre_remitente)
                        ->setSubject($asunto)
                        ->setHtml($mensaje_formateado)
                        ->setSubstitutions($env['recipientes'])
                        ->addCategory($id_empresa."-".$id_envio)
                        ->addUniqueArg("id_envio", $id_envio)
                        ->addUniqueArg("id_empresa", $id_empresa);


                    $result = $this->sendgrid->send($SGemail);

                } catch(\SendGrid\Exception $e) {
                    // Mandrill errors are thrown as exceptions
                    //echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
                    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
                    $this->write_log('Error en correo MASIVO con los siguientes datos: A Sendgrid error occurred: ' . get_class($e) . ' - ' . $e->getMessage());                                        
                    throw $e;
                }

            }




            # AQUÍ TERMINA ALGORITMO PARA SEPARAR ENVÍOS


            

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