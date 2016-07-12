<?php
class ModelDashboardClientDashboardClient extends Model{	

	public function getTotalSms($id_empresa){

		$sql1 = "SELECT id_cuenta_corriente as id FROM cuenta_corriente where id_empresa = ".$id_empresa;		
		
		$result1 = $this->admDB->query($sql1);

		if ($result1->num_rows > 0) {
			$sql = "SELECT SUM(sms_consumidos) as total_sms FROM consumo where id_cuenta_corriente = '".$result1->row['id']."'";
		
			$result = $this->admDB->query($sql);

			return $result->row['total_sms'];
		}else{
			return 0;
		}
		
	}

	public function getTotalMail($id_empresa){
		
		$sql1 = "SELECT id_cuenta_corriente as id FROM cuenta_corriente where id_empresa = ".$id_empresa;		
		
		$result1 = $this->admDB->query($sql1);

		if ($result1->num_rows > 0) {
			$sql = "SELECT SUM(mail_consumidos) as total_mail FROM consumo where id_cuenta_corriente = '".$result1->row['id']."'";
			
			$result = $this->admDB->query($sql);

			return $result->row['total_mail'];
		}else{
			return 0;
		}

	}

	public function getTotalListas($id_empresa){
				
		$sql = "select count(id_lista) as total 
				from lista_contacto where borrado = 0 and id_empresa =".$id_empresa;

		$result = $this->admDB->query($sql);
		if ($result->num_rows > 0) {
			return $result->row['total'];
		}else{
			return 0;
		}
	}

	public function getTotalContactos($id_empresa){
		
		$sql = "select count(C.id_contacto) as total
				from contacto as C 
				inner join lista_contacto as L
				on C.id_lista = L.id_lista
				where C.borrado = 0 and  L.id_empresa = ".$id_empresa;
		
		$result = $this->admDB->query($sql);

		$result = $this->admDB->query($sql);
		
		if ($result->num_rows > 0) {
			return $result->row['total'];
		}else{
			return 0;
		}

		return $result->row['total'];
	}
	
	public function getSaldoEnviosSms($id_empresa)
	{
		$cuenta_sql = "SELECT id_cuenta_corriente as cuenta FROM cuenta_corriente WHERE id_empresa = " . $id_empresa;
		$result = $this->admDB->query($cuenta_sql);
		$cuenta = $result->row['cuenta'];

		$sql = "select sum(cantidad_sms) as 'SMS' from compra where id_cuenta_corriente = " . $cuenta;
		
		$result = $this->admDB->query($sql);
		if($result->num_rows > 0){
			$aux = $result->row['SMS'];
			if (isset($aux)) {
				return $aux;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		
	}

	public function getSaldoEnviosMail($id_empresa)
	{
		$cuenta_sql = "SELECT id_cuenta_corriente as cuenta FROM cuenta_corriente WHERE id_empresa = " . $id_empresa;
		$result = $this->admDB->query($cuenta_sql);
		$cuenta = $result->row['cuenta'];

		$sql = "select sum(cantidad_mail) as 'MAIL' from compra where id_cuenta_corriente = " . $cuenta;
		
		$result = $this->admDB->query($sql);
		if($result->num_rows > 0){
			$aux = $result->row['MAIL'];
			if (isset($aux)) {
				return $aux;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

}