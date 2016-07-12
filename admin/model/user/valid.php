<?php
	
	class ModelUserValid extends Model
	{
		public $name;

		public function setName($username)
		{
			$this->name = $username;
		}

		public function isValid()
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->name ."'";
			$result = $this->db->query($sql);
			$usuario = $result->row;

			if($usuario['user_group_id'] == 1)
			{	// user admin
				return true;
				
			} else {

				$sql = "SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . $usuario['id_empresa'];
				$result = $this->db->query($sql);
				$cliente = $result->row;

				if($cliente['token'] == '' || is_null($cliente['token'])){
					// si el campo token está vacío el correo del cliente se encuentra confirmado
					return true;
				}else{
					// si existe algún token significa que no está validado
					return false;
				}
			}

			
		}
	}


