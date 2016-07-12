<?php


    require_once "Models/Cliente.php";
    require_once "Models/Envio.php";

    
    class ReportEnvio
    {

        public $error;

        public function getClientes(){

            $cli = new Cliente();
            $clientes = $cli->getAll();
            
            if(!$clientes){
                return false;
            }else{
                return $clientes;
            }

        }


        public function getEnviosPorCliente($cliente)
        {

            $env = new Envio();

            $env->cliente = $cliente;
            $envios = $env->getEnviobyCliente();
            
            if(!$envios){
                return false;
            }else{
                return $envios;
            }

        }


        public function getVolumen($envio){

            $env = new Envio();

            $volumen = $env->getVolumenByEnvio($envio);

            return $volumen;

        }
    }


    
    
?>