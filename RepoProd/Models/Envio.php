<?php  
    require_once "Models.php"; 

    class Envio extends Models 
    {     

        public $cliente;

        public function getEnviobyCliente(){

            $this->use_db('connectu_adm_connectus');

            $sql = "SELECT e.id_envio, e.estado, e.tipo_envio, e.tipo_mensaje, e.cuando_enviar
                    FROM envio e
                    WHERE e.id_empresa = ".$this->cliente."

                    AND YEAR(e.cuando_enviar) = 2016
                    AND MONTH(e.cuando_enviar) = 4
                    
                    ";


            $result = $this->_db->query($sql);

            if($result->num_rows > 0){

                return $result;
            }else{
                return false;
            }


        }


        public function getVolumenByEnvio($envio)
        {   

            $this->use_db('connectu_adm_connectus');

            $sql = "SELECT count(id_detalle_envio) as cantidad
                    FROM detalle_envio
                    WHERE id_envio = ".$envio;

            $result = $this->_db->query($sql);

            if($result->num_rows > 0){

                return $result;
            }else{
                return false;
            }

        }


        public function __construct() 
        { 
            parent::__construct(); 
        } 
    }