<?php
class ControllerCommonLogout extends Controller {
	public function index() {
		$this->user->logout();

		unset($this->session->data['token']);
		unset($this->session->data['como_empresa']);
		unset($this->session->data['id_empresa']);


		$this->response->redirect($this->url->link('common/login', '', 'SSL'));
	}

	public function salirModoEmpresa()
	{
		$token = $this->request->get['token'];
		
		unset($this->session->data['como_empresa']);
		unset($this->session->data['id_empresa']);

		$this->response->redirect($this->url->link('common/dashboard', '&token=' . $token));
	}
}