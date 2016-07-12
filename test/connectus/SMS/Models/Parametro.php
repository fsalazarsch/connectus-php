<?php

    require_once __DIR__."/Models.php";

    class Parametro extends Models
    {

        public $id;

        public function get()
        {
            $sql = "SELECT * FROM parametro WHERE id_parametro = '".$this->id."'";
            $stmt = $this->conDB->prepare($sql);

            if (!$stmt->execute())
            {
                $this->write_log('Error - SMS::Parametro::get() :'.implode(":",$stmt->errorInfo()));
                return false;

            } else {
                return $stmt->fetch();           
            }
        }

    }