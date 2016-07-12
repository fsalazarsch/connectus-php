<?php

    require_once __DIR__."/Models.php";

    class Envio extends Models
    {
        public $id;
        public $conector;
        public $estado;
        public $id_consumo;
        public $id_empresa;
        public $id_lista;
        public $id_mensaje;
        public $nombre_envio;
        public $remitente;
        public $correo_remitente;
        public $tipo_envio;
        public $tipo_mensaje;
        public $datos_envio;
        public $correos_malos;


        public function save()
        {
            $sql =  "INSERT INTO envio SET";
            $sql .= " estado = '". $this->estado ."'";
            $sql .= ",remitente = '". $this->remitente ."'";
            $sql .= ",correo_remitente = '". $this->correo_remitente ."'";      
            $sql .=',id_mensaje =' . $this->id_mensaje;       
            $sql .=",tipo_envio = '" . $this->tipo_envio . "'";
            $sql .=",tipo_mensaje = '" . $this->tipo_mensaje . "'";
            $sql .=",cuando_enviar = NOW()";        
            $sql .=",nombre_envio = '" . ($this->nombre_envio) . "'";             

            if ($this->id_lista != '') {
                $sql .= ", id_lista = " . $this->id_lista;
            }

            if ($this->correos_malos != '') {
                $sql .= ", correos_malos = " . $this->correos_malos;
            }

            if($this->id_consumo != ''){
                $sql .= ", id_consumo = ".$this->id_consumo;
            }

            $sql .= ",id_conector = " . $this->conector;
            $sql .= ",id_empresa = " . $this->id_empresa;

            $sql .=",datos_envio_programado = '" . str_replace("'", "", $this->datos_envio) . "'";

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                $this->write_log('Error - MAIL::Envio::save() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {
                $this->id = $this->conDB->lastInsertId();   
                return true;           
            }   
        }


        public function get()
        {
            if(empty($this->id)){
                $this->error = "Problemas al obtener ID envío";
                return false;
            }

            $sql = "SELECT * FROM envio WHERE id_envio = ".$this->id;

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute()){
                $this->write_log('Error - MAIL::Envio::get() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {

                $result = $stmt->fetch();
                $this->estado = $result['estado'];

                return true;           
            }
        }

        public function edit()
        {
            if(empty($this->id)){
                $this->error = "Problemas al obtener ID envío";
                return false;
            }


            $sql =  "UPDATE envio SET 
                        estado = '".$this->estado."' ";

            if(!empty($this->correos_malos)){
                $sql .= " , correos_malos = ".$this->correos_malos;
            }

            $sql .= " WHERE id_envio = ".$this->id;        

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                $this->write_log('Error - MAIL::Envio::edit() :'.implode(":",$stmt->errorInfo()));
                return false;

            } else {
                return true;           
            }
        }

        
    }
