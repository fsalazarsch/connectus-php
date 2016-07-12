<?php 
class ControllerPaymentWebpayOCCL extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/webpay_occl');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('webpay_occl', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

	
		$data['mod_title'] = $this->language->get('mod_title');
		$data['text_edit'] = $this->language->get('text_edit');


		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_successful'] = $this->language->get('text_successful');
		$data['text_declined'] = $this->language->get('text_declined');
		$data['text_off'] = $this->language->get('text_off');

		$data['entry_kcc_url'] = $this->language->get('entry_kcc_url');
		$data['help_kcc_url'] = $this->language->get('help_kcc_url');

		$data['entry_kcc_path'] = $this->language->get('entry_kcc_path');
		$data['entry_callback'] = $this->language->get('entry_callback');
		$data['entry_total'] = $this->language->get('entry_total');	
		$data['help_entry_kcc_path'] = $this->language->get('help_entry_kcc_path');
		$data['help_entry_callback'] = $this->language->get('help_entry_callback');
		$data['help_entry_total'] = $this->language->get('help_entry_total');	

		$data['entry_order_status'] = $this->language->get('entry_order_status');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['kcc_url'])) {
			$data['error_kcc_url'] = $this->error['kcc_url'];
		} else {
			$data['error_kcc_url'] = '';
		}

 		if (isset($this->error['kcc_path'])) {
			$data['error_kcc_path'] = $this->error['kcc_path'];
		} else {
			$data['error_kcc_path'] = '';
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/webpay_occl', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
				
		$data['action'] = $this->url->link('payment/webpay_occl', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['webpay_occl_kcc_url'])) {
			$data['webpay_occl_kcc_url'] = $this->request->post['webpay_occl_kcc_url'];
		} elseif($this->config->get('webpay_occl_kcc_url') != '') {
			$data['webpay_occl_kcc_url'] = $this->config->get('webpay_occl_kcc_url');
		} else {
			$data['webpay_occl_kcc_url'] = HTTP_CATALOG . 'cgi-bin/';
		}

		if (isset($this->request->post['webpay_occl_kcc_path'])) {
			$data['webpay_occl_kcc_path'] = $this->request->post['webpay_occl_kcc_path'];
		} elseif($this->config->get('webpay_occl_kcc_path') != '') {
			$data['webpay_occl_kcc_path'] = $this->config->get('webpay_occl_kcc_path');
		} else {
			$data['webpay_occl_kcc_path'] = preg_replace("/\/catalog\//i", '/cgi-bin/', DIR_CATALOG, 1);
		}

		$data['callback'] = HTTP_CATALOG . 'index.php?route=payment/webpay_occl/callback&action=check';

		if (isset($this->request->post['webpay_occl_total'])) {
			$data['webpay_occl_total'] = $this->request->post['webpay_occl_total'];
		} else {
			$data['webpay_occl_total'] = $this->config->get('webpay_occl_total'); 
		} 
				
		if (isset($this->request->post['webpay_occl_order_status_id'])) {
			$data['webpay_occl_order_status_id'] = $this->request->post['webpay_occl_order_status_id'];
		} else {
			$data['webpay_occl_order_status_id'] = $this->config->get('webpay_occl_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['webpay_occl_geo_zone_id'])) {
			$data['webpay_occl_geo_zone_id'] = $this->request->post['webpay_occl_geo_zone_id'];
		} else {
			$data['webpay_occl_geo_zone_id'] = $this->config->get('webpay_occl_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['webpay_occl_status'])) {
			$data['webpay_occl_status'] = $this->request->post['webpay_occl_status'];
		} else {
			$data['webpay_occl_status'] = $this->config->get('webpay_occl_status');
		}

		if (isset($this->request->post['webpay_occl_sort_order'])) {
			$data['webpay_occl_sort_order'] = $this->request->post['webpay_occl_sort_order'];
		} else {
			$data['webpay_occl_sort_order'] = $this->config->get('webpay_occl_sort_order');
		}

		$this->template = 'payment/webpay_occl.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/webpay_occl.tpl', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/webpay_occl')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['webpay_occl_kcc_url']) {
			$this->error['kcc_url'] = $this->language->get('error_kcc_url');
		}

		if (!$this->request->post['webpay_occl_kcc_path']) {
			$this->error['kcc_path'] = $this->language->get('error_kcc_path');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function install() {
		 $this->load->model('payment/webpay_occl');
		 $this->model_payment_webpay_occl->createTable();
	}
	
	public function uninstall() {
        $this->load->model('payment/webpay_occl');
        $this->model_payment_webpay_occl->deleteTable();
    }

}
?>