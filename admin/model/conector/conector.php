<?php

	class ModelConectorConector extends Model
	{

		public function getConectores($driver)
		{
			$sql = "SELECT id_conector, glosa, ip_servidor FROM conector WHERE tipo = '".$driver."';";
			$result = $this->admDB->query($sql);
        	return $result->rows;
		}

		public function getConectoresCliente($cliente)
		{
			$sql = "SELECT id_conector_sms, id_conector_mail
					FROM cuenta_corriente 
					WHERE id_empresa= ".$cliente.";";

			$result = $this->admDB->query($sql);
        	return $result->row;
		}

		public function updDriverCliente($cliente, $mail, $sms)
		{

			$sql = "UPDATE cuenta_corriente SET
					 	id_conector_mail = ".$mail.",
					 	id_conector_sms = ".$sms."
					WHERE id_empresa= ".$cliente.";";

			if($result = $this->admDB->query($sql))
			{
				return true;
			}else{
				return false;
			}

		}

	}