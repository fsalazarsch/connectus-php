<?php

    require_once __DIR__."/Models.php";


    /**
     * ocuparemos esta clase para obtener los envios y sus detalles 
     * relacionados a las empresas con failover activado
     */

    class Failover extends Models
    {

    	public function getClientes()
    	{

            $sql = "SELECT id_cliente FROM failover WHERE failover = 1;";

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute()){
                $this->write_log('Error - SMS::Failover::getClientes() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {

                return $stmt->fetchAll();           
            }
    	}

    	public function getEnvios($cliente)
    	{
    		$sql = "SELECT id_envio, id_mensaje, id_lista, tipo_envio
                    FROM envio 
                    WHERE id_empresa = $cliente 
                    AND failover = 1;";

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute()){
                $this->write_log('Error - SMS::Failover::getEnvios() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {

                return $stmt->fetchAll();           
            }
    	}

    	public function getDetalleEnvio($envio)
    	{
            extract($envio);

    		$sql = "SELECT d.id_detalle_envio, d.estado, m.cuerpo, d.id_contacto, d.destinatario
                    FROM detalle_envio d 
                    JOIN mensaje m 
                    WHERE d.id_envio = $id_envio 
                    AND m.id_mensaje = $id_mensaje
                    AND d.estado in ('UNDELIVERED', 'UNKNOWN');";

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute()){
                $this->write_log('Error - SMS::Failover::getDetalleEnvio() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {

                return $stmt->fetchAll();           
            }
    	}

        public function getDatosContacto($contacto)
        {

            $sql = "SELECT *
                    FROM contacto 
                    WHERE id_contacto = $contacto ;";

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute()){
                $this->write_log('Error - SMS::Failover::getDetalleEnvio() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {

                return $stmt->fetch();           
            }
        }


        public function getCampos($lista)
        {

            $sql = "SELECT * FROM campo WHERE id_lista = ".$lista.";";

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                $this->write_log('Error - SMS::Failover::getCampos() :'.implode(":",$stmt->errorInfo())."\n".$sql);
                return false;

            } else {

                return $stmt->fetchAll();           
            }
        }

        public function updEstado($estado, $id_detalle_envio, $channel)
        {
            $sql = "UPDATE detalle_envio SET estado = '".$estado."', num_channel = $channel WHERE id_detalle_envio = ".$id_detalle_envio;

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                $this->write_log('Error - SMS::Failover::updEstado() :'.implode(":",$stmt->errorInfo()));
                return false;
            
            } else {
                return true;         
            }

        }
    }