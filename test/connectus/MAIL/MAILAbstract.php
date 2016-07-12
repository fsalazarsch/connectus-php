<?php

    require_once __DIR__."/Models/Consumo.php";
    require_once __DIR__."/Models/CuentaCorriente.php";
    require_once __DIR__."/Models/DetalleEnvio.php";
    require_once __DIR__."/Models/Envio.php";
    require_once __DIR__."/Models/Mensaje.php";
    require_once __DIR__."/Models/Parametro.php";
    
    abstract class MAILAbstract
    {
        public $conDB;
        public $conector;
        public $message;

        /**
         *  envío de MAIL
         */
        abstract public function sendMail($nombre_remitente, $email_remitente, $destinatario, $asunto, $message, $connectusKey, $id_empresa, $momento_envio,$is_predefinido ,$id_envio_programado, $nombre_predefinido, $nombre_envio, $id_consumo);
        
        /**
         *  envío de MAIL vía API REST
         */                                  
        abstract public function sendMailRest($nombre_remitente, $email_remitente, $destinatario, $asunto, $message, $id_empresa, $nombre_predefinido, $nombre_envio);
        
        /**
         *  envío de MAIL Masivo
         */
        abstract public function sendMassMail($mensaje, $connectusKey, $id_empresa, $momento_envio, $is_predefinido, $result = array(), $titulo = '', $id_lista, $nombre_mensaje_predefinido, $remitente,  $id_envio_programado);
        
        /**
         *  obtención del estado del MAIL enviado
         *  y se guarda en la BD
         */
        abstract public function get_status_from_server($id_detalle_envio);

        /**
         *  obtención de los MAIL recividos
         */






        public function addMail ($asunto, $cuerpo, $remitente, $correo_remitente, $guardar, $nombre_envio, $id_empresa)
        {
            $mensaje = new Mensaje();

            $mensaje->asunto            = $asunto;
            $mensaje->correo_remitente  = $correo_remitente;
            $mensaje->nombre_envio      = $nombre_envio;

            $mensaje->cuerpo        = $cuerpo;
            $mensaje->guardar       = $guardar;
            $mensaje->remitente     = $remitente;
            $mensaje->id_empresa    = $id_empresa;

            if($mensaje->save()){
                return $mensaje->id;   
            }else{
                return false;
            }       
        }

        public function addEnvio($estado , $nombre_remitente, $correo_remitente, $id_lista, $id_mensaje, $tipo_envio, $tipo_mensaje, $nombre_envio, $correos_malos, $id_empresa, $asunto, $id_consumo)
        {

            if(empty($asunto)){
                $asunto = $nombre_envio;
            }
            $data['asunto'] = $asunto;
            $datos_envio = json_encode($data, JSON_UNESCAPED_UNICODE);


            $envio = new Envio();

            $envio->estado          = $estado;
            $envio->remitente       = $nombre_remitente;
            $envio->correo_remitente= $correo_remitente;
            $envio->id_mensaje      = $id_mensaje;
            $envio->tipo_envio      = $tipo_envio;
            $envio->tipo_mensaje    = $tipo_mensaje;
            $envio->nombre_envio    = $nombre_envio;
            $envio->id_lista        = $id_lista;
            $envio->conector        = $this->conector;
            $envio->id_empresa      = $id_empresa;
            $envio->id_consumo      = $id_consumo;
            $envio->datos_envio     = $datos_envio;
            $envio->correos_malos   = $correos_malos;

            $envio->save();

            return $envio->id;     
        }

        public function addDetalleEnvio($id_respuesta_servidor, $id_envio, $destinatario, $id_contacto, $estado)
        {
            $detalle = new DetalleEnvio();

            $detalle->id_respuesta  = $id_respuesta_servidor;
            $detalle->id_envio      = $id_envio;
            $detalle->destinatario  = $destinatario;
            $detalle->id_contacto   = $id_contacto;
            $detalle->estado        = $estado;

            $detalle->save();

            return $detalle->id;            
        }

        public function traducirEstado($estado)
        {
            $retorno = '';

            if($estado == 'failed' || $estado == 'rejected' || $estado ==  'stored' || $estado == 'complained' || $estado == 'bounced' || $estado == 'hard_bounce'){
                $retorno = 'Rebote';
            }elseif($estado == 'clicked' || $estado == 'click'){
                $retorno = 'Click';
            }elseif($estado == 'opened' || $estado == 'open'){
                $retorno = 'Abierto';
            }elseif($estado == 'delivered' || $estado == 'accepted' || $estado == 'sent' || $estado == 'send'){
                $retorno = 'Entregado';
            }elseif($estado == 'en proceso' || $estado == 'queued' ){
                $retorno = 'Esperando confirmar';
            }elseif($estado == 'desconocido'){
                $retorno = 'Pendiente';
            }else{
                $retorno = ucwords($estado);
            }


            return $retorno;

        }

        public function insertConsumo($id_empresa , $consumo_mail, $consumo_MAIL, $valor)
        {
            $cuenta = new CuentaCorriente();
            $cuenta->empresa = $id_empresa;

            $cuenta->getByEmpresa();

            $consumo = new Consumo();
            $consumo->cuenta_corriente = $cuenta->id;
            $consumo->consumo_MAIL = $consumo_MAIL;
            $consumo->consumo_mail = $consumo_mail;
            $consumo->valor = $valor;

            $consumo->save();

            $this->actualizarCuenta($id_empresa,$consumo_MAIL);

            return $consumo->id;
        }

        public function actualizarCuenta($id_empresa, $consumo)
        {
            $cuenta = new CuentaCorriente();
            $cuenta->empresa = $id_empresa;
            $cuenta->getByEmpresa();

            #$nuevo_saldo_MAIL = $cuenta->saldo_MAIL;# ??
            $cuenta->consumidos_mail = ($cuenta->consumidos_mail + $consumo);
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

        public function updateDetalleEnvio($id_envio, $id_envio_mailgun){
        
            $sql =  "UPDATE detalle_envio SET 
                            estado = 'en proceso' , 
                            id_respuesta_servidor = '". $id_envio_mailgun ."' 
                            WHERE id_envio = ". $id_envio;      

            $stmt = $this->conDB->prepare($sql);
            $stmt->execute();       

            return $id_envio;       
        }

        public function updateDetalleEstado($id_detalle_envio, $estado){
        
            $detalle = new DetalleEnvio();
            $detalle->id = $id_detalle_envio;
            $detalle->estado = $estado;
            return $detalle->editEstado();
        }



        public function updateFechaEnvio($id_envio){
        
            $sql =  "UPDATE envio SET cuando_enviar = NOW() WHERE id_envio = '". $id_envio ."'";        

            $stmt = $this->conDB->prepare($sql);
            $stmt->execute();       

            return $id_envio;       
        }

        public function updateEnvioMasivo($id_envio, $correos_malos){   

            $envio = new Envio();
            $envio->id = $id_envio;
            $envio->get();

            $envio->correos_malos = $correos_malos;
            $envio->edit();
    
            return $envio->id;       
        }

        public function updateEnvioEstado($id_envio, $estado_envio)
        {
            $envio = new Envio();
            $envio->id = $id_envio;
            $envio->get();

            $envio->estado = $estado_envio;
            $envio->edit();
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

        public function deleteConsumo($id)
        {
            $consumo = new Consumo();
            $consumo->id = $id;
            $consumo->get();

            # Actualizamos los mail consumidos
            $cuenta_corriente = new CuentaCorriente();
            $cuenta_corriente->id               = $consumo->cuenta_corriente;
            $cuenta_corriente->consumidos_mail  = ($consumo->consumo_mail - $consumo->valor);
            $cuenta_corriente->edit();
            
            # Eliminamos el consumo
            $consumo->delete();

            return true;        
        }

        public function write_log($cadena)
        {
            $this->error = $cadena;

            $arch = fopen(__DIR__ . '/logs/mail_log.txt', 'a');
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

        public function center_image($html_message)
        {
            $newtext=preg_replace("#(<img[^>]*>)#s","<div style='text-align: center;'>"."\${1}"."</div>",$html_message);
            return $newtext;
        }
    }