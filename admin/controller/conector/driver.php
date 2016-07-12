<?php
	class ControllerConectorDriver extends Controller
	{

		public function getInfo()
		{
			$id_cliente = $_POST['cliente'];

			if(empty($id_cliente)){
				$data['error'] 	= "Error al obtener variable 'cliente'.";
				$data['estado'] = false;
			} else {

				$this->load->model('sale/customer');

				if(!$cliente = $this->model_sale_customer->getCustomer($id_cliente))
				{
					$data['error'] = "Error al cargar cliente";
					$data['estado'] = false;
				}else
				{

					$this->load->model('conector/conector');
					
					if($datos_cliente = $this->model_conector_conector->getConectoresCliente($id_cliente)){

						$data['cliente'] = $cliente['firstname'];
						$data['driver_mail'] = $datos_cliente['id_conector_mail'];
						$data['driver_sms'] = $datos_cliente['id_conector_sms'];
						$data['estado'] = true;
					}else{
						$data['error'] = "Error al cargar conectores";
						$data['estado'] = false;
					}
				}
			}
			echo json_encode($data, true);
		}

		public function getLista()
		{
			$driver = $_POST['driver'];

			if(empty($driver))
			{
				$data['error'] 	= "Error al obtener variable 'driver'.";
				$data['estado'] = false;
			} else
			{
				$this->load->model('conector/conector');
				
				if($data['conectores'] = $this->model_conector_conector->getConectores($driver)){

					$data['estado'] = true;
				}else{
					$data['error'] = "Error al cargar conectores";
					$data['estado'] = false;
				}
			}

			echo json_encode($data, true);
			return true;
		}


		public function updConector()
		{
			$cliente = $_POST['id_cliente'];
			$driver_mail = $_POST['driver_mail'];
			$driver_sms = $_POST['driver_sms'];

			if( empty($cliente) || empty($driver_mail) || empty($driver_sms) ){

				$data['error'] = "Error en las variables";
				$data['estado'] = false;
			}else
			{

				$this->load->model('conector/conector');

				if( $this->model_conector_conector->updDriverCliente($cliente, $driver_mail, $driver_sms))
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


	}