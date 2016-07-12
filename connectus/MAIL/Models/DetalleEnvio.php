<?php

    require_once __DIR__."/Models.php";

    class DetalleEnvio extends Models
    {

        public $id;
        public $destinatario;
        public $estado;
        public $fecha;
        public $id_envio;
        public $id_respuesta;
        public $id_contacto;


        public function save()
        {
            $sql  = "INSERT INTO detalle_envio SET ";
            $sql .= " id_respuesta_servidor =  '" . $this->id_respuesta . "'";


            if ($this->estado != '') {
                $sql .= ",estado = '".$this->estado."'" ; 
            }else{
                $sql .= ",estado = 'en proceso'" ;  
            }


            if ($this->id_contacto != '') {
                $sql .= ",id_contacto = '".$this->id_contacto."'" ;   
            }

            $sql .=",fecha = NOW()";        
            $sql .= ",destinatario =  '" . $this->destinatario . "'";
            $sql .= ",id_envio = " . $this->id_envio;


            $stmt = $this->conDB->prepare($sql);


            if (!$stmt->execute())
            {
                $this->write_log('Error - MAIL::DetalleEnvio::save() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {
                $this->id = $this->conDB->lastInsertId();
                return true;           
            }
        }

        public function get()
        {
            if(empty($this->id)){
                $this->error = "Problemas al obtener ID detalle envío";
                return false;
            }

            $sql = "SELECT * FROM detalle_envio WHERE id_detalle_envio = ".$this->id;

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute()){
                $this->write_log('Error - MAIL::DetalleEnvio::get() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {

                $result = $stmt->fetch();

                $this->id_respuesta   = $result['id_respuesta_servidor'];
                $this->channel  = $result['num_channel'];
                $this->id_envio = $result['id_envio'];
                $this->estado   = $result['estado'];
                $this->fecha    = $result['fecha'];

                return true;           
            }

        }

        public function getStatus($rest = false)
        {
            if(empty($this->id)){
                $this->error = "Problemas al obtener ID detalle envío";
                return false;
            }

            $cuenta = "SELECT estado FROM detalle_envio WHERE id_detalle_envio = '".$this->id."'";
            $stmt = $this->conDB->query($cuenta);
            //$stmt->execute();
            $result = $stmt->fetch();


            if (empty($result['estado'])) {
                $this->estado = '046';
            } else {
                $estado_format = str_ireplace(' ', '_', $this->traducirEstado($result['estado'])); //Se sacan espacios
                $this->estado = $this->messageClean($estado_format);
            }

            return true;
        }


        public function editByIdMsg()
        {
            if(empty($this->id_respuesta)){
                $this->error = "Problemas al obtener ID respuesta"; return false;
            }

            if(empty($this->estado)){
                $this->error = "Problemas al obtener estado"; return false;
            }


            $sql =  "UPDATE detalle_envio SET 
                        estado = '".$this->estado."' 
                    WHERE id_respuesta_servidor = ".$this->id_respuesta;


            $stmt = $this->conDB->prepare($sql);



            if (!$stmt->execute())
            {
                $this->write_log('Error - MAIL::DetalleEnvio::edit() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {
                return true;           
            }
        }


        public function editEstado()
        {
            if(empty($this->id)){
                $this->error = "Problemas al obtener ID"; return false;
            }

            if(empty($this->estado)){
                $this->error = "Problemas al obtener estado"; return false;
            }


            $sql =  "UPDATE detalle_envio SET 
                        estado = '".$this->estado."' 
                    WHERE id_detalle_envio = ".$this->id;


            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                $this->write_log('Error - MAIL::DetalleEnvio::editEstado() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {
                return true;           
            }


        }

        
    }