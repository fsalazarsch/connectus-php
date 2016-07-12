<?php
    
    require_once __DIR__.'/../SMSAbstract.php';

    class Celcom extends SMSAbstract
    {
        private $url        = 'http://smsapi.celcom.cl/recibe?';
        private $service_id = 'Smsapi_Test_5501';
        private $numero     = '';
        private $num_corto  = 5501;
        private $clave      = 'test.2016.api';
        private $texto      = 'texto';
        private $tasacion   = 0;


        public function sendSms($numero, $mensaje, $remitente, $connectusKey, $momento_envio, $id_empresa = '', $titulo , $is_predefinido, $nombre_mensaje_predefinido, $id_envio_programado = '')
        {

            $id_envio = 0;

            try
            {                
                if ($momento_envio == 'ahora')
                {

                    if($id_mensaje = $this->addSms($mensaje, $remitente, $is_predefinido, $id_empresa, $titulo, $nombre_mensaje_predefinido))
                    {
                        $id_envio = $this->addEnvio('en proceso' ,$remitente, $id_mensaje, 'unico','SMS', $titulo ,$id_empresa);            

                    } else {
                        return false;
                    }
                    

                }elseif ($momento_envio == 'programado')
                {
                    $id_envio = $this->updateEnvio($id_envio_programado);
                }

                //Se envían mensajes y guardan en tabla detalle_envío
                $numeroMensajesEnviados = $this->send_api($numero, $mensaje, $id_envio, null);

                if ($numeroMensajesEnviados > 1){
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

        public function sendSmsRest($numero, $mensaje, $id_empresa)
        {

        }

        public function sendMassSms($mensaje, $connectusKey, $id_empresa, $momento_envio, $is_predefinido, $result = array(), $titulo = '', $id_lista, $nombre_mensaje_predefinido, $remitente,  $id_envio_programado)
        {

        }




        public function send_api($number, $message, $id_envio, $rest)
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

            

            foreach($splitMessage as &$readyMessage){


                $data = array(
                    "service_id"    => $this->service_id,
                    "nro_movil"     => $number,
                    "nro_corto"     => $this->num_corto,
                    "clave"         => $this->clave,
                    "texto"         => utf8_encode($readyMessage),
                    "tasacion"      => $this->tasacion
                );


                if ($compania != 'SIN EMPRESA') {

                    //Manda porque está dentro de la tabla de portabilidad

                    $url        = $this->url.http_build_query($data);
                    
                    $result = file_get_contents($url);

                    $pos = strpos($result, '000');

                    if($pos === false)
                    {
                        $this->write_log('Error - SMS::Celcom::send_api() :'.implode(":",$stmt->errorInfo()));
                        return false;
                    }

                    $r = array();

                    foreach ($http_response_header as $val) {
                        $s = explode(': ', $val);
                        $r[$s[0]] = $s[1];
                    }

                    $restId = $r['X-SMSGW-Msg-Id'];


                    //DEBUG
                    $this->addDetalleEnvio($restId, $id_envio, $number, $compania, null);

                    $id_mensaje[] = $restId;

                } else {
                    //No envía porque no está en la tabla de portabilidad

                    //DEBUG
                    $this->addDetalleEnvio('0', $id_envio, $number, $compania, $channel[$ich]);
                }

                $sendMessages++;
            }

            if (empty($rest)) {
                return $sendMessages;
            } else {
                return $id_mensaje;
            }
        }


        public function get_status_from_server($id_detalle_envio)
        {
            /**
             *  Celcom se encarga de envíar la confirmación de envío
             *
             * 
             *  se envía la información corespondiente a esta dirección
             *  connectus.cl/connectus/celcom/inputCelcom.php
             */

            $this->error = "Celcom se encarga de enviar el estado del envío";
            return false;
        }

        public function getMensajesRecibidosById()
        {

        }
    }