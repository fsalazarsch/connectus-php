<?php

    require_once __DIR__."/Models.php";

    class CuentaCorriente extends Models
    {

        public $id;
        public $empresa;
        public $saldo_mail;
        public $consumidos_mail;


        public function getByEmpresa()
        {
            $sql = "SELECT * FROM cuenta_corriente WHERE id_empresa = ".$this->empresa;
            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                $this->write_log('Error - MAIL::CuentaCorriente::getByEmpresa() :'.implode(":",$stmt->errorInfo()));
                return false;

            } else {
                $result = $stmt->fetch();

                $this->id               = $result['id_cuenta_corriente'];
                $this->saldo_mail       = $result['saldo_mail'];
                $this->consumidos_mail  = $result['consumidos_mail'];

                return true;        
            }
        }

        public function edit()
        {

            if(empty($this->id)){
                $this->error = "Error al obtener ID del cliente";
                return false;
            }

            $sql = "UPDATE cuenta_corriente SET consumidos_mail = ".$this->consumidos_mail;


            # solo si está la actualizamos
            if(!empty($this->saldo_mail)){
                $sql .= ", saldo_mail = ".$this->saldo_mail;
            }
            
            $sql .= " WHERE id_cuenta_corriente = " . $this->id;

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
                $this->write_log('Error - MAIL::CuentaCorriente::getCreditoDisponible() :'.implode(":",$stmt->errorInfo()));
                return false;

            } else {
                
                $result = $stmt->fetch();

                $this->saldo_mail        = $result['saldo_mail'];
                $this->consumidos_mail   = $result['consumidos_mail'];

                return ($this->saldo_mail - $this->consumidos_mail);        
            }



        }





    }