	<?php
	class ControllerPaymentWebpayOCCL extends Controller {
		public function index() {
			$this->load->language('payment/webpay_occl');
			$data['button_confirm'] = $this->language->get('button_confirm');

			$this->load->model('checkout/order');

			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			$data['action'] = $this->config->get('webpay_occl_kcc_url') . 'tbk_bp_pago.cgi';

			$data['tbk_tipo_transaccion'] = 'TR_NORMAL';
			$tbk_monto_explode = explode('.', $order_info['total']);
			$data['tbk_monto'] = $tbk_monto_explode[0] . '00';
			$data['tbk_orden_compra'] = $order_info['order_id'];
			$data['tbk_id_sesion'] = date("Ymdhis");
			$data['tbk_url_fracaso'] = $this->url->link('payment/webpay_occl/callback&action=failure', '', 'SSL');
			$data['tbk_url_exito'] = $this->url->link('payment/webpay_occl/callback&action=success', '', 'SSL');
	
	//		$data['tbk_monto_cuota'] = 0;
	//		$data['tbk_numero_cuota'] = 0;

			$tbk_file = fopen(DIR_LOGS . 'TBK' . $data['tbk_id_sesion'] . '.log', 'w+');
			fwrite ($tbk_file, $tbk_monto_explode[0].'00;'.$order_info['order_id']);
			fclose($tbk_file);

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/webpay_occl.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/payment/webpay_occl.tpl', $data);
			} else {
				return $this->load->view('default/template/payment/webpay_occl.tpl', $data);
			}

		}


	public function callback() {
		$this->load->language('payment/webpay_occl');
		$data = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$action = htmlentities($this->request->get['action']);
			
			if ($action != 'check') {
				if (!$this->request->server['HTTPS']) {
					$data['base'] = $this->config->get('config_url');
				} else {
					$data['base'] = $this->config->get('config_ssl');
				}
				
				$data['title'] = $this->language->get('title');
				$data['language'] = $this->language->get('code');
				$data['direction'] = $this->language->get('direction');
				$data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
				$data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
				$data['text_response'] = $this->language->get('text_response');
				$data['text_success'] = $this->language->get('text_success');
				$data['text_failure'] = $this->language->get('text_failure');
				$data['text_failure_wait'] = $this->language->get('text_failure_wait');
			}
			
			if ($action == 'check') {
				
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($this->request->post['TBK_ORDEN_COMPRA']);
				
				$fp = tmpfile();
				while(list($key, $val) = each($this->request->post)) {
					fwrite($fp, $key .'='. $val .'&');
				}
				$meta_data = stream_get_meta_data($fp);
				$filename = $meta_data['uri'];
				
				if ($this->request->post['TBK_RESPUESTA'] == "0") {
					$ok = true;
				} else {
					$ok = false;
				}
				
				// validacion de monto y orden de compra
				// debe ser multiplicado por 100 para coincidir lo informado por transbank
				$total = $order_info['total']*100;
				if ($this->request->post['TBK_MONTO'] == $total && $this->request->post['TBK_ORDEN_COMPRA'] == $order_info['order_id'] && $ok == true) {
					$ok = true;
				} else {
					$ok = false;
				}
				
				// validacion MAC
				$tbk_check_mac = $this->config->get('webpay_checkmac_path');
				$cmdline = sprintf('%s %s', $tbk_check_mac, $filename);
				if ($ok == true){
					exec($cmdline, $result, $retint);
					
					if ($result[0] == 'CORRECTO') {
						$ok = true;
					} else {
						$ok = false;
					}
				}
				
				$this->load->model('checkout/order');
				if ($ok == true) {
					$status = 'ACEPTADO';
					// pago exitoso
					$this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('webpay_order_status_id'), $status, false);
				} else {
					$status = 'RECHAZADO';
					// pago no realizado
					$this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('config_order_status_id'), $status, false);
				}
				
				$this->load->model('payment/webpay_occl');
				$this->model_payment_webpay->insertTransaction($this->request->post, $status);
				$data['status'] = $status;
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/webpay_occl_callback.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/webpay_occl_callback.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/payment/webpay_occl_callback.tpl', $data));
				}
			}
			
			if ($action == 'success') {
				$data['continue'] = $this->url->link('checkout/success');
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/webpay_occl_success.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/webpay_occl_success.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/payment/webpay_occl_success.tpl', $data));
				}
			} 
			
			if ($action == 'failure') {
				$data['continue'] = $this->url->link('checkout/cart');
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/webpay_occl_failure.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/webpay_occl_failure.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/payment/webpay_occl_failure.tpl', $data));
				}	
			}	
		}
	}
}/*	public function failure() {
			
			$this->load->language('payment/webpay_occl');
			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$data['base'] = $this->config->get('config_url');
			} else {
				$data['base'] = $this->config->get('config_ssl');
			}
		
			$data['language'] = $this->language->get('code');
			$data['direction'] = $this->language->get('direction');

			$data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			$data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			$data['text_response'] = $this->language->get('text_response');
			$data['text_failure'] = $this->language->get('text_failure');
			$data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/cart', '', 'SSL'));

			$data['continue'] = $this->url->link('checkout/cart');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/webpay_occl_failure.tpl')) {
				$this->load->view($this->config->get('config_template') . '/template/payment/webpay_occl_failure.tpl', $data);
			} else {
				$this->load->view('default/template/payment/webpay_occl_failure.tpl', $data);
			}

			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/webpay_occl_failure.tpl', $data));
		}

		public function success() {
			$this->language->load('payment/webpay_occl');

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$data['base'] = $this->config->get('config_url');
			} else {
				$data['base'] = $this->config->get('config_ssl');
			}
		
			$data['language'] = $this->language->get('code');
			$data['direction'] = $this->language->get('direction');

			$data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			$data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			$data['text_response'] = $this->language->get('text_response');
			$data['text_success'] = $this->language->get('text_success');
			$data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success', '', 'SSL'));

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('checkout/success');

			$data['tbk_orden_compra'] = 0;
			$data['tbk_tipo_transaccion'] = 0;
			$data['tbk_respuesta'] = 0;
			$data['tbk_monto'] = 0;
			$data['tbk_codigo_autorizacion'] = 0;
			$data['tbk_final_numero_tarjeta'] = '************0000';
			$data['tbk_fecha_contable'] = '00-00';
			$data['tbk_fecha_transaccion'] = '00-00';
			$data['tbk_hora_transaccion'] = '00:00:00';
			$data['tbk_id_transaccion'] = 0;
			$data['tbk_tipo_pago'] = 'XX';
			$data['tbk_numero_cuotas'] = '00';
			$data['tbk_mac'] = 0;

			if (isset($this->request->post['TBK_ID_SESION']) && isset($this->request->post['TBK_ORDEN_COMPRA'])) {
				$tbk_cache = fopen(DIR_CACHE . 'TBK' . $this->request->post['TBK_ID_SESION'] . '.txt', 'r');
				$tbk_cache_string = fgets($tbk_cache);
				fclose($tbk_cache);

				$tbk_details = explode('&', $tbk_cache_string);

				$tbk_orden_compra = explode('=', $tbk_details[0]);
				$tbk_tipo_transaccion = explode('=', $tbk_details[1]);
				$tbk_respuesta = explode('=', $tbk_details[2]);
				$tbk_monto = explode('=', $tbk_details[3]);
				$tbk_codigo_autorizacion = explode('=', $tbk_details[4]);
				$tbk_final_numero_tarjeta = explode('=', $tbk_details[5]);
				$tbk_fecha_contable = explode('=', $tbk_details[6]);
				$tbk_fecha_transaccion = explode('=', $tbk_details[7]);
				$tbk_hora_transaccion = explode('=', $tbk_details[8]);
				$tbk_id_transaccion = explode('=', $tbk_details[10]);
				$tbk_tipo_pago = explode('=', $tbk_details[11]);
				$tbk_numero_cuotas = explode('=', $tbk_details[12]);
				$tbk_mac = explode('=', $tbk_details[13]);

				$data['tbk_orden_compra'] = $tbk_orden_compra[1];
				$data['tbk_tipo_transaccion'] = $tbk_tipo_transaccion[1];
				$data['tbk_respuesta'] = $tbk_respuesta[1];
				$data['tbk_monto'] = $tbk_monto[1];
				$data['tbk_codigo_autorizacion'] = $tbk_codigo_autorizacion[1];
				$data['tbk_final_numero_tarjeta'] = '************' . $tbk_final_numero_tarjeta[1];
				$data['tbk_fecha_contable'] = substr($tbk_fecha_contable[1], 2, 2) . '-' . substr($tbk_fecha_contable[1], 0, 2);
				$data['tbk_fecha_transaccion'] = substr($tbk_fecha_transaccion[1], 2, 2) . '-' . substr($tbk_fecha_transaccion[1], 0, 2);
				$data['tbk_hora_transaccion'] = substr($tbk_hora_transaccion[1], 0, 2) . ':' . substr($tbk_hora_transaccion[1], 2, 2) . ':' . substr($tbk_hora_transaccion[1], 4, 2);
				$data['tbk_id_transaccion'] = $tbk_id_transaccion[1];
				$data['tbk_tipo_pago'] = $tbk_tipo_pago[1];
				$data['tbk_numero_cuotas'] = $tbk_numero_cuotas[1];
				$data['tbk_mac'] = $tbk_mac[1];
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/webpay_occl_success.tpl')) {
				$this->load->view($this->config->get('config_template') . '/template/payment/webpay_occl_success.tpl', $data);
			} else {
				$this->load->view('default/template/payment/webpay_occl_success.tpl', $data);

			}

			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/webpay_occl_success.tpl', $data));

		}
	}*/
	?>