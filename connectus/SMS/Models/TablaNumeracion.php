<?php

    require_once __DIR__."/Models.php";

    class TablaNumeracion extends Models
    {

        public $id;
        public $rango;

        public function getCompany()
        {
            $sql = "SELECT compania FROM tabla_numeracion WHERE rango = ".$this->rango ;
            
            $stmt = $this->conDB->prepare($sql);
            
            if (!$stmt->execute())
            {
                $this->write_log('Error - SMS::TablaNumeracion::getCompany() :'.implode(":",$stmt->errorInfo()));
                return false;

            } else {
                return $stmt->fetch();           
            }
        }

    }