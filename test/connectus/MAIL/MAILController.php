<?php
    error_reporting(E_ALL);

    require_once __DIR__."/Models/Conector.php";

    class MAILController
    {
        private $id_conector;
        private $conector;
        private $empresa;
        public  $error;
        public  $key;

        function __construct($id_empresa, $conector = null)
        {
            # Set empresa
            if(!$this->setEmpresa($id_empresa)){ return false; }

            # Set/Get conector empresa
            if(is_null($conector)) {
                # GET
                if(!$this->getConector()){ return false; }

            } else {
                # SET
                if(!$this->setConector($conector)){ return false; }
            }
        }

        private function setConector($conector)
        {
            if(is_string($conector)){
                
                $this->conector = $conector;
                $this->getConectorByNombre();
                
            }else{
                $this->error = "Conector no es valido";
                return false;
            }
        }

        private function setEmpresa($id_empresa)
        {
            if(is_null($id_empresa) OR empty($id_empresa))
            {
                $this->error = "Problemas con el ID Empresa";
                return false;
            }

            $this->empresa = $id_empresa;
            return true;
        }

        public function getAPI()
        {

            require_once __DIR__."/Drivers/".$this->conector.'.php';

            if(empty($this->key) || is_null($this->key) || $this->key == '')
            {
                $api = new $this->conector();
            }else{
                $api = new $this->conector($this->key);
            }

            
            $api->conector = $this->id_conector;

            return $api;
        }


        private function getConector()
        {
            $conector = new Conector();
            $conector->empresa = $this->empresa;
            $conector->get();

            if(!is_null($conector->error)){
                $this->error = $conector->error;
                return false;
            }

            $this->id_conector  = $conector->id;
            $this->conector     = $conector->nombre;
            $this->key          = $conector->key;
        }

        private function getConectorByNombre()
        {
            $conector = new Conector();
            $conector->nombre = $this->conector;
            $conector->getByNombre();

            if(!is_null($conector->error)){

                $this->error = $conector->error;
                return false;
                
            }

            $this->id_conector  = $conector->id;
            $this->conector     = $conector->nombre;
        }

    }