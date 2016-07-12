<?php
class ModelMailingListaMailsPredefinidos extends Model {


public function getMailPredefinidos($id_empresa, $data = array()){
        $string_connection = "mysql:host=".DB_HOSTNAME_CONNECTUS.";dbname=".DB_DATABASE_CONNECTUS;
        $conexion = new PDO($string_connection,DB_USERNAME_CONNECTUS,DB_PASSWORD_CONNECTUS);

        $sql = 'SELECT * FROM mensaje
                  WHERE tipo="MAIL"
                  AND is_predefinido = 1
                  AND id_empresa = '.$id_empresa;

        $sql .= " AND borrado = 0 "; 

        if (!empty($data['filter_nombre'])) { 
            $sql .= " AND titulo LIKE '%" . $this->db->escape($data['filter_nombre']) . "%'";                                              
        }                    

        if (!empty($data['filter_fecha'])) { 
            $sql .= " AND fecha_creacion LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";                                              
        }

        $sort_data = array(
            'titulo',
            'fecha_creacion'            
        );        

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY id_mensaje";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }        

        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $conexion = null;

        return $stmt->fetchAll(); 
}

public function getMailPredefinidosPorID($id_mensaje){
    $string_connection = "mysql:host=".DB_HOSTNAME_CONNECTUS.";dbname=".DB_DATABASE_CONNECTUS;
    $conexion = new PDO($string_connection,DB_USERNAME_CONNECTUS,DB_PASSWORD_CONNECTUS);


    $sql = "select * from mensaje
    where tipo='mail'
    And is_predefinido = 1
    and id_mensaje = ". $id_mensaje;
        
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $conexion = null;

    return $stmt->fetchAll(); 
}

public function getCountMailPredefinidos($id_empresa){
    $string_connection = "mysql:host=".DB_HOSTNAME_CONNECTUS.";dbname=".DB_DATABASE_CONNECTUS;
    $conexion = new PDO($string_connection,DB_USERNAME_CONNECTUS,DB_PASSWORD_CONNECTUS);

    $sql = "select COUNT(id_mensaje) as total from mensaje
            where tipo='mail'
            And is_predefinido = 1
            and id_empresa = ". $id_empresa;
  

    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    
    $conexion = null;
    $row = $stmt->fetch();        

    return $row['total'];  
}

public function addMail($titulo, $cuerpo, $remitente, $correo_remitente, $guardar, $usuario = '', $archivo = '') {
    $sql  = "INSERT INTO mensaje SET tipo = 'MAIL'";
    $sql .= ", titulo = '" . $titulo . "'";
    $sql .= ", cuerpo = '" . $cuerpo . "'";
    $sql .= ", is_predefinido = " . $guardar;
    $sql .= ", fecha_creacion = NOW()";
    $sql .= ", remitente = '" . $remitente . "'";
    $sql .= ", correo_remitente = '" . $correo_remitente . "'";

    $sql .= ", id_usuario = '" . $this->session->data['user_id'] . "'";
    $sql .= ", id_empresa = '" . $this->session->data['id_empresa'] . "'";


    if(isset($archivo)){
        if($archivo != ''){
            $sql .= ", archivo = '" . $archivo . "'";
        }
    }

    $result = $this->admDB->query($sql);
    $id_mail = $this->admDB->getLastId();

    return $id_mail;
}

public function updateMensaje($titulo, $cuerpo, $id_mensaje) {
    $sql = "UPDATE mensaje SET titulo = '" . $titulo . "', cuerpo = '" . $cuerpo . "' WHERE id_mensaje = " .$id_mensaje;
    $result = $this->admDB->query($sql);

    return $result;
}

public function deleteMensaje($id_mensaje){
    $sql = "UPDATE mensaje SET is_predefinido = 0 WHERE id_mensaje = " .$id_mensaje;
    $result = $this->admDB->query($sql);

    return $result;
}

}