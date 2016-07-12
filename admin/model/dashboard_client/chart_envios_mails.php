<?php
class ModelDashboardClientChartEnviosMails extends Model {
	
	public function getTotalOrdersByDay($id_empresa) {		

		$order_data = array();	


		$order_data = array();

		for ($i = 0; $i < 24; $i++) {
			$order_data[$i] = array(
				'hour'  => $i,
				'total' => 0
			);
		}	



		$query = $this->admDB->query("SELECT COUNT(*) AS total, HOUR(cuando_enviar) AS hour FROM detalle_envio AS D inner join envio as E
                on E.id_envio = D.id_envio                
                WHERE  E.tipo_mensaje = 'MAIL' and E.id_empresa = ". $id_empresa." AND E.tipo_mensaje = 'MAIL' AND DAY(E.cuando_enviar) = DAY(NOW()) GROUP BY HOUR(E.cuando_enviar) ORDER BY E.cuando_enviar ASC");

		foreach ($query->rows as $result) {
			$order_data[$result['hour']] = array(
				'hour'  => $result['hour'],
				'total' => $result['total']
			);
		}

		return $order_data;
	}

	public function getTotalOrdersByWeek($id_empresa) {		

		$order_data = array();

		$date_start = strtotime('-' . date('w') . ' days');

		for ($i = 0; $i < 7; $i++) {

			$order_data[$i] = array(
				'day'   => $i,
				'total' => 0
			);
		}

		$query = $this->admDB->query("SELECT COUNT(*) AS total, WEEKDAY(cuando_enviar) AS week FROM detalle_envio AS D inner join envio as E
                on E.id_envio = D.id_envio                
                WHERE  E.tipo_mensaje = 'MAIL' and E.id_empresa = ". $id_empresa." AND E.tipo_mensaje = 'MAIL' AND MONTH(E.cuando_enviar) = MONTH(NOW()) GROUP BY WEEKDAY(E.cuando_enviar) ORDER BY E.cuando_enviar ASC");

		foreach ($query->rows as $result) {
			$order_data[$result['week']] = array(
				'day'   => $result['week'],
				'total' => $result['total']
			);
		}

		return $order_data;
	}

	public function getTotalOrdersByMonth($id_empresa) {		


		$order_data = array();
		
		for ($i = 1; $i <= 31; $i++) {			

			$order_data[$i] = array(
				'day'   => $i,
				'total' => 0
			);
		}

		$query = $this->admDB->query("SELECT COUNT(*) AS total, DAY(cuando_enviar) AS day FROM detalle_envio AS D inner join envio as E
                on E.id_envio = D.id_envio                
                WHERE  E.tipo_mensaje = 'MAIL' and E.id_empresa = ". $id_empresa." AND E.tipo_mensaje = 'MAIL' AND MONTH(E.cuando_enviar) = MONTH(NOW()) GROUP BY DAY(E.cuando_enviar) ORDER BY E.cuando_enviar ASC");

		foreach ($query->rows as $result) {
			$order_data[$result['day']] = array(
				'day'   => $result['day'],
				'total' => $result['total']
			);
		}

		return $order_data;
	}

	public function getTotalOrdersByYear($id_empresa) {		

		$order_data = array();

		for ($i = 1; $i <= 12; $i++) {
			$order_data[$i] = array(
				'month' => $i,
				'total' => 0
			);
		}	

		$query = $this->admDB->query("SELECT COUNT(*) AS total, MONTH(cuando_enviar) AS month FROM detalle_envio AS D inner join envio as E
                on E.id_envio = D.id_envio                
                WHERE  E.tipo_mensaje = 'MAIL' and E.id_empresa = ". $id_empresa." AND E.tipo_mensaje = 'MAIL' AND YEAR(E.cuando_enviar) = YEAR(NOW()) GROUP BY MONTH(E.cuando_enviar) ORDER BY E.cuando_enviar ASC");

		foreach ($query->rows as $result) {
			$order_data[$result['month']] = array(
				'month' => $result['month'],
				'total' => $result['total']
			);
		}

		return $order_data;
	}
}