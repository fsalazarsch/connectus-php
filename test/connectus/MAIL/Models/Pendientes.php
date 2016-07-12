<?php 


	require_once __DIR__."/Models.php";

    class Pendientes extends Models
    {

        public $id;

        public function get()
        {
            $sql = 'SELECT id_empresa, id_envio, tipo_mensaje, tipo_envio, datos_envio_programado
            		FROM envio 
            		WHERE cuando_enviar <= NOW() 
            		AND tipo_mensaje = "MAIL" 
            		AND estado = "pendiente"';

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                $this->write_log('Error - MAIL::Parametro::get() :'.implode(":",$stmt->errorInfo()));
                return false;

            } else {
                return $stmt->fetchAll();           
            }
        }

    }