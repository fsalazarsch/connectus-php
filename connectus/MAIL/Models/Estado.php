<?php

    require_once __DIR__."/Models.php";

    class Estado extends Models
    {

    	public $id;
    	public $estado;

    	public $id_envio;
    	public $destinatario;


    	public function updateEstado()
    	{
    		
            $sql = "UPDATE detalle_envio SET 
                    estado = '".$this->estado."' 
            		WHERE id_envio = ".$this->id_envio." 
                    AND destinatario = '".$this->destinatario."' ";

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                #$this->write_log('Error - MAIL::Estado::updateEstado() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {
                return true;           
            }

    	}

        public function getSuma()
        {
            
            $sql = "SELECT estado_".$this->estado." as suma 
                    FROM detalle_envio 
                    WHERE id_envio = ".$this->id_envio." 
                    AND destinatario = '".$this->destinatario."' ";

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                #$this->write_log('Error - MAIL::Estado::getSuma() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {

                $result = $stmt->fetch();
                $this->suma = $result['suma'];

                return true;           
            }

        }

        public function updateSuma()
        {
            $sql = "UPDATE detalle_envio SET 
                    estado_".$this->estado." = ".$this->suma." 
                    WHERE id_envio = ".$this->id_envio." 
                    AND destinatario = '".$this->destinatario."' ";

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                #$this->write_log('Error - MAIL::Estado::updateSuma() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {
                return true;           
            }
        }
    }