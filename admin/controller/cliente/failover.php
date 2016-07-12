<?php

	class ControllerClienteFailover extends Controller
	{

		public function updFailover()
		{
			$cliente 	= $_POST['id_cliente'];
			$failover 	= $_POST['failover'];

			if( empty($cliente) || empty($failover) ){

				$data['error'] = "Error en las variables";
				$data['estado'] = false;
			}else
			{

				$this->load->model('failover/failover');

				$data['failover'] = $failover;

				if( $this->model_failover_failover->updFailover($cliente, $failover))
				{
					$data['estado'] = true;
				}else
				{
					$data['error'] = "Error al actualizar drivers";
					$data['estado'] = false;
				}
			}

			echo json_encode($data, true);

			return true;

		}

		public function getInfo()
		{
			$id_cliente = $_POST['cliente'];

			if(empty($id_cliente)){
				$data['error'] 	= "Error al obtener variable 'cliente'.";
				$data['estado'] = false;
			} else {

				$this->load->model('failover/failover');

				if(!$failover = $this->model_failover_failover->getFailoverCliente($id_cliente))
				{
					$data['estado'] = false;
				}else
				{

					if($failover['failover']){
						$data['estado'] = true;
					}else{
						$data['estado'] = false;
					}
				}
			}

			echo json_encode($data, true);
		}
	}

