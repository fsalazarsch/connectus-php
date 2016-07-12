<?php

    require_once __DIR__."/Models.php";

    class Conector extends Models
    {

        public $id;
        public $empresa;
        public $nombre;
        public $glosa;
        public $key;

        public function get()
        {
            $sql  = "   SELECT cc.id_conector_mail , con.glosa, con.nombre, con.key
                        FROM cuenta_corriente cc 
                        INNER JOIN conector con ON cc.id_conector_mail = con.id_conector
                        WHERE id_empresa = ".$this->empresa;
          
            $stmt = $this->conDB->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();

            if(is_null($result['nombre']) OR empty($result['nombre'])){
                $this->error = "Problemas al obtener conector get()";
                return false;
            }

            $this->id       = $result['id_conector_mail'];
            $this->nombre   = $result['nombre'];
            $this->glosa    = $result['glosa'];
            $this->key      = $result['key'];

            $this->error = null;
            return true;
        }

        public function getByNombre()
        {
            $sql  = "   SELECT *
                        FROM conector 
                        WHERE nombre = '".$this->nombre."' ORDER BY ASC ";
          
            $stmt = $this->conDB->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();

            if(is_null($result['nombre']) OR empty($result['nombre'])){
                $this->error = "Problemas al obtener conector getByNombre()";
                return false;
            }

            $this->id       = $result['id_conector'];
            $this->nombre   = $result['nombre'];
            $this->key      = $result['key'];

            $this->error = null;
            return true;
        }

    }
