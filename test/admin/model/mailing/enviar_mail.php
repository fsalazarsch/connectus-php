<?php
class ModelMailingEnviarMail extends Model {

  public function addMail($asunto, $cuerpo, $remitente, $correo_remitente, $guardar, $usuario = '', $archivo = '', $nombre_predef = null) {    

    if(empty($nombre_predef)){
        $nombre_predef = $asunto;
    }

    $sql  = "INSERT INTO mensaje SET tipo = 'MAIL'";
    $sql .= ", titulo = '" . $asunto . "'";
    $sql .= ", cuerpo = '" . $this->admDB->escape($cuerpo) . "'";
    $sql .= ", is_predefinido = " . $guardar;
    $sql .= ", fecha_creacion = NOW()";
    $sql .= ", id_usuario = '" . $this->session->data['user_id'] . "'";
    $sql .= ", id_empresa = '" . $this->session->data['id_empresa'] . "'";
    $sql .= ", remitente = '" . $remitente . "'";
    $sql .= ", correo_remitente = '" . $correo_remitente . "'";  
    $sql .= ", nombre_predefinido = '" . $nombre_predef . "'";  

    

    if(isset($archivo)){
      if($archivo != ''){
        $sql .= ", archivo = '" . $archivo . "'";
      }
    }     

   // echo $sql;

    $this->admDB->query($sql);
    
    $id_mail = $this->admDB->getLastId();

    return $id_mail;
  }

  public function getConector($id_empresa) {    
    $sql  = "SELECT id_conector_mail FROM cuenta_corriente WHERE id_empresa = ".$id_empresa;

    $result = $this->admDB->query($sql);

    return $result->row['id_conector_mail'];
  }


  public function getdetalles()
  {
    $parametro = "SELECT * FROM envio where estado = 'pendiente'";
    //$parametro = "SELECT * FROM detalle_envio where id_envio = 409";
    $result = $this->admDB->query($parametro);

    return $result;
  }

  public function checkCreditosDisponibles($id_empresa, $requeridos){              

    $sql = "SELECT id_cuenta_corriente AS cuenta FROM cuenta_corriente WHERE id_empresa = " . $id_empresa;
    $result = $this->admDB->query($sql);

    $sql = "SELECT (saldo_mail - consumidos_mail) AS disponibles FROM cuenta_corriente WHERE id_cuenta_corriente = " . $result->row['cuenta'];
    $result = $this->admDB->query($sql);

    return $result->row['disponibles'] >= $requeridos ? true : false;
  }

    public function checkDesincrito($destinatario, $id_empresa)
    {

        $sql = "SELECT * FROM lista_contacto WHERE id_empresa = ".$id_empresa;
        $result = $this->admDB->query($sql);


        if(count($result->num_rows) > 0)
        {
            foreach ($result->rows as $lista) {
                $listas[] = $lista['id_lista'];
            }
        
            $listas = implode(',', $listas);

            $sql="SELECT * 
                    FROM contacto 
                    WHERE email = '".$destinatario."'
                    AND  id_lista in (".$listas."); ";

            $result = $this->admDB->query($sql);

            if(count($result->num_rows) > 0){

                foreach ($result->rows as $listac) {
                    $listasc[] = $listac['id_contacto'];
                }

                $listasc = implode(',', $listasc);

                $sql="SELECT * FROM desinscrito WHERE id_contacto in(".$listasc.");";

                $result = $this->admDB->query($sql);

                if($result->num_rows > 0){
                    # si existe en desincrito de la empresa
                    return true;
                }else{
                    # no existe en desinscritos
                    return false;
                }
            }else{
                # no existe contacto en las listas de la empresa
                return false;
            }
        }else{
            # empresa sin listas de contacto
            return false;
        }
    }

  public function addMailEnvioProgramado($estado ,$remitente,$correo_remitente,$id_lista, $id_mensaje,$tipo_envio,$tipo_mensaje,$nombre_envio, $correos_malos,$id_usuario,$fecha_ejecucion,$datos_envio_programado, $id_consumo){     
      
    $sql =  "INSERT INTO envio SET";
    $sql .= " estado = '". $estado ."'";
    $sql .= ",remitente = '". $remitente ."'";
    $sql .= ",correo_remitente = '" . $correo_remitente ."'";
    if ($id_lista != '') {
      $sql .= ",id_lista = " . $id_lista;
    }   
    $sql .=',id_mensaje =' . $id_mensaje;   
    $sql .=",tipo_envio = '" . $tipo_envio . "'";
    $sql .=",tipo_mensaje = '" . $tipo_mensaje . "'";
    
    if ($fecha_ejecucion == '') {
      $sql .=",cuando_enviar =  NOW()";
    }else{
      $sql .=",cuando_enviar = '" . $fecha_ejecucion . "'";
    }
    
    $sql .= ", id_usuario = '" . $this->session->data['user_id'] . "'";
    $sql .= ", id_empresa = '" . $this->session->data['id_empresa'] . "'";
    $sql .=",nombre_envio = '" . $nombre_envio . "'";
    $sql .=",correos_malos = '" . $correos_malos . "'";       
    $sql .=",datos_envio_programado = '" . str_replace("'", "", $datos_envio_programado) . "'"; 

    if (isset($id_consumo)){
        $sql .=", id_consumo = ".$id_consumo;   
    }

   // echo $sql;

    $this->admDB->query($sql);    
    $id_envio = $this->admDB->getLastId();         

    return $id_envio;     
  } 


  public function getEquivalencia($id_parametro)
  {
    $parametro = "SELECT valor1, valor2 FROM parametro WHERE id_parametro = '".$id_parametro."'";
    $result = $this->admDB->query($parametro); 

    return $result->row;
  }


  public function getTotalMail($id_empresa){

    $sqlSaldo = "select saldo_mail 
        from cuenta_corriente 
        where id_empresa = ".$id_empresa;
    
    $resultSaldo = $this->admDB->query($sqlSaldo);

    $sql1 = "SELECT id_cuenta_corriente as id FROM cuenta_corriente where id_empresa = ".$id_empresa;   

    $result1 = $this->admDB->query($sql1);


    $sql = "SELECT SUM(mail_consumidos) as total_mail FROM consumo where id_cuenta_corriente = '".$result1->row['id']."'";

    $result = $this->admDB->query($sql);    

    $diferencia = $resultSaldo->row['saldo_mail'] - $result->row['total_mail'];

    return $diferencia;
  }

    public function addPredefinido($tipo, $titulo, $cuerpo, $usuario, $remitente = '', $correo_remitente = '', $archivo = '' ) {
    $sql  = "INSERT INTO mensaje SET tipo = '". $tipo ."'";
    $sql .= ", titulo ='" . $titulo . "'";
    $sql .= ", cuerpo = '" . $cuerpo . "'";
    $sql .= ", id_usuario = '" . $this->session->data['user_id'] . "'";
    $sql .= ", id_empresa = '" . $this->session->data['id_empresa'] . "'";
    $sql .= ", is_predefinido = 1";

    if(isset($remitente)){
        if($remitente != ''){
            $sql .= ", remitente = '" . $remitente . "'";
        }
    }

    if(isset($correo_remitente)){
        if($correo_remitente != ''){
            $sql .= ", correo_remitente = '" . $correo_remitente . "'";
        }
    }

    if(isset($archivo)){
        if($archivo != ''){
            $sql .= ", archivo = '" . $archivo . "'";
        }
    }
    
        $result = $this->admDB->query($sql);
        $id_predefinido_insertado = $this->admDB->getLastId();

        return $id_predefinido_insertado;
    }

    public function addSMS( $cuerpo, $remitente, $guardar, $usuario = '') {
    $sql  = "INSERT INTO mensaje SET tipo = 'SMS'";
    $sql .= ", cuerpo = '" . $cuerpo . "'";
    $sql .= ", is_predefinido = " . $guardar;
    $sql .= ", remitente = '" . $remitente . "'";
    $sql .= ", id_usuario = '" . $this->session->data['user_id'] . "'";
    $sql .= ", id_empresa = '" . $this->session->data['id_empresa'] . "'";


        $result = $this->admDB->query($sql);
        $id_sms = $this->admDB->getLastId();

        return $id_sms;
    }

    public function updateMensaje($titulo, $cuerpo, $id_mensaje) {
        $sql = "UPDATE mensaje SET titulo = '" . $titulo . "', cuerpo = '" . $cuerpo . "' WHERE id_mensaje = " .$id_mensaje;
        $result = $this->admDB->query($sql);

        return $result;
    }

    public function deleteMensaje($id_mensaje){
        $sql = "UPDATE mensaje SET borrado = 1 WHERE id_mensaje = " .$id_mensaje;
        $result = $this->admDB->query($sql);

        return $result;
    }

    public function historialEnviosMail($id_usuario, $tipo){  
        $result = array();

        if ($tipo == 'unico') {
            $result = $this->getEnviosUnico($id_usuario);
        }elseif ($tipo == 'masivo') {
            $result = $this->getEnviosMasivos($id_usuario);
        }elseif ('API') {
            $result = $this->getEnviosAPI($id_usuario);
        }

        return $result;
    }

    public function getEnviosMasivos($id_usuario){
        $sql = "SELECT * FROM envio WHERE tipo_envio = 'masivo' AND id_usuario = " . $id_usuario ;
        $result = $this->admDB->query($sql);

        return $result->rows;
    }

    public function getEnviosUnico($id_usuario){
        $sql = "SELECT * FROM envio WHERE tipo_envio = 'unico' AND id_usuario = " . $id_usuario;
        $result = $this->admDB->query($sql);

        return $result->rows;
    }

    public function getEnviosAPI($id_usuario){
        $sql = "SELECT * FROM envio WHERE tipo_envio = 'API' AND id_usuario = " . $id_usuario;
        $result = $this->admDB->query($sql);

        return $result->rows;
    }

    public function getCantidadMasivos($id_usuario){
        $result = $this->admDB->query("SELECT count(id_envio) as total FROM envio WHERE id_usuario = " . $id_usuario . " AND tipo_envio = 'masivo'" );
        $result->row['total'];
    }
    
}