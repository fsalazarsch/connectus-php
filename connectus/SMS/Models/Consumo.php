<?php

    require_once __DIR__."/Models.php";

    class Consumo extends Models
    {

        public $id;
        public $cuenta_corriente;
        public $consumo_sms;
        public $consumo_mail;
        public $valor;

        public function save()
        {
            $sql  = "INSERT INTO consumo SET ";     
            $sql .= "id_cuenta_corriente = '" . $this->cuenta_corriente . "'";
            $sql .= ", sms_consumidos = " . $this->consumo_sms;
            $sql .= ", fecha = NOW()";      
            $sql .= ", mail_consumidos = '" . $this->consumo_mail . "'";
            $sql .= ", valor = ". $this->valor;

            $stmt = $this->conDB->prepare($sql);
            $stmt->execute();

            $this->id = $this->conDB->lastInsertId();

            return true;
        }

        public function get()
        {

            if(empty($this->id)){
                $this->error = "Problemas al obtener ID Consumo";
                return false;
            }

            $sql = "SELECT c.valor, cc.*
                    FROM consumo c
                    INNER JOIN cuenta_corriente cc ON cc.id_cuenta_corriente = c.id_cuenta_corriente
                    WHERE c.id_consumo = ".$this->id;


            $this->cuenta_corriente     = $result['id_cuenta_corriente'];
            $this->consumo_mail         = $result['consumidos_mail']; 
            $this->consumo_sms          = $result['consumidos_sms']; 
            $this->valor                = $result['valor'];

            return true;
        }

        public function delete()
        {

            if(empty($this->id)){
                $this->error = "Problemas al obtener ID Consumo";
                return false;
            }

            $sql    = "DELETE FROM consumo WHERE id_consumo = ".$this->id;
            $stmt   = $this->conDB->prepare($sql);
            $stmt->execute();

            return true;

        }

    }