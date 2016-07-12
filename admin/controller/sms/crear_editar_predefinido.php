<?php
class ControllerSmsCrearEditarPredefinido extends Controller {
	private $error = array();

	public function index() {
        $this->load->language('sms/crear_editar_predefinido');        
        $this->document->setTitle($this->language->get('heading_title'));
        $this->getForm();
    }

	public function getForm() {
		$this->load->language('sms/crear_editar_predefinido');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('contactos/listas');
		//textos y labels
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_form_crear');
			
		$data['entry_editor'] = $this->language->get('entry_editor');

		$data['button_save'] = $this->language->get('text_btn_save');
		$data['button_cancel'] = $this->language->get('text_btn_cancel');

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sms/crear_editar_predefinido', 'token=' . $this->session->data['token'], 'SSL')
		);


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

        if (isset($this->error['error_lista_destinatarios'])) {
            $data['error_lista_destinatarios'] = $this->language->get('error_lista_destinatarios');
        } else {
            $data['error_lista_destinatarios'] = '';
        }
	

        if (isset($this->request->post['nombre_mensaje_predefinido'])) {
            $data['nombre_mensaje_predefinido'] = $this->request->post['nombre_mensaje_predefinido'];
         } else {
            $data['nombre_mensaje_predefinido'] = '';
        }

       	if(isset($this->error['error_nombre_mensaje_predefinido'])){

			$data['error_nombre_mensaje_predefinido'] = $this->language->get('error_nombre_mensaje');
		}else{
			$data['error_nombre_mensaje_predefinido'] = '';
		}

		if(isset($this->error['error_mensaje_a_enviar'])){

			$data['error_mensaje_a_enviar'] = $this->language->get('error_mensaje_a_enviar');
		}else{
			$data['error_mensaje_a_enviar'] = '';
		}
		

        $data['placeholder_nuevo_predefinido'] = $this->language->get('placeholder_nuevo_predefinido');


		if(isset($this->request->post['cuerpo'])){			
			$data['mensaje'] = html_entity_decode($this->request->post['cuerpo'], ENT_QUOTES, 'UTF-8');
		}else{
			$data['mensaje'] = '';
		}	

		if(isset($this->request->post['uploadFile'])){
			$data['nombre_archivo'] = $this->request->post['uploadFile'];
		}else{
			$data['nombre_archivo'] = '';
		}

		
		$data['model'] = $this->model_contactos_listas;

		$lista_contactos = array();
        $result_lista_contactos = $this->model_contactos_listas->getListasEmpresaPorUsuarioSms($this->session->data['id_empresa']);

        foreach( $result_lista_contactos as $row ) {
            $filtro = array('nombre' => $row['nombre'],
                               'id_lista' => $row['id_lista']);

            $lista_contactos[] = $filtro;
        }

        $data['lista_contactos'] = $lista_contactos;

        if (isset($this->request->get['id_mensaje'])) {           
            $this->load->model('sms/lista_sms_predefinidos');
        	        
	        $data['lista_preferidos'] = '';
	        $this->session->data['id_mensaje'] = $this->request->get['id_mensaje'];
	        $result_preferidos = $this->model_sms_lista_sms_predefinidos->getSmsPredefinidosPorID($this->request->get['id_mensaje']);
	        
	        foreach( $result_preferidos as $row ) {

	        	 $data['nombre_mensaje_predefinido'] = utf8_encode($row['titulo']);
	        	 $data['mensaje'] = utf8_encode($row['cuerpo']);	            	            
	        }
        }

		$data['cancel'] = $this->url->link('sms/lista_sms_predefinidos', 'token=' . $this->session->data['token'], 'SSL');	
		$data['action']	= $this->url->link('sms/crear_editar_predefinido/add', 'token=' . $this->session->data['token'], 'SSL');	

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');		

		$this->response->setOutput($this->load->view('sms/crear_editar_predefinido.tpl', $data));	
	}

	public function editar() {
		$this->load->language('sms/crear_editar_predefinido');

		$this->document->setTitle($this->language->get('heading_title_editar'));
		$this->load->model('contactos/listas');
		//textos y labels
		$data['heading_title'] = $this->language->get('heading_title_editar');
		$data['text_list'] = $this->language->get('text_form_editar');
			
		$data['entry_editor'] = $this->language->get('entry_editor');

		$data['button_save'] = $this->language->get('text_btn_save');
		$data['button_cancel'] = $this->language->get('text_btn_cancel');

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_editar'),
			'href' => $this->url->link('sms/crear_editar_predefinido/editar', 'token=' . $this->session->data['token'].'&id_mensaje='.$this->request->get['id_mensaje'], 'SSL')
		);


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

        if (isset($this->error['error_lista_destinatarios'])) {
            $data['error_lista_destinatarios'] = $this->language->get('error_lista_destinatarios');
        } else {
            $data['error_lista_destinatarios'] = '';
        }
	

        if (isset($this->request->post['nombre_mensaje_predefinido'])) {
            $data['nombre_mensaje_predefinido'] = $this->request->post['nombre_mensaje_predefinido'];
         } else {
            $data['nombre_mensaje_predefinido'] = '';
        }

       	if(isset($this->error['error_nombre_mensaje_predefinido'])){

			$data['error_nombre_mensaje_predefinido'] = $this->language->get('error_nombre_mensaje');
		}else{
			$data['error_nombre_mensaje_predefinido'] = '';
		}

		if(isset($this->error['error_mensaje_a_enviar'])){

			$data['error_mensaje_a_enviar'] = $this->language->get('error_mensaje_a_enviar');
		}else{
			$data['error_mensaje_a_enviar'] = '';
		}
		

        $data['placeholder_nuevo_predefinido'] = $this->language->get('placeholder_nuevo_predefinido');


		if(isset($this->request->post['cuerpo'])){			
			$data['mensaje'] = utf8_encode($this->request->post['cuerpo']);
		}			

		if(isset($this->request->post['uploadFile'])){
			$data['nombre_archivo'] = $this->request->post['uploadFile'];
		}else{
			$data['nombre_archivo'] = '';
		}

		
		$data['model'] = $this->model_contactos_listas;

		$lista_contactos = array();
        $result_lista_contactos = $this->model_contactos_listas->getListasEmpresaPorUsuario($this->session->data['id_empresa']);

        foreach( $result_lista_contactos as $row ) {
            $filtro = array('nombre' => $row['nombre'],
                               'id_lista' => $row['id_lista']);

            $lista_contactos[] = $filtro;
        }

        $data['lista_contactos'] = $lista_contactos;

        if (isset($this->request->get['id_mensaje'])) {           
            $this->load->model('sms/lista_sms_predefinidos');
        	        
	        $data['lista_preferidos'] = '';
	        $this->session->data['id_mensaje'] = $this->request->get['id_mensaje'];
	        $result_preferidos = $this->model_sms_lista_sms_predefinidos->getSmsPredefinidosPorID($this->request->get['id_mensaje']);
	        
	        foreach( $result_preferidos as $row ) {

	        	 $data['nombre_mensaje_predefinido'] = utf8_encode($row['titulo']);
	        	 $data['mensaje'] = utf8_encode($row['cuerpo']);	            	            
	        }
        }

		$data['cancel'] = $this->url->link('sms/lista_sms_predefinidos', 'token=' . $this->session->data['token'], 'SSL');	
		$data['action']	= $this->url->link('sms/crear_editar_predefinido/update', 'token=' . $this->session->data['token'], 'SSL');	

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');		

		$this->response->setOutput($this->load->view('sms/crear_editar_predefinido.tpl', $data));		
	}

	public function add() {		

		if(isset($_FILES['file']['name'])){
			for($i=0; $i<count($_FILES['file']['name']); $i++) {			  
				  $tmpFilePath = $_FILES['file']['tmp_name'][$i];			  
				  if ($tmpFilePath != ""){
				    $newFilePath = DIR_FILE_ATTACHMENT. $_FILES['file']['name'][$i];			   
				    move_uploaded_file($tmpFilePath, $newFilePath);			      			    
				  }
			}  
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {		 	
		 	$this->load->model('sms/lista_sms_predefinidos');
		 	$this->load->language('sms/crear_editar_predefinido');  

		 	$this->model_sms_lista_sms_predefinidos->addSms(($this->request->post['nombre_mensaje_predefinido']), ($this->request->post['cuerpo']), '', '', 1, $this->session->data['user_id']);
		 	$this->session->data['success'] = $this->language->get('text_success_crear');

		 	$this->response->redirect($this->url->link('sms/lista_sms_predefinidos','token=' . $this->session->data['token'],'SSL'));	
		 }		

		 $this->editar(); 				 			 	
			
	}

	public function update() {	

		if(isset($_FILES['file']['name'])){
			for($i=0; $i<count($_FILES['file']['name']); $i++) {			  
				  $tmpFilePath = $_FILES['file']['tmp_name'][$i];			  
				  if ($tmpFilePath != ""){
				    $newFilePath = DIR_FILE_ATTACHMENT. $_FILES['file']['name'][$i];			   
				    move_uploaded_file($tmpFilePath, $newFilePath);			      			    
				  }
			}  
		}	

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {		 	
		 	$this->load->model('sms/lista_sms_predefinidos');
			$this->load->language('sms/crear_editar_predefinido');  	
		 	
		 	$this->model_sms_lista_sms_predefinidos->updateMensaje($this->request->post['nombre_mensaje_predefinido'], $this->request->post['cuerpo'], $this->session->data['id_mensaje']);		 	
		 	unset($this->session->data['id_mensaje']);
		 	$this->session->data['success'] = $this->language->get('text_success_editar');

		 	$this->response->redirect($this->url->link('sms/lista_sms_predefinidos','token=' . $this->session->data['token'],'SSL'));	
		 }		

		 $this->editar(); 				 			 	
			
	}

	public function validate(){
		$this->load->language('sms/enviar_sms');

		if(html_entity_decode($this->request->post['cuerpo'])== '<p><br></p>'){
			$this->error['error_mensaje_a_enviar'] = $this->language->get('error_mensaje_a_enviar');
		}		

		if(empty($this->request->post['nombre_mensaje_predefinido'])){
			$this->error['error_nombre_mensaje_predefinido'] = $this->language->get('error_nombre_mensaje_predefinido');
		}

		return !$this->error;
	}

	public function getCamposPorLista(){
		$json = array();

		$a = $this->request->post['id_lista'];

		$this->load->model('contactos/listas');
		$result = $this->model_contactos_listas->getDatosContactosLista($a);		

		$json[0] = sizeof($result['nombre_columnas']);
		for ($i=0; $i < sizeof($result['nombre_columnas']); $i++) { 

			$json[$i + 1 ] = $result['nombre_columnas'][$i];

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}