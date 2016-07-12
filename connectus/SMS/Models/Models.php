<?php

    require_once __DIR__."/../../../admin/config.php";

    class Models 
    {   

        public $conDB;
        public $error;
        
        function __construct()
        {
            # Connectar BD
            $str_con = "mysql:host=".DB_HOSTNAME_CONNECTUS.";dbname=".DB_DATABASE_CONNECTUS.";charset=utf8";
            
            $this->conDB = new PDO($str_con, DB_USERNAME_CONNECTUS, DB_PASSWORD_CONNECTUS);                              
            $this->conDB->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        }

        public function write_log($cadena)
        {
            $this->error = $cadena;

            $arch = fopen(DIR_LINK . 'connectus/Logs/sms_log.txt', 'a');
            date_default_timezone_set('America/Santiago');
            fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r('-------------------------', true) . "\n");
            fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r($cadena, true) . "\n");

            fclose($arch);
        }

        public function traducirEstado($estado)
        {
            $retorno = '';

            if($estado == 'DELIVERED' || $estado == 'CONFIRMED DELIVERY'){
                $retorno = 'Confirmado';

            } else if($estado == 'UNDELIVERED' || $estado == 'UNKNOWN'){
                $retorno = 'No entregado';

            } else if($estado == 'WAITING FOR CONFIRMATION' || $estado =='ROUTING'|| $estado == 'INCOMMING' || $estado == 'DEFERRED' || $estado ==  'SENT' ){
                $retorno = 'Esperando confirmación';

            } else if ($estado == 'en proceso' ) {
                $retorno = 'En proceso';

            } else if ($estado == 'desconocido') {
                $retorno = 'Pendiente';

            } else if ($estado == 'terminado') {
                $retorno = 'Terminado';
                
            } else if ($estado == 'INVALID_DNS') {
                $retorno = 'DNS Inválido';

            }

            return $retorno;
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

            $message = str_replace("¡", "", $message ); 
            $message = str_replace("&", "y", $message );

            return $message;
        }
    }