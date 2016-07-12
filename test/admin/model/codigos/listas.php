<?php
    
    class ModelCodigosListas extends Model{


        public function getLista()
        {
            $sql = 'SELECT pais, codigo
                    FROM codigo_pais
                    WHERE activo = 1 
                    ORDER BY pais ASC';                
        
            $result = $this->admDB->query($sql);
            return $result->rows;   

        }


    }