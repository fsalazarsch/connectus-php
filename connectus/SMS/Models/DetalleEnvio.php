<?php

    require_once __DIR__."/Models.php";

    class DetalleEnvio extends Models
    {

        public $id;
        public $channel;
        public $compania;
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

            if(!empty($this->id_contacto))
            {
                $sql .= ", id_contacto = ".$this->id_contacto;
            }


            if(!is_null($this->channel)  && $this->channel != ''){
                $sql .= " ,num_channel =  '" . $this->channel . "'";
            }                


            if($this->id_respuesta == '-1'){
                $sql .= ",estado = 'UNKNOWN'" ;
            } else if ($this->id_respuesta == '0') {
                $sql .= ",estado = 'INVALID_DNS'" ;
            } else {
                $sql .= ",estado = 'en proceso'" ;
            }

            $sql .=",fecha = NOW()";        
            $sql .= ",destinatario =  '" . $this->destinatario . "'";
            $sql .= ",id_envio = " . $this->id_envio;

            $sql .= ", empresa_telefono_receptor = '" . $this->compania . "'";

            $stmt = $this->conDB->prepare($sql);


            if (!$stmt->execute())
            {
                $this->write_log('Error - SMS::DetalleEnvio::save() :'.implode(":",$stmt->errorInfo()));
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
                $this->write_log('Error - SMS::DetalleEnvio::get() :'.implode(":",$stmt->errorInfo()));
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
                $this->write_log('Error - SMS::DetalleEnvio::edit() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {
                return true;           
            }
        }

        
    }