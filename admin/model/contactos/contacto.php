<?php
class ModelContactosContacto extends Model {
    public function addLista($nombre,$id_empresa,$id_usuario){
        //cargar archivos de user desde model
        $result = $this->admDB->query("INSERT INTO `lista_contacto` SET nombre = '" . $nombre . "', fecha_creacion = NOW(), ultima_actualizacion = NOW(), id_empresa = '" . $id_empresa . "', id_usuario = ".$id_usuario);
        if ($result) {
            return $this->admDB->getLastId();
        }  else{
            return false;
        }      
    }

    public function addContactos($id_lista,$nombre = '',$email = ' ',$telefono = ' ', $sexo = ' ', $fecha_nac = ' ', $celular = ' ', $estado = 1, $custom1 = '', $custom2 = '', $custom3 = '', $custom4 = '', $custom5 = '', $custom6 = '', $custom7 = '', $custom8 = '', $custom9 = '', $custom10 = ''){
        $this->admDB->query("INSERT INTO `contacto` SET id_lista = '" . $id_lista .",nombre = '" . $nombre . "', email = '" . $email . 
                             "', telefono = '" . $telefono . "', sexo = " . $sexo . ",fecha_nac = ". $fecha_nac .",celular = ". $celular .
                             ", custom1 = " . $custom1 . ", custom2 = ". $custom2 . ",custom3 = " . $custom3 . ", custom4 = " .$custom4 . ", custom5 = " . $custom5 .
                             ", custom6 = " . $custom6 . ",custom7 = " . $custom7 .",custom8 = " . $custom8 . ",custom9 = " . $custom9 . ",custom10 = " . $custom10 );
        return $this->admDB->getLastId();
    }

    public function getTotalListas() {
        $query = $this->admDB->query("SELECT COUNT(*) AS total FROM lista_contacto");
        return $query->row['total'];
    }

    public function getListas($lista_id){

        $conDB = new mysqli(DB_DRIVER_CONNECTUS,DB_HOSTNAME_CONNECTUS,DB_USERNAME_CONNECTUS,DB_PASSWORD_CONNECTUS,DB_DATABASE_CONNECTUS);
        $stmt = $conDB->prepare("SELECT numero, firstname FROM contacto WHERE lista_id = ?");
        $stmt->bind_param("s", $lista_id);
        $stmt->execute();
        $stmt->bind_result($numero,$firstname);

        //array de arrays
        $array = array();
        while ($stmt->fetch()){
            $string = array();
            $string['nombre'] = $firstname;
            $string['numero'] = $numero;
            array_push($array,$string);
        }
        $stmt->close();
        return $array;
    }

    public function deleteContacto($id_contacto){
        $sql = "UPDATE contacto SET borrado = 1 WHERE id_contacto = " . $id_contacto;
        $result = $this->admDB->query($sql);
        return $result;
    }
   
    public function updateContacto($campos, $valores,$id_contacto ) {
        $sql = 'UPDATE contacto SET ';

        for ($indice=0; $indice < count($valores); $indice++) { 
            $sql .= $campos[$indice] . " = '" . $valores[$indice] . "'";
            if($indice < count($valores)-1){
                $sql .= ",";
            }
        }
        $sql .= " WHERE id_contacto = " . $id_contacto;

        $result = $this->admDB->query($sql);
        $rows = $result->rows;
        return $rows;
    }
    
    public function getContacto($id_contacto, $id_lista){
        $contactos_info['nombre_columnas'] = array();
        $contactos_info['campos_de_contacto'] = array();
        $contactos_info['valores'] = array();

        $result = $this->admDB->query("SELECT * FROM campo WHERE id_lista = " . $id_lista);
        $nombre_campos = $result->rows;

        $sql = 'SELECT ';
        $cantidad_registros = sizeof($nombre_campos);  

        for ($indice=0; $indice < $cantidad_registros ; $indice++) { 
            if ($indice < $cantidad_registros) {
                $sql .= $nombre_campos[$indice]['campo'] . ',';
                $contactos_info['campos_de_contacto'][] = $nombre_campos[$indice]['campo'];
            }
            $contactos_info['nombre_columnas'][] = $nombre_campos[$indice]['glosa']; 
        }

        $contactos_info['campos_de_contacto'][] = 'id_contacto';
        $sql .= " id_contacto FROM contacto WHERE id_contacto = " . $id_contacto;    
                
        $aux = $this->admDB->query($sql);

        $contactos_info['valores'] = $aux->rows;
 
        return $contactos_info;
    }

    public function getContactoPorEmail( $select, $email, $id_lista){
        $sql = "select " . $select . " from contacto where email = '" . $email . "'";
        $sql .= " AND id_lista = " . $id_lista;
        $result = $this->admDB->query($sql);

        return $result; 
    }

    public function getContactoPorCelular($select, $celular, $id_lista) {
        $sql = "select " . $select . " from contacto where celular = '" . $celular . "'";
        $sql .= " AND id_lista = " . $id_lista;

        $result = $this->admDB->query($sql);
        return $result;
    }

    public function getContactoPorTelefono($select, $telefono, $id_lista ){
        $sql = "select " . $select . " from contacto where telefono = '" . $telefono . "'";
        $sql .= " AND id_lista = " . $id_lista;

        $result = $this->admDB->query($sql);
        return $result;
    }

    public function getContactoPoId($select, $id_contacto, $id_lista){
        $sql = "select " . $select . " from contacto where id_contacto = " . $id_contacto;
        $sql .= " AND id_lista = " . $id_lista;
        $result = $this->admDB->query($sql);

        return $result;
    }

    public function getContactoForm( $id_lista){       
        $sql = "SELECT * FROM campo WHERE id_lista = ". $id_lista;
        $result = $this->admDB->query($sql);

        $form['campo'] = array();
        $form['glosa'] = array();

        foreach ($result->rows as $campo) {
            $form['campo'][] = $campo['campo']; 
            $form['glosa'][] = $campo['glosa'];
        }

        return $form;
    }

    public function inscribirContacto($id_contacto)
    {
        $sql = "update  contacto set desinscrito= 0 where id_contacto = " . $id_contacto;
        $this->admDB->query($sql);

        $sql = "delete from desinscrito where id_contacto = " . $id_contacto;
        $this->admDB->query($sql);
    }

    /* public function getContactoEditable($id_contacto){
        $sql = "select * from campo where id_lista = (select id_lista from contacto where id_contacto = ". $id_contacto .")";
        $campos = $this->admDB->query($sql);

        $contacto['campos']       = array();
        $contacto['valores']      = array();
        $contacto['glosa_campos'] = array();

        $sql = "SELECT ";

        $result = $campos->rows;
        for ($indice=0; $indice < count($result) ; $indice++) {
            if ($indice < count($result)-1) {
                $sql .= $value['campo'] . ",";
                $contacto['campos'][] = $value['campo'];
                $contacto['glosa_campos'][] = $value['glosa'];
            } else{
                $sql .= $value['campo'] ;
                $contacto['campos'][] = $value['campo'];
                $contacto['glosa_campos'][] = $value['glosa'];
            }            
        }

        $sql .= " from contacto where id_contacto = " . $id_contacto;

        $result = $this->admDB->query($sql);

        foreach ($result as $valor_campo) {
            $contacto['valores'][] = $valor_campo;
        }

        return $contacto;
    }*/
}