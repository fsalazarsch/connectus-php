<?php
class ControllerDashboardContactos extends Controller {
	public function index() {
		$this->load->language('dashboard/contactos');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = '        '.$this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		$this->load->model('dashboard_client/dashboard_client');

		$total_listas = $this->model_dashboard_client_dashboard_client->getTotalListas($this->session->data['id_empresa']);
		$total_contactos = $this->model_dashboard_client_dashboard_client->getTotalContactos($this->session->data['id_empresa']);		
					
		if ($total_listas > 1000000000000) {
			$data['total_listas'] = round($total_listas / 1000000000000, 1) . 'T';
		} elseif ($total_listas > 1000000000) {
			$data['total_listas'] = round($total_listas / 1000000000, 1) . 'B';
		} elseif ($total_listas > 1000000) {
			$data['total_listas'] = round($total_listas / 1000000, 1) . 'M';
		} elseif ($total_listas > 1000) {
			$data['total_listas'] = round($total_listas / 1000, 1) . 'K';
		} else {
			$data['total_listas'] = round($total_listas);
		}

		if ($total_contactos > 1000000000000) {
			$data['total_contactos'] = round($total_contactos / 1000000000000, 1) . 'T';
		} elseif ($total_contactos > 1000000000) {
			$data['total_contactos'] = round($total_contactos / 1000000000, 1) . 'B';
		} elseif ($total_contactos > 1000000) {
			$data['total_contactos'] = round($total_contactos / 1000000, 1) . 'M';
		} elseif ($total_contactos > 1000) {
			$data['total_contactos'] = round($total_contactos / 1000, 1) . 'K';
		} else {
			$data['total_contactos'] = round($total_contactos);
		}

		$data['listas'] = $this->url->link('contactos/mis_listas', 'token=' . $this->session->data['token'], 'SSL');

		return $this->load->view('dashboard/contactos.tpl', $data);
	}
}