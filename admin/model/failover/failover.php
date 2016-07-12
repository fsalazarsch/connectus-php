<?php

	class ModelFailoverFailover extends Model
	{


		public function getFailoverCliente($cliente)
		{
			$sql = "SELECT *
					FROM failover 
					WHERE id_cliente = ".$cliente.";";

			$result = $this->admDB->query($sql);
        	return $result->row;
		}

		public function updFailover($cliente, $failover)
		{

			if($this->getFailoverCliente($cliente))
			{
				#UPDATE
				$sql = "UPDATE failover SET failover = ".$failover." WHERE id_cliente = ".$cliente;

			}else{
				#INSERT
				$sql = "INSERT INTO failover (id_cliente, failover) VALUES (".$cliente.", ".$failover.");";
			}

			if($result = $this->admDB->query($sql))
			{
				return true;
			}else{
				return false;
			}

		}

	}