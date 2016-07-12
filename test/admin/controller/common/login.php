<?php
class ControllerCommonLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('common/login');
		$username = "";

		$this->document->setTitle($this->language->get('heading_title'));
 		if (isset($this->request->get['id_lista'])) {
 			if ($this->request->get['id_lista']) {
 				$id_lista    = $this->request->get['id_lista'];
 				$id_contacto = $this->request->get['id_contacto'];
 				$id_envio    = $this->request->get['id_envio'];

 				$this->load->model('mailing/desinscritos');
 				$result = $this->model_mailing_desinscritos->desinscribir($id_contacto, $id_lista, $id_envio);

 				if (isset($result)){
 					echo "<script type='text/javascript'>alert('Se ha desinscrito del sistema satisfactoriamente');</script>";					
 				}

 				echo "<script type='text/javascript'>";
				echo "window.self.close();";
				echo "</script>";

			}
 		}



		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {

			//Se cambia para distintos dash para cliente y administrador
			if ($this->session->data['tipo_usuario'] == 'administrador') {
				$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));			
			} else if ($this->session->data['tipo_usuario'] == 'cliente') {
				$this->response->redirect($this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL'));			
			}
			
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->session->data['token'] = md5(mt_rand());

			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0 || strpos($this->request->post['redirect'], HTTPS_SERVER) === 0 )) {
				$this->response->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
			} else {				
				//Se cambia para distintos dash para cliente y administrador
				if ($this->session->data['tipo_usuario'] == 'administrador') {
					$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
				} else if ($this->session->data['tipo_usuario'] == 'cliente') {
					$this->response->redirect($this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL'));
				}
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_login'] = $this->language->get('text_login');
		$data['text_forgotten'] = $this->language->get('text_forgotten');

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');

		$data['button_login'] = $this->language->get('button_login');

		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
			$this->error['warning'] = $this->language->get('error_token');
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['action'] = $this->url->link('common/login', '', 'SSL');

		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
			$username = $this->request->post['username'];
		} else {
			$data['username'] = '';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];

			unset($this->request->get['route']);
			unset($this->request->get['token']);

			$url = '';

			if ($this->request->get) {
				$url .= http_build_query($this->request->get);
			}

			$data['redirect'] = $this->url->link($route, $url, 'SSL');
		} else {
			$data['redirect'] = '';
		}

		if ($this->config->get('config_password')) {
			$data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
		} else {
			$data['forgotten'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/login.tpl', $data));
	}

	protected function validate() {
		if (!isset($this->request->post['username']) || !isset($this->request->post['password']) || !$this->user->login($this->request->post['username'], $this->request->post['password'])) {
			$this->error['warning'] = $this->language->get('error_login');
		}else
			{
				$this->session->data['username'] = $this->request->post['username'];
			}

		return !$this->error;
	}

	public function check() {
		$route = isset($this->request->get['route']) ? $this->request->get['route'] : '';

		$ignore = array(
			'common/login',
			'common/forgotten',
			'common/reset'
		);

		if (!$this->user->isLogged() && !in_array($route, $ignore)) {
			return new Action('common/login');
		}

		if (isset($this->request->get['route'])) {
			$ignore = array(
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'
			);

			if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
				return new Action('common/login');
			}
		} else {
			if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
				return new Action('common/login');
			}
		}
	}

	public function a(){
        

    }
}