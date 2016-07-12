<?php
class ControllerContactosListaModoEdit extends Controller {
	public function index(){
		$this->load->language('contactos/lista_modo_edit');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->getList();
	}

	public function getList(){
		$this->load->language('contactos/lista_modo_edit');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/category');
		$this->load->model('contactos/listas');
		$this->load->model('contactos/contacto');

		$id_lista = '';

		if (isset($this->request->get['id_lista'])) {
			$id_lista = $this->request->get['id_lista'];
		}

		$data['token']    = $this->session->data['token'];
		$data['id_lista'] = $id_lista;

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		}else{
			$filter = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		}else{
			$sort = 'id_lista';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		}else{
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		}else{
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['id_lista'])) {
            $url .= '&id_lista=' . $this->request->get['id_lista']; 
        }

		//parametros de consulta a URL
		if (isset($this->request->get['filter'])) {
			$url .= '&filter=' . urlencode(html_entity_decode($this->request->get['filter'],ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort']; 
        }


        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if(isset($this->request->get['u'])){
        	$data['editar'] = true;
        }else{
        	$data['editar'] = false;
        }


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('contactos/lista_modo_edit', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['categories'] = array();

		$filter_data = array(
			'filter' => $filter,
			'sort'   => $sort,
			'order'  => $order,
			'start'  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'  => $this->config->get('config_limit_admin')
		);

		$lista_info = $this->model_contactos_listas->getListaPorID($id_lista);
		$category_total = $this->model_contactos_listas->getCantidadContactoPorLista($id_lista);
		$contactos = $this->model_contactos_listas->getDatosContactosLista($id_lista,$filter_data);
		

		$data['heading_title'] = $this->language->get('heading_title');
		//cabeceras de primera tabla del tpl		
		$data["text_nombre"]       = $this->language->get('text_nombre');
		$data["text_num_reg"]      = $this->language->get('text_num_reg');
		$data["text_creacion"]     = $this->language->get('text_creacion');
		$data["text_actualizacion"]= $this->language->get('text_actualizacion');
		//valores de la primera tabla
		$data["nombre"]        = $lista_info['nombre'];
		$data["num_reg" ]      = $category_total;
		$data["creacion"]      = $lista_info['fecha_creacion'];
		$data["actualizacion"] = $lista_info['ultima_actualizacion'];
			

		$data['text_list'] = $this->language->get('text_list');
		$data['text_detalle_lista'] = $this->language->get('text_detalle_lista');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_rebuild'] = $this->language->get('button_rebuild');

		//action para agregar y actualizar modal
		$data['maddaction'] = $this->url->link('contactos/lista_modo_edit/nuevoContacto', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['refresh'] = $this->url->link('contactos/lista_modo_edit', 'token=' . $this->session->data['token'].$url , 'SSL');

		$data['delete'] = $this->url->link('contactos/lista_modo_edit/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['action'] = $this->url->link('contactos/lista_modo_edit/updateContacto', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['mis_listas'] = $this->url->link('contactos/mis_listas', 'token=' . $this->session->data['token'] . $url, 'SSL');

		/*Inicio dialog modal*/
		$data['btn_actualizar_contacto'] = $this->language->get('btn_actualizar_contacto');
		$data['btn_guardar'] = $this->language->get('btn_guardar');
		$data['btn_cancel'] = $this->language->get('btn_cancel_mod');
		$data['modal_title'] = $this->language->get('modal_title');

		$this->load->model('contactos/contacto');
		$result = $this->model_contactos_contacto->getContactoForm($id_lista);

		$data['form'] = $result;
		$this->session->data['form_add'] = $result;

		/*--Fin dialogo modal--*/

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}


		if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . urlencode(html_entity_decode($this->request->get['filter'], ENT_QUOTES, 'UTF-8'));
        }
		
		if ($order == 'ASC') {
            $url .= '&order=DESC';
            $data['order'] = 'ASC';
        } else {
            $url .= '&order=ASC';
            $data['order'] = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }


        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if ($id_lista) {
        	$url .= '&id_lista=' . $id_lista;
        }

        if(isset($this->request->get['u'])){        	
        	$url .= '&u=1';
        }

        $this->session->data['id_lista'] = $id_lista;

        		  
		for ($indice=0; $indice < count($contactos['campos_de_contacto']) - 1 ; $indice++) { 
			$aux = $contactos['nombre_columnas'][$indice];
			$contactos['nombre_columnas'][$indice] = array();
			$contactos['nombre_columnas'][$indice][] = $aux;
			$contactos['nombre_columnas'][$indice][] = $this->url->link('contactos/lista_modo_edit', 'token=' . $this->session->data['token'] . '&sort=' . $contactos['campos_de_contacto'][$indice] . $url , 'SSL'); 
		}

		
		$data['contactos'] = $contactos;

		$pagination = new Pagination();
		$pagination->total = $category_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('contactos/lista_modo_edit', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));

		$data['filter'] = $filter;
		$data['sort']   = $sort;
		$data['order']  = $order;

		$data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('contactos/lista_modo_edit.tpl', $data));
	}

	public function nuevoContacto(){
		$form_add = $this->session->data['form_add'];
		$lista = $this->session->data['id_lista']; 

		$sql = "INSERT INTO contacto SET ";

		for ($i = 0; $i < count($form_add['campo']); $i++) {				
			$sql .= $form_add['campo'][$i] . "='" . $this->request->post[$form_add['campo'][$i]] . "',";
		}

		$sql .= "id_lista=".$lista;

		$result = $this->admDB->query($sql);
		if($result->num_rows>0){
			$this->session->data['success']="Contacto agregado exitosamente!";
			
		}	
		$url = '&id_lista=' . $this->request->get['id_lista'];
		$url .= '&u=1';
		$this->response->redirect($this->url->link('contactos/lista_modo_edit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function traerContacto(){

		if (isset($this->request->post['id_contacto'])) {
			$this->load->model('contactos/contacto');

			$result = $this->model_contactos_contacto->getContacto($this->request->post['id_contacto'],$this->session->data['id_lista']);		

			
			$this->session->data['info_contacto_editar'] = $result;

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($result));

		} 
	}
	
	public function updateContacto(){
		$datos_edit = $this->session->data['info_contacto_editar'];
		$input_names = array();
		$value_controles = array();

		for ($i=0; $i < count($datos_edit['campos_de_contacto']) ; $i++) { 
			if ($datos_edit['campos_de_contacto']!='id_contacto') {
				$input_names[] = $datos_edit['campos_de_contacto'][$i];
				if ($datos_edit['campos_de_contacto'][$i] != "id_contacto") {
					$value_controles[] = $this->request->post[$datos_edit['campos_de_contacto'][$i]];
				}
			}
		}

		$this->load->model('contactos/contacto');
		$this->model_contactos_contacto->updateContacto($input_names, $value_controles, $datos_edit['valores'][0]['id_contacto']);

		$url = '&id_lista=' . $this->request->get['id_lista'];
		$url .= '&u=1';
		$this->response->redirect($this->url->link('contactos/lista_modo_edit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function updateLista(){

		$url = '';

		if (isset($this->request->get['update'])) {
			if (!empty($this->request->get['update'])) {
				$nombre_lista = $this->request->get['update'];

				if (isset($this->request->get['id_lista'])) {
					if (!empty($this->request->get['id_lista'])) {
						$this->load->model('contactos/listas');
						$this->load->language('contactos/lista_modo_edit');

						$id_lista = $this->request->get['id_lista'];
						$result = $this->model_contactos_listas->updateNombreLista($nombre_lista,$id_lista);
						$this->model_contactos_listas->setFechaActualizacion($id_lista);

					}
				}
			}
		}
		$url .= '&id_lista=' . $this->request->get['id_lista'];
		$url .= '&u=1';
		$this->response->redirect($this->url->link('contactos/lista_modo_edit', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function delete() {
		$this->load->language('contactos/lista_modo_edit');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('contactos/listas');
		$this->load->model('contactos/contacto');
		
		$url = '';

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id_contacto) {
				$this->model_contactos_contacto->deleteContacto($id_contacto);
			}


			if (isset($this->request->get['id_lista'])) {
				$id_lista = $this->request->get['id_lista'];
				$url .= '&id_lista=' . $id_lista;

				$this->model_contactos_listas->setFechaActualizacion($id_lista);
			}


			$this->session->data['success'] = $this->language->get('text_success');
			

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
		}

		$url .= '&id_lista=' . $this->request->get['id_lista'];
		$url .= '&u=1';
		
		$this->response->redirect($this->url->link('contactos/lista_modo_edit', 'token=' . $this->session->data['token'] . $url, 'SSL'));

	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'contactos/lista_modo_edit')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	/*public function autocomplete() {

        $json = array();

        if (isset($this->request->get['filter'])) {        

	        if (isset($this->request->get['filter'])) {
	            $filter_name = $this->request->get['filter'];
	        } else {
	            $filter_name = '';
	        }
	        
	        if (isset($this->request->get['limit'])) {
	            $limit = $this->request->get['limit'];
	        } else {
	            $limit = 5;
	        }

	        $filter_data = array(
	            'filter'  => $filter,            
	            'start'   => 0,
	            'limit'   => $limit
	        );

	        $this->load->model('contactos/listas');
	        $lista_contactos = array();

	        $contactos = $this->model_contactos_listas->getDatosContactosLista($this->session->data['user_id'],$filter_data);        

	        //despues
	        for ($indice=0; $indice < count($contactos['campos_de_contacto']) - 1 ; $indice++) { 
	        	$json['id_contacto'] = $contactos['valores']['id_contacto'];
	        	$json['nombre'] = $contactos['valores'][$indice];

	        	foreach ($contactos['valores'] as $key => $value) {
					$json['id_contacto'] = $value[$indice];
					$aux = $contactos['nombre_columnas'][$indice];
					$contactos['nombre_columnas'][$indice] = array();
					$contactos['nombre_columnas'][$indice][] = $aux;
					$contactos['nombre_columnas'][$indice][] = $this->url->link('contactos/lista_modo_edit', 'token=' . $this->session->data['token'] . '&sort=' . $contactos['campos_de_contacto'][$indice] . $url, 'SSL');
				}
			}

			
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }*/
}