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

            $arch = fopen(DIR_LINK . 'connectus/Logs/mail_log.txt', 'a');
            date_default_timezone_set('America/Santiago');
            fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r('-------------------------', true) . "\n");
            fwrite($arch, date('Y-m-d G:i:s') . ' - ' . print_r($cadena, true) . "\n");

            fclose($arch);
        }

        
    }