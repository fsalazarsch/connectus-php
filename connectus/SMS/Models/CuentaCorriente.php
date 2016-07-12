<?php

    require_once __DIR__."/Models.php";

    class CuentaCorriente extends Models
    {

        public $id;
        public $empresa;
        public $saldo_sms;
        public $consumidos_sms;


        public function getByEmpresa()
        {
            $sql = "SELECT * FROM cuenta_corriente WHERE id_empresa = ".$this->empresa;
            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                $this->write_log('Error - SMS::CuentaCorriente::getByEmpresa() :'.implode(":",$stmt->errorInfo()));
                return false;

            } else {
                $result = $stmt->fetch();

                $this->id               = $result['id_cuenta_corriente'];
                $this->saldo_sms        = $result['saldo_sms'];
                $this->consumidos_sms   = $result['consumidos_sms'];

                return true;        
            }
        }

        public function edit()
        {

            if(empty($this->id)){
                $this->error = "Error al obtener ID del cliente";
                return false;
            }

            $sql = "UPDATE cuenta_corriente SET 
                        saldo_sms = ".$this->saldo_sms.", 
                        consumidos_sms = ".$this->consumidos_sms." 
                    WHERE id_cuenta_corriente = " . $this->id;

            $stmt = $this->conDB->prepare($sql);
            
            $stmt->execute();
        }

        public function getCreditoDisponible(){


            if(empty($this->empresa)){
                $this->error = "Error al obtener ID del cliente";
                return false;
            }

            $sql = "SELECT * FROM cuenta_corriente WHERE id_empresa = ".$this->empresa;
            $stmt = $this->conDB->prepare($sql);


            if (!$stmt->execute())
            {
                $this->write_log('Error - SMS::CuentaCorriente::getCreditoDisponible() :'.implode(":",$stmt->errorInfo()));
                return false;

            } else {
                
                $result = $stmt->fetch();

                $this->saldo_sms        = $result['saldo_sms'];
                $this->consumidos_sms   = $result['consumidos_sms'];

                return ($this->saldo_sms - $this->consumidos_sms);        
            }



        }





    }