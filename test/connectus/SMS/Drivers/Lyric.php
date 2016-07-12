<?php
    
    require_once __DIR__.'/../SMSAbstract.php';
    require_once __DIR__.'/../Models/Conector.php';
    require_once __DIR__.'/../Models/DetalleEnvio.php';

    class Lyric extends SMSAbstract
    {


        public $conDB;
        public $servidor    =   '186.103.231.210';
        public $api_version =   '0.08';
        public $username    =   'ConnectusApi';
        public $password    =   'Connectus.2016.api;';
        public $usersrv     =   'admin';
        public $passsrv     =   'Connectus.2016;';
        public $channel     =   array(2);




        public function sendSms($numero, $mensaje, $remitente, $connectusKey, $momento_envio, $id_empresa = '', $titulo , $is_predefinido, $nombre_mensaje_predefinido, $id_envio_programado, $id_consumo)
        {

            $id_envio = 0;

            try{
                
                    if ($momento_envio == 'ahora') {

                        $id_mensaje = $this->addSms($mensaje, $remitente, $is_predefinido, $id_empresa, $titulo, $nombre_mensaje_predefinido);
                        $id_envio = $this->addEnvio('en proceso' ,$remitente, $id_mensaje, 'unico','SMS', $titulo ,$id_empresa, null, $id_consumo);            

                    }elseif ($momento_envio == 'programado')
                    {
                        $id_envio = $this->updateEnvio($id_envio_programado);
                    }

                    //Se envían mensajes y guardan en tabla detalle_envío
                    $numeroMensajesEnviados = $this->api_queue_sms($numero, $mensaje, $id_envio);

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
        

        public function sendMassSms($mensaje, $connectusKey, $id_empresa, $momento_envio, $is_predefinido, $result = array(), $titulo = '', $id_lista, $nombre_mensaje_predefinido, $remitente,  $id_envio_programado)
        {
        
            $id_envio = 0;
            set_time_limit(20000); //para que se pueda ejecutar por un buen tiempo, hasta 5 H app

            try {
                
                if ($momento_envio == 'ahora') {

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
                    }            


                    $contactos[$i]['mensaje'] = $mensaje_formateado;

                }
                
                  
                
                ////FORMA NO ASYNC ANTERIOR
                foreach ($contactos as $contacto) { 
                    //Se envían mensajes y guardan en tabla detalle_envío
                    $this->api_queue_sms($contacto['celular'], $contacto['mensaje'], $id_envio);                    
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

        public function sendSmsRest($numero, $mensaje, $id_empresa)
        {
            $id_envio = 0;

            try {

                $numero = $this->checkAndParseCellphoneNumber($numero);

                $equivalentes = $this->getEquivalencia('equivalencia_sms');

                $valor1 = $equivalentes['valor1'];
                $valor2 = $equivalentes['valor2'];

                $consumo = 1 / $valor2;

                $car = strlen($mensaje);
                if($car > $this->largo_max_mensaje){
                    $consumo = ceil($car / $this->largo_max_mensaje);
                }


                if ($this->checkCreditosDisponibles($id_empresa,$consumo)) {

                    $id_consumo = $this->insertConsumo($id_empresa , 0, $consumo, $consumo);

                    $id_mensaje = $this->addSms($mensaje, '', 0, $id_empresa, 'API');   

                    $id_envio = $this->addEnvio('en proceso' , '', $id_mensaje, 'API','SMS', 'Enviado desde API' ,$id_empresa, null, $id_consumo);         
                    
                    $numeroMensajesEnviados = $this->api_queue_sms($numero, $mensaje, $id_envio,'si');

                    

                    //$this->write_log('Mensaje REST ENVIADO: Numero-> '.$numero.' , id_envio-> '.$id_envio . ' , id_teleco-> '.$numeroMensajesEnviados[0]);

                    if (count($numeroMensajesEnviados) == 1) {
                        return $numeroMensajesEnviados[0];
                    } else if (count($numeroMensajesEnviados) > 1) {
                        return $numeroMensajesEnviados;
                    } else {
                        return '004';
                    }
                    

                    return ($numeroMensajesEnviados[0]);
                }else{
                    return '044';
                }

            } catch(Exception $e){
                $this->write_log('ERROR Envío SMS REST - datos: Numero-> '.$numero .' , id_envio->'.$id_envio.'-'.$id_envio_programado.' , mensaje-> '
                    .$mensaje .' , id_empresa-> '
                    .$id_empresa .', Error:'.$e->getMessage().' - '.$e->getFile().' - line: '.$e->getLine()
                    .' - Trace:'.$e->getTraceAsString());

                if ($id_envio != 0) {
                    $this->updateEnvioEstado($id_envio, 'Error');
                } else if ($id_envio_programado != 0){
                    $this->updateEnvioEstado($id_envio_programado, 'Error');
                } 

                return '004';

            }
        }



        public function api_get_version()
        {
            $url  = "http://".$this->usersrv.":".$this->passsrv."@".$this->servidor."/cgi-bin/exec?cmd=api_get_version";
            $result = file_get_contents($url);
            return $result;
        }


        public function api_queue_sms($number, $message, $id_envio, $rest = '')
        {

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

            
            $channel    = $this->get_channel(); # obtenemos un array con los canales disponibles
            $nch        = count($channel);      # numero de canales disponibles
            $ich        = 0;                    # index channel

            foreach($splitMessage as &$readyMessage){


                $data = array(
                    "cmd"           => "api_queue_sms",
                    "username"      => $this->username,
                    "password"      => $this->password,
                    "content"       => utf8_encode($readyMessage),
                    "destination"   => $number,
                    "channel"       => $channel[$ich],
                    "api_version"   => $this->api_version
                );


                if ($compania != 'SIN EMPRESA') {

                    //Manda porque está dentro de la tabla de portabilidad

                    $url  = "http://".$this->usersrv.":".$this->passsrv."@".$this->servidor."/cgi-bin/exec?".http_build_query($data);

                    $json = file_get_contents($url);

                    $arrayName = json_decode($json);// devuelve un objeto

                    //DEBUG
                    $this->addDetalleEnvio($arrayName->message_id, $id_envio, $number, $compania, $channel[$ich]);

                    $id_mensaje[] = $arrayName->message_id;

                } else {
                    //No envía porque no está en la tabla de portabilidad

                    //DEBUG
                    $this->addDetalleEnvio('0', $id_envio, $number, $compania, $channel[$ich]);
                }

                $sendMessages++;

                $ich ++;

                if($ich >= $nch){
                    $ich = 0;
                }
            }

            if (empty($rest)) {
                return $sendMessages;
            } else {
                return $id_mensaje;
            }



        }



        public function get_status_from_server($id_detalle_envio)
        {

            if(empty($this->servidor)){
                $conector = new Conector();
                $conector->id = $conector;
                $this->servidor = $conector->ip_servidor;
            }                
            
            $detalle = new DetalleEnvio();
            $detalle->id = $id_detalle_envio;
            
            if(! $detalle->get()){
                $this->error = "Error al obtener detalles del envío";
                return false;
            }


            $data = array(
                "cmd"           => "api_get_status",
                "username"      => $this->username,
                "password"      => $this->password,
                "message_id"    => $detalle->id_respuesta,
                "channel"       => $detalle->channel,
                "api_version"   => $this->api_version
            );

            $url  = "http://".$this->usersrv.":".$this->passsrv."@".$this->servidor."/cgi-bin/exec?".http_build_query($data);

            $json = file_get_contents($url);
            $result = json_decode($json);// devuelve un objeto

            if($result->success)
            {
                /**
                 *  Return
                 *  
                 *  0 new
                 *  1 Processing
                 *  2 Sent
                 *  3 Failure
                 *  
                 */

                switch ($result->message_status) {
                    case 0: $status = 'en proceso'; break;
                    case 1: $status = 'en proceso'; break;
                    case 2: $status = 'DELIVERED'; break;
                    case 3: $status = 'UNKNOWN'; break;
                }
            
            } else {
                $this->error = $result->error_code;
                return false;
            }

            return $status;
        }

        public function get_channel()
        {
            return $this->channel[0];
        }
        
    }