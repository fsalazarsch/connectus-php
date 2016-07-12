<?php

    require_once __DIR__."/Models/Consumo.php";
    require_once __DIR__."/Models/CuentaCorriente.php";
    require_once __DIR__."/Models/DetalleEnvio.php";
    require_once __DIR__."/Models/Envio.php";
    require_once __DIR__."/Models/Mensaje.php";
    require_once __DIR__."/Models/Parametro.php";
    require_once __DIR__."/Models/TablaNumeracion.php";
    
    abstract class SMSAbstract
    {
        public $conDB;
        public $conector;
        public $message;
        public $number;
        public $company;
        public $largo_max_mensaje = 160;

        /**
         *  envío de SMS
         */
        abstract public function sendSms($numero, $mensaje, $remitente, $connectusKey, $momento_envio, $id_empresa = '', $titulo , $is_predefinido, $nombre_mensaje_predefinido, $id_envio_programado, $id_consumo);
        
        /**
         *  envío de SMS vía API REST
         */
        abstract public function sendSmsRest($numero, $mensaje, $id_empresa);
        
        /**
         *  envío de SMS Masivo
         */
        abstract public function sendMassSms($mensaje, $connectusKey, $id_empresa, $momento_envio, $is_predefinido, $result = array(), $titulo = '', $id_lista, $nombre_mensaje_predefinido, $remitente,  $id_envio_programado);
        
        /**
         *  obtención del estado del SMS enviado
         *  y se guarda en la BD
         */
        abstract public function get_status_from_server($id_detalle_envio);

        /**
         *  obtención de los SMS recividos
         */











        public function addSms($cuerpo, $remitente, $guardar, $id_empresa, $titulo, $predefinido)
        {
            $mensaje = new Mensaje();

            $mensaje->cuerpo        = $cuerpo;
            $mensaje->guardar       = $guardar;
            $mensaje->remitente     = $remitente;
            $mensaje->id_empresa    = $id_empresa;
            $mensaje->titulo        = $titulo;
            $mensaje->predefinido   = $predefinido;

            if($mensaje->save()){
                return $mensaje->id;   
            }else{
                return false;
            }       
        }

        public function addEnvio($estado ,$remitente, $id_mensaje, $tipo_envio, $tipo_mensaje, $nombre_envio, $id_empresa = '', $id_lista = '', $id_consumo)
        {
            $envio = new Envio();

            $envio->estado          = $estado;
            $envio->remitente       = $remitente;
            $envio->id_mensaje      = $id_mensaje;
            $envio->tipo_envio      = $tipo_envio;
            $envio->tipo_mensaje    = $tipo_mensaje;
            $envio->nombre_envio    = $nombre_envio;
            $envio->id_lista        = $id_lista;
            $envio->conector        = $this->conector;
            $envio->id_empresa      = $id_empresa;
            $envio->id_consumo      = $id_consumo;

            $envio->save();

            return $envio->id;     
        }

        public function addDetalleEnvio($id_respuesta_servidor, $id_envio, $destinatario, $compania, $channel, $id_contacto)
        {
            $detalle = new DetalleEnvio();

            $detalle->id_respuesta  = $id_respuesta_servidor;
            $detalle->id_envio      = $id_envio;
            $detalle->id_contacto   = $id_contacto;
            $detalle->destinatario  = $destinatario;
            $detalle->compania      = $compania;
            $detalle->channel       = $channel;

            $detalle->save();

            return $detalle->id;            
        }

        public function insertConsumo($id_empresa , $consumo_mail, $consumo_sms, $valor)
        {
            $cuenta = new CuentaCorriente();
            $cuenta->empresa = $id_empresa;

            $cuenta->getByEmpresa();

            $consumo = new Consumo();
            $consumo->cuenta_corriente = $cuenta->id;
            $consumo->consumo_sms = $consumo_sms;
            $consumo->consumo_mail = $consumo_mail;
            $consumo->valor = $valor;

            $consumo->save();

            $this->actualizarCuenta($id_empresa,$consumo_sms);

            return $consumo->id;
        }

        public function actualizarCuenta($id_empresa, $consumo)
        {
            $cuenta = new CuentaCorriente();
            $cuenta->empresa = $id_empresa;
            $cuenta->getByEmpresa();

            #$nuevo_saldo_sms = $cuenta->saldo_sms;# ??
            $cuenta->consumidos_sms = ($cuenta->consumidos_sms + $consumo);
            $cuenta->edit();

            # prueba
            $cuenta->getByEmpresa();
        }

        public function getEquivalencia($id_parametro)
        {
            $parametro = new Parametro();
            $parametro->id = $id_parametro;
            $result = $parametro->get();
            return $result;
        }

        public function updateEnvio($id_envio)
        {
            $this->updateEnvioEstado($id_envio, 'en proceso');
            return $id_envio;
        }

        public function updateEnvioEstado($id_envio, $estado_envio)
        {
            $envio = new Envio();
            $envio->id = $id_envio;
            $envio->get();

            $envio->estado = $estado_envio;
            $envio->edit();
        }


        public function checkAndParseCellphoneNumber($number)
        {
            $formatNumber = $number;
            $formatNumber = str_ireplace(' ', '', $formatNumber); //Se sacan espacios
            $formatNumber = str_ireplace('+', '', $formatNumber); //Se saca +
            $formatNumber = str_ireplace('(', '', $formatNumber); //Se saca (
            $formatNumber = str_ireplace(')', '', $formatNumber); //Se saca )
            
            if (strlen($formatNumber) == 8){
                $formatNumber = '569'.$formatNumber;
            }

            return $formatNumber;
        }

        public function checkCreditosDisponibles($id_empresa, $requeridos)
        {   
            $cuenta = new CuentaCorriente();
            $cuenta->empresa = $id_empresa;
            $disponibles = $cuenta->getCreditoDisponible();

            if ($disponibles >= $requeridos) {
                return true;
            }else{
                return false;
            }
        }


        public function getMessageStatus($id_detalle)
        {
            $detalle = new DetalleEnvio();
            $detalle->id = $id_detalle;
            $detalle->getStatus();

            return $detalle->estado;
        }

        public function check_compania($number)
        {
            $numero_preparado = strlen($number) > 8 ? substr($number, strlen($number) - 8 ) : $number;

            $cantidad_caracteres = array(6, 5, 4);
            $this->company = 'SIN EMPRESA';  

            foreach ($cantidad_caracteres as $value) {

                $finder = $this->find_rango($numero_preparado, $value);

                if (!empty($finder['compania']))
                {

                    $this->company = $finder['compania'];
                    break;
                }
            }

             return $this->company;
        }

        public function find_rango($numero, $length)
        {
            $rango = substr($numero, 0, $length);

            $numeracion = new TablaNumeracion();
            $numeracion->rango = $rango;

            return $numeracion->getCompany();
        }

        public function write_log($cadena)
        {
            $this->error = $cadena;

            $arch = fopen(__DIR__ . '/logs/sms_log.txt', 'a');
            date_default_timezone_set('America/Santiago');
            fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r('-------------------------', true) . "\n");
            fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r($cadena, true) . "\n");

            fclose($arch);
        }

        public function write_logCrone($cadena)
        {
            $arch = fopen('/home/connectus/public_html/' . 'connectus/Logs/adminconnectuslog.txt', 'a');
            date_default_timezone_set('America/Santiago');
            fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r('-------------------------', true) . "\n");
            fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r($cadena, true) . "\n");

            fclose($arch);
        }

        public function fakeSend($cadena)
        {
            $cadena = str_ireplace('&', ' - ', $cadena); //Se sacan espacios
            $arch = fopen(DIR_LINK . 'connectus/Logs/sms_fake_send.txt', 'a');
            date_default_timezone_set('America/Santiago');
            fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r($cadena, true) . "\n");

            fclose($arch);
        }

        public function fakeSendCron($cadena)
        {
            $cadena = str_ireplace('&', ' - ', $cadena); //Se sacan espacios
            $arch = fopen('/home/connectus/public_html/' . 'connectus/Logs/sms_fake_send.txt', 'a');
            date_default_timezone_set('America/Santiago');
            fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r($cadena, true) . "\n");

            fclose($arch);
        }

        public function messageClean($message)
        {
            $message = $message;
            
            $message = str_replace( array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $message ); 
            $message = str_replace( array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $message ); 
            $message = str_replace( array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $message ); 
            $message = str_replace( array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $message ); 
            $message = str_replace( array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $message ); 
            $message = str_replace( array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C',), $message );      
            #$message = str_replace("@", "!", $message );

            #teleco
            $message = str_replace("¡", "", $message );  
            $message = str_replace("¿", "", $message );     
            $message = str_replace("&", "y", $message );    # teleco y lyric

            return $message;
        }
    }