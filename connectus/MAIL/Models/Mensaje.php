<?php

    require_once __DIR__."/Models.php";

    class Mensaje extends Models
    {
        public $id;
        public $cuerpo;
        public $guardar;
        public $remitente;
        public $id_empresa;
        public $titulo;
        public $predefinido;

        public $asunto;
        public $correo_remitente;
        public $nombre_envio;
    
        public function save()
        {
            if(empty($this->id_empresa))
                $this->id_empresa = '';


            if(empty($this->predefinido))
                $this->predefinido = '';


            $sql  = "INSERT INTO mensaje SET tipo = 'MAIL' ";
            $sql .= ", titulo = '" . ($this->asunto) . "'";     
            $sql .= ", correo_remitente = '" . $this->correo_remitente . "'";       
            $sql .= ", cuerpo = '" . $this->cuerpo . "'";
            $sql .= ", is_predefinido = " . ($this->guardar);
            $sql .= ", fecha_creacion = NOW()";     
            $sql .= ", remitente = '" . $this->remitente . "'";

            $sql .= ", id_empresa = '" . $this->id_empresa . "'";

        
            if($this->guardar != 0){
                $sql .= ", nombre_envio = '" . ($this->predefinido) . "'";
                $sql .= ", nombre_predefinido = '" . ($this->nombre_envio) . "'";
            }       

            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                $this->write_log('Error - MAIL::mensaje::save() :'.implode(":",$stmt->errorInfo()));
                return false;
            } else {
                $this->id = $this->conDB->lastInsertId();   
                return true;           
            }   
        }

    }
