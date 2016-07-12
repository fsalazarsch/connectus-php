<?php

    require_once __DIR__."/Models.php";

    class Conector extends Models
    {

        public $id;
        public $empresa;
        public $nombre;

        public function get()
        {
            $sql  = "   SELECT cc.id_conector_sms , con.glosa
                        FROM cuenta_corriente cc 
                        INNER JOIN conector con ON cc.id_conector_sms = con.id_conector
                        WHERE id_empresa = ".$this->empresa;
          
            $stmt = $this->conDB->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();

            if(is_null($result['glosa']) OR empty($result['glosa'])){
                $this->error = "Problemas al obtener conector";
                return false;
            }

            $this->id       = $result['id_conector_sms'];
            $this->nombre   = $result['glosa'];

            $this->error = null;
            return true;
        }

        public function getByNombre()
        {
            $sql  = "   SELECT *
                        FROM conector 
                        WHERE glosa = '".$this->nombre."'";
          
            $stmt = $this->conDB->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();

            if(is_null($result['glosa']) OR empty($result['glosa'])){
                $this->error = "Problemas al obtener conector";
                return false;
            }

            $this->id       = $result['id_conector'];
            $this->nombre   = $result['glosa'];

            $this->error = null;
            return true;
        }

    }
