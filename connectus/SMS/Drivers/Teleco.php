<?php

    require_once __DIR__.'/../SMSAbstract.php';
    require_once __DIR__.'/../Models/DetalleEnvio.php';


class Teleco extends SMSAbstract
{
    private $clientServerSms;
    private $clientIdSms;
    private $clientPassSms;
    private $clientAni;

    function __construct() {
        
        # Librería necesario para la ejecución del protocolo xmlrpc
        require_once __DIR__."/../../wsteleco/lib/xmlrpc.inc";

        # WebService Teleco
        $this->clientServerSms = new xmlrpc_client("http://smpp2.telecochile.cl:4040");
        $this->clientServerSms->return_type = 'phpvals';
        
        # Datos autoentificación 
        $this->clientIdSms = 'telecotesting';
        $this->clientPassSms = 'matias2015';
        $this->clientAni = '56442147839';

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
    public function sendSms($numero, $mensaje, $remitente, $connectusKey, $momento_envio, $id_empresa = '', $titulo , $is_predefinido,$nombre_mensaje_predefinido ,$id_envio_programado, $id_consumo){

        $id_envio = 0;

        try{
            
                if ($momento_envio == 'ahora') {

                    $id_mensaje = $this->addSms($mensaje, $remitente, $is_predefinido, $id_empresa, $titulo, $nombre_mensaje_predefinido);

                    $id_envio = $this->addEnvio('en proceso' ,$remitente, $id_mensaje, 'unico','SMS', $titulo ,$id_empresa, null, $id_consumo);            

                }elseif ($momento_envio == 'programado'){

                    $id_envio = $this->updateEnvio($id_envio_programado);

                }       

                //Se envían mensajes y guardan en tabla detalle_envío
                $numeroMensajesEnviados = $this->sendSMStoTeleco($numero, $mensaje, $id_envio, null, null);

                if ($numeroMensajesEnviados > 1) {
                    return 'Se han enviado '.$numeroMensajesEnviados.' mensajes exitosamente';
                } else if ($numeroMensajesEnviados == 1) {
                    return 'El mensaje fue enviado exitosamente';
                } else {
                    return 'Problemas con el servidor. Inténtelo más tarde.';
                }
            
        } catch(Exception $e){
            $this->write_log('ERROR Envío SMS Único - datos: Numero-> '.$numero .' , id_envio->'.$id_envio.'-'.$id_envio_programado.' , mensajeISO-> '
                .$mensaje .' , remitente-> '.$remitente .' , momento_envio-> '.$momento_envio .' , id_empresa-> '
                .$id_empresa .' , titulo-> '.$titulo .', Error:'.$e->getMessage().' - '.$e->getFile().' - line: '.$e->getLine()
                .' - Trace:'.$e->getTraceAsString());

            if ($id_envio != 0) {
                $this->updateEnvioEstado($id_envio, 'Error');
            } else if ($id_envio_programado != 0){
                $this->updateEnvioEstado($id_envio_programado, 'Error');
            } 

            return 'Problemas con el servidor. Inténtelo más tarde.';
        }
        
    }

    public function sendSmsRest($numero, $mensaje, $id_empresa){    
        
        $id_envio = 0;

        try {

            $numero = $this->checkAndParseCellphoneNumber($numero);

            $equivalentes = $this->getEquivalencia('equivalencia_sms');

            $valor1 = $equivalentes['valor1'];
            $valor2 = $equivalentes['valor2'];

            $consumo = 1 / $valor2;

            if ($this->checkCreditosDisponibles($id_empresa,$consumo)) {

                $id_consumo = $this->insertConsumo($id_empresa , 0, $consumo, $consumo);

                $id_mensaje = $this->addSms($mensaje, '', 0, $id_empresa, 'API', null);   

                $id_envio = $this->addEnvio('en proceso' , '', $id_mensaje, 'API','SMS', 'Enviado desde API' ,$id_empresa, null, $id_consumo);         
                
                $numeroMensajesEnviados = $this->sendSMStoTeleco($numero, $mensaje, $id_envio,'si');

                

                //$this->write_log('Mensaje REST ENVIADO: Numero-> '.$numero.' , id_envio-> '.$id_envio . ' , id_teleco-> '.$numeroMensajesEnviados[0]);

                if (count($numeroMensajesEnviados) == 1)
                {
                    return $numeroMensajesEnviados;

                } else if (count($numeroMensajesEnviados) > 1)
                {
                    return $numeroMensajesEnviados;
                } else {
                    return '004';
                }
                

                return ($numeroMensajesEnviados[0]);
            }else{
                return '044';
            }

        } catch(Exception $e){
            $this->write_log('ERROR Envío SMS REST - datos: Numero-> '.$numero .' , id_envio->'.$id_envio.' , mensaje-> '
                .$mensaje .' , id_empresa-> '
                .$id_empresa .', Error:'.$e->getMessage().' - '.$e->getFile().' - line: '.$e->getLine()
                .' - Trace:'.$e->getTraceAsString());

            if ($id_envio != 0) {
                $this->updateEnvioEstado($id_envio, 'Error');
            }

            return '004';

        }
        
        
    }

    
    public function sendMassSms($mensaje, $connectusKey, $id_empresa, $momento_envio, $is_predefinido, $result = array(), $titulo = '', $id_lista, $nombre_mensaje_predefinido, $remitente,  $id_envio_programado)
    {
        $id_envio = 0;
        set_time_limit(20000); //para que se pueda ejecutar por un buen tiempo, hasta 5 H app

        try {
            
            if ($momento_envio == 'ahora')
            {

                $id_mensaje = $this->addSms($mensaje, $remitente, $is_predefinido, $id_empresa, $titulo,$nombre_mensaje_predefinido);                                       

                $id_envio = $this->addEnvio('en proceso' ,$remitente, $id_mensaje, 'masivo','SMS', $titulo, $id_empresa, $id_lista);

            }elseif ($momento_envio == 'programado') {

                $id_envio = $this->updateEnvio($id_envio_programado);

            }

            
            $arraySearch = array();
            for ($i=0; $i < sizeof($result['nombre_columnas']) - 1; $i++) { 
                array_push($arraySearch, "%".$result['nombre_columnas'][$i]."%");                    
            }   

            $contactos = array();
            for ($i=0; $i < sizeof($result['valores']); $i++) {                                    
                $mensaje_formateado = $mensaje;
                for ($j=0; $j < sizeof($result['valores'][$i]); $j++) {                     

                    //$contactos[$i][strtolower($result['nombre_columnas'][$j])] = $result['valores'][$i][$result['campos_de_contacto'][$j]];
                    $contactos[$i][strtolower($result['campos_de_contacto'][$j])] = $result['valores'][$i][$result['campos_de_contacto'][$j]];

                    if ( $j < sizeof($arraySearch)) {
                        $mensaje_formateado = str_ireplace($arraySearch[$j], $result['valores'][$i][$result['campos_de_contacto'][$j]] ,$mensaje_formateado);
                    }    

                    //guardamos el id de contacto
                    $contactos[$i]['contacto'] = $result['valores'][$i]['id_contacto'];                     
                }            


                $contactos[$i]['mensaje'] = $mensaje_formateado;

            }
            
            
              
            
            ////FORMA NO ASYNC ANTERIOR
            foreach ($contactos as $contacto) { 
                //Se envían mensajes y guardan en tabla detalle_envío
                $this->sendSMStoTeleco($contacto['celular'], $contacto['mensaje'], $id_envio, null, $contacto['contacto']);

                //$this->write_log('ENVIO SMS Contacto: '.$contacto['contacto']);
                
            }
            
            

        } catch (Exception $e) {
            
            $this->write_logCrone('ERROR Envío SMS MASIVO - datos: id_envio->'.$id_envio.'-'.$id_envio_programado.' , remitente-> '.$remitente .' , momento_envio-> '.$momento_envio .' , id_empresa-> '
            .$id_empresa .' , titulo-> '.$titulo .', Error:'.$e->getMessage().' - '.$e->getFile().' - line: '.$e->getLine()
            .' - Trace:'.$e->getTraceAsString());

            if ($id_envio != 0) {
                $this->updateEnvioEstado($id_envio, 'Error');
            } else if ($id_envio_programado != 0){
                $this->updateEnvioEstado($id_envio_programado, 'Error');
            } 

        }
            
    }





    public function get_status_from_server($id_detalle_envio)
    {
        $detalle = new DetalleEnvio();
        $detalle->id = $id_detalle_envio;
        
        if(! $detalle->get()){
            $this->error = "Error al obtener detalles del envío";
            return false;
        }   

        try {

            $params = new xmlrpcmsg("enquireMsgStatus", array(
                                new xmlrpcval($this->clientIdSms),
                                new xmlrpcval($this->clientPassSms),
                                new xmlrpcval($detalle->id_respuesta)
                            ));
            
            $result = $this->clientServerSms->send($params)->val;

            if($result['MESSAGE'] == 'Success'){
                return $result['STATUS'];
            
            } else{
                return false;
            }

        } catch (Exception $e) {
            $this->write_logCrone('ERROR Teleco::get_status_from_server - Error:'.$e->getMessage().' - '.$e->getFile().' - line: '.$e->getLine().' - Trace:'.$e->getTraceAsString());

            return false;
        }
    }
    

    public function getMensajesRecibidosById(){     
        //if($this->checkConnectusKey($connectusKey)){  

        $count = 10;
            
        /*$params = array(
        "clientid" => $this->clientIdSms,
        "clientpassword" => $this->clientPassSms,
        "count" => 10
        );*/

        $params = new xmlrpcmsg("getReceivedMessages",
            array(
                new xmlrpcval($this->clientIdSms),
                new xmlrpcval($this->clientPassSms),
                new xmlrpcval($count)
            ));

        
        #$result = $this->clientServerSms->__soapCall("getReceivedMessages", array($params));
        $result = $this->clientServerSms->send($params)->val;



            //$arrayName = json_decode(json_encode($result), true);                          

            //return $arrayName['getReceivedMessagesResult']['messages'];
            

            return $result;

        /*}else{
            $response["Mensaje"] = "Tu api key no es valida porfavor registate en connectus o contactanos al siguiente correo: info@connectus.com";
            return $response;                                                   
        }*/ 
    }

    


    public function sendSMStoTeleco($number, $message, $id_envio, $rest = '', $id_contacto = null){

        //Se setea formato
        $number = $this->checkAndParseCellphoneNumber($number);

        //Se limpia y sacan acentos al mensaje
        $message = $this->messageClean($message); 

        //Revisa compañía en tabla de portabilidad
        $compania = $this->check_compania($number);

        $unsplitMessage = wordwrap($message,$this->largo_max_mensaje,'|',true);
        $splitMessage = explode('|',$unsplitMessage);

        $sendMessages = 0;

        $id_mensaje;

        foreach($splitMessage as &$readyMessage){

            /*$params = array(
                "clientid" => $this->clientIdSms,
                "clientpassword" => $this->clientPassSms,
                "ani" =>  $this->clientAni,
                "dnis" => $number,
                "message" => utf8_encode($readyMessage)
                );*/
            
            $params = new xmlrpcmsg("submitMsg",
                array(
                    new xmlrpcval($this->clientIdSms),
                    new xmlrpcval($this->clientPassSms),
                    new xmlrpcval($this->clientAni),
                    new xmlrpcval($number),  
                    new xmlrpcval(utf8_encode($readyMessage))
                ));

            //DEBUG
            //$this->fakeSendCron($params["message"]);

            if ($compania != 'SIN EMPRESA') {

                //Manda porque está dentro de la tabla de portabilidad

                #$result = $this->clientServerSms->__soapCall('submitMsg' ,array($params));
                $result = $this->clientServerSms->send($params)->val;

                $id_detalleEnvio = $this->addDetalleEnvio($result['ID'], $id_envio, $number, $compania, null, $id_contacto);

                #$id_mensaje[] = $arrayName['submitMsgResult']['id'];
                $id_mensaje[] = $result['ID'];

            } else {
                //No envía porque no está en la tabla de portabilidad

                $id_detalleEnvio = $this->addDetalleEnvio('0', $id_envio, $number, $compania, null, $id_contacto);
            }

            $sendMessages++;
        }

        if (empty($rest)) {
            return $sendMessages;
        } else {
            # se retornaba el id devuelto pot teleco, ahora retornaremos el ID del mensaje (de la tabla detalleEnvio)
            #return $id_mensaje;
            return $id_detalleEnvio;
        }

        

    }


    public function sendSMStoTelecoASYNC($number, $message, $id_envio, $rest = ''){

        //Se setea formato
        $number = $this->checkAndParseCellphoneNumber($number);

        //Se limpia y sacan acentos al mensaje
        $message = $this->messageClean($message); 

        //Revisa compañía en tabla de portabilidad
        $compania = $this->check_compania($number);

        $unsplitMessage = wordwrap($message,$this->largo_max_mensaje,'|',true);
        $splitMessage = explode('|',$unsplitMessage);

        $sendMessages = 0;

        $id_mensaje;

        foreach($splitMessage as &$readyMessage){

            /*$params = array(
                "clientid" => $this->clientIdSms,
                "clientpassword" => $this->clientPassSms,
                "ani" =>  $this->clientAni,
                "dnis" => $number,
                "message" => utf8_encode($readyMessage)
                );*/

            $params = new xmlrpcmsg("submitMsg",
                array(
                    new xmlrpcval($this->clientIdSms),
                    new xmlrpcval($this->clientPassSms),
                    new xmlrpcval($this->clientAni),
                    new xmlrpcval($number),  
                    new xmlrpcval(utf8_encode($readyMessage))
                ));


            //DEBUG
            //$this->fakeSendCron($params["message"]);

            if ($compania != 'SIN EMPRESA') {

                //Manda porque está dentro de la tabla de portabilidad

                $result = $this->clientServerSms->send($params);

                //DEBUG
                $arrayName = json_decode(json_encode($result), true);  

                //DEBUG
                //$this->addDetalleEnvio('-1', $id_envio, $telefono);
                $this->addDetalleEnvio($arrayName['submitMsgResult']['id'], $id_envio, $number, $compania, null);

                $id_mensaje[] = $arrayName['submitMsgResult']['id'];

            } else {
                //No envía porque no está en la tabla de portabilidad

                //DEBUG
                //$this->addDetalleEnvio('-1', $id_envio, $telefono);
                $this->addDetalleEnvio('0', $id_envio, $number, $compania, null);
            }

            $sendMessages++;
        }

        if (empty($rest)) {
            return $sendMessages;
        } else {
            return $id_mensaje;
        }

        

    }



}
