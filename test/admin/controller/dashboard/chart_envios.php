<?php
class ControllerDashboardChartEnvios extends Controller {
	public function index() {
		$this->load->language('dashboard/chart_envios');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_day'] = $this->language->get('text_day');
		$data['text_week'] = $this->language->get('text_week');
		$data['text_month'] = $this->language->get('text_month');
		$data['text_year'] = $this->language->get('text_year');
		$data['text_view'] = $this->language->get('text_view');

		$data['token'] = $this->session->data['token'];


		return $this->load->view('dashboard/chart_envios.tpl', $data);
	}

	public function chart() {
		$this->load->language('dashboard/chart_envios');

		$json = array();		

		$this->load->model('dashboard_client/chart_envios_mails');
		$this->load->model('dashboard_client/chart_envios_sms');

		$json['order'] = array();
		$json['customer'] = array();
		$json['xaxis'] = array();

		$json['order']['label'] = $this->language->get('text_order');
		$json['customer']['label'] = $this->language->get('text_customer');
		$json['order']['data'] = array();
		$json['customer']['data'] = array();

		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'day';
		}

		switch ($range) {
			default:
			case 'day':
				$results = $this->model_dashboard_client_chart_envios_mails->getTotalOrdersByDay($this->session->data['id_empresa']);

				foreach ($results as $value) {
					$json['order']['data'][] = array($value['hour'], $value['total']);
				}

				$results = $this->model_dashboard_client_chart_envios_sms->getTotalOrdersByDay($this->session->data['id_empresa']);

				foreach ($results as $value) {
					$json['customer']['data'][] = array($value['hour'], $value['total']);
				}

				for ($i = 0; $i < 24; $i++) {
					$json['xaxis'][] = array($i, $i);
				}
				break;
			case 'week':
				$results = $this->model_dashboard_client_chart_envios_mails->getTotalOrdersByWeek($this->session->data['id_empresa']);

				foreach ($results as $value) {
					$json['order']['data'][] = array($value['day'], $value['total']);
				}

				$results = $this->model_dashboard_client_chart_envios_sms->getTotalOrdersByWeek($this->session->data['id_empresa']);

				foreach ($results as $value) {
					$json['customer']['data'][] = array($value['day'], $value['total']);
				}

				$date_start = strtotime('-' . date('w') . ' days');

				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));

					$json['xaxis'][] = array(date('w', strtotime($date)), date('D', strtotime($date)));
				}
				break;
			case 'month':
				$results = $this->model_dashboard_client_chart_envios_mails->getTotalOrdersByMonth($this->session->data['id_empresa']);

				foreach ($results as $value) {
					$json['order']['data'][] = array($value['day'], $value['total']);
				}

				$results = $this->model_dashboard_client_chart_envios_sms->getTotalOrdersByMonth($this->session->data['id_empresa']);

				foreach ($results as $value) {
					$json['customer']['data'][] = array($value['day'], $value['total']);
				}

				for ($i = 1; $i <= date('t'); $i++) {					
					$json['xaxis'][] = array($i,$i);
				}
				break;
			case 'year':
				$results = $this->model_dashboard_client_chart_envios_mails->getTotalOrdersByYear($this->session->data['id_empresa']);

				foreach ($results as $value) {
					$json['order']['data'][] = array($value['month'], $value['total']); 
				}

				$results = $this->model_dashboard_client_chart_envios_sms->getTotalOrdersByYear($this->session->data['id_empresa']);

				foreach ($results as $value) {
					$json['customer']['data'][] = array($value['month'], $value['total']);
				}

				for ($i = 1; $i <= 12; $i++) {
					$json['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i)));
				}
				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}