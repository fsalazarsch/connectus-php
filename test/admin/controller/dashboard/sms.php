<?php
class ControllerDashboardSms extends Controller {
	public function index() {
		$this->load->language('dashboard/sms');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = '        '.$this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		$this->load->model('dashboard_client/dashboard_client');
		//$total_envios = $this->model_dashboard_client_dashboard_client->getTotalSms($this->session->data['id_empresa']);

		$this->load->model('cuenta_corriente/cuenta_corriente');
		
		//total historico de creditos consumidos
		$total_envios = $this->model_cuenta_corriente_cuenta_corriente->creditosConsumidosSms($this->session->data['id_empresa']);
		
		//creditos disponibles
		//$total_envios = $this->model_cuenta_corriente_cuenta_corriente->creditosSmsRestantes($this->session->data['id_empresa']);
		//$data['total_creditos'] = $this->model_dashboard_client_dashboard_client->getSaldoEnviosSms($this->session->data['id_empresa']);
		
		$total_creditos = $this->model_cuenta_corriente_cuenta_corriente->creditosSmsRestantes($this->session->data['id_empresa']);


		$data['total'] = round($total_envios);
		$data['total_creditos'] = round($total_creditos);
		/*
		if ($total_envios > 1000000000000) {
			$data['total'] = round($total_envios / 1000000000000, 1) . 'T';
		} elseif ($total_envios > 1000000000) {
			$data['total'] = round($total_envios / 1000000000, 1) . 'B';
		} elseif ($total_envios > 1000000) {
			$data['total'] = round($total_envios / 1000000, 1) . 'M';
		} elseif ($total_envios > 1000) {
			$data['total'] = round($total_envios / 1000, 1) . 'K';
		} else {
			$data['total'] = round($total_envios);
		}

		if ($total_creditos > 1000000000000) {
			$data['total_creditos'] = round($total_creditos / 1000000000000, 1) . 'T';
		} elseif ($total_creditos > 1000000000) {
			$data['total_creditos'] = round($total_creditos / 1000000000, 1) . 'B';
		} elseif ($total_creditos > 1000000) {
			$data['total_creditos'] = round($total_creditos / 1000000, 1) . 'M';
		} elseif ($total_creditos > 1000) {
			$data['total_creditos'] = round($total_creditos / 1000, 1) . 'K';
		} else {
			$data['total_creditos'] = round($total_creditos);
		}*/


		//22-12-2015 problemas.- lineas comentadas puesto que porcentaje no se muestra en tpl
		/*if ($total_envios != 0) {			
			$data['percentage'] = round((100 * $total_envios) / $data['total_creditos']);	
		}else{
			$data['percentage'] = 0;	
		}*/
		

		$data['comprar'] = $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL');

		return $this->load->view('dashboard/sms.tpl', $data);
	}
}