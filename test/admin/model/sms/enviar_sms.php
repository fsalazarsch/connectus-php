<?php
class ModelSmsEnviarSms extends Model {

	public function addSmsEnvioProgramado($estado ,$remitente, $id_mensaje, $tipo_envio, $tipo_mensaje, $nombre_envio,$id_usuario ,$fecha_ejecucion ,$datos_envio_programado, $id_lista = '', $id_consumo){     
	  
		$sql =  "INSERT INTO envio SET";
		$sql .= " estado = '". $estado ."'";
		$sql .= ",remitente = '". $remitente ."'";    
		$sql .=',id_mensaje =' . $id_mensaje;   
		$sql .=",tipo_envio = '" . $tipo_envio . "'";
		$sql .=",tipo_mensaje = '" . $tipo_mensaje . "'";

		if ($fecha_ejecucion == '') {
			$sql .=",cuando_enviar = 	NOW()";
		}else{
			$sql .=",cuando_enviar = '" . $fecha_ejecucion . "'";
		}
				

        $sql .= ", id_usuario = '" . $this->session->data['user_id'] . "'";
        $sql .= ", id_empresa = '" . $this->session->data['id_empresa'] . "'";
		$sql .=",nombre_envio = '" . $nombre_envio . "'";    
		$sql .=",datos_envio_programado = '" . $datos_envio_programado . "'"; 

		if ($id_lista != '') {
			$sql .= ",id_lista = " . $id_lista;
		}

		if(isset($id_consumo)){
			$sql .= ", id_consumo = ".$id_consumo;
		}

		$this->admDB->query($sql);    
		$id_envio = $this->admDB->getLastId();         

		return $id_envio;     
	}

	public function addSms($titulo, $cuerpo, $remitente, $guardar, $usuario = '', $nombre_mensaje_predefinido = '') {    
	    $sql  = "INSERT INTO mensaje SET tipo = 'SMS'";	    
	    $sql .= ", cuerpo = '" . $cuerpo . "'";
	    $sql .= ", is_predefinido = " . $guardar;
	    $sql .= ", fecha_creacion = NOW()";
        $sql .= ", id_usuario = '" . $this->session->data['user_id'] . "'";
        $sql .= ", id_empresa = '" . $this->session->data['id_empresa'] . "'";
	    $sql .= ", remitente = '" . $remitente . "'";	    
	    	  	
	  	if($guardar == 1){
	  		$sql .= ", titulo = '" . $nombre_mensaje_predefinido . "'";
	  	}else{
	  		$sql .= ", titulo = '" . $titulo . "'";
	  	}
	  	


	    $this->admDB->query($sql);
	    
	    $id_mail = $this->admDB->getLastId();

	    return $id_mail;
	}


	public function getEquivalencia($id_parametro)
	{
		$parametro = "SELECT valor1, valor2 FROM parametro WHERE id_parametro = '".$id_parametro."'";
		$result = $this->admDB->query($parametro);

		return $result->row;
	}

	public function checkCreditosDisponibles($id_empresa, $requeridos){

	    $select_cuenta = "SELECT id_cuenta_corriente AS cuenta FROM cuenta_corriente WHERE id_empresa = " . $id_empresa;
	    $result_cuenta = $this->admDB->query($select_cuenta);


	    $sql_disponibles = "SELECT (saldo_sms - consumidos_sms) AS disponibles FROM cuenta_corriente WHERE id_cuenta_corriente = " . $result_cuenta->row['cuenta'];
	    $result_disponibles = $this->admDB->query($sql_disponibles);

	    return $result_disponibles->row['disponibles'] >= $requeridos ? true : false;
	 }

	public function getdetalles()
	{
		$parametro = "SELECT * FROM envio where estado = 'pendiente'";
		$result = $this->admDB->query($parametro);

		return $result;
	}


	public function getTotalSms($id_empresa){

		$sqlSaldo = "select saldo_sms 
				from cuenta_corriente 
				where id_empresa = ".$id_empresa;
		
		$resultSaldo = $this->admDB->query($sqlSaldo);

		$sql1 = "SELECT id_cuenta_corriente as id FROM cuenta_corriente where id_empresa = ".$id_empresa;		

		$result1 = $this->admDB->query($sql1);


		$sql = "SELECT SUM(sms_consumidos) as total_sms FROM consumo where id_cuenta_corriente = '".$result1->row['id']."'";

		$result = $this->admDB->query($sql);		

		$diferencia = $resultSaldo->row['saldo_sms'] - $result->row['total_sms'];

		return $diferencia;
	}
	

	public function getConector($id_empresa)
	{
		$sql  = "SELECT id_conector_sms FROM cuenta_corriente WHERE id_empresa = ".$id_empresa;

		$result = $this->admDB->query($sql);

		return $result->row['id_conector_sms'];
	}

}