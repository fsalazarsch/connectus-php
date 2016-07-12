<?php

	class ControllerReportesSms extends Controller
	{
		
		private $error = array();
		private $ruta_Archivo = '/../../../RepoProd/csv/';

		public function index()
		{
			$this->load->language('reportes/sms');

			$this->document->setTitle($this->language->get('titulo'));

			$data['token'] = $this->session->data['token'];

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('detalle'),
				'href' => $this->url->link('reportes/sms', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['button_export_excel'] = $this->language->get('button_export_excel');

			$data['btn_filtrar'] = $this->language->get('button_filtrar');

			$data['titulo'] = $this->language->get('titulo');
        	$data['detalle'] = $this->language->get('detalle');

        	if (isset($this->request->get['sort'])) {
	            $sort = $this->request->get['sort'];
	        } else {
	            $sort = 'fecha';
	        }

	        if (isset($this->request->get['order'])) {
	            $order = $this->request->get['order'];
	        } else {
	            $order = 'DESC';
	        }

	        if (isset($this->request->get['page'])) {
	            $page = $this->request->get['page'];
	        } else {
	            $page = 1; 
	        }


			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['error_cred'])) {
				$data['error_cred'] = $this->session->data['error_cred'];
				unset($this->session->data['error_cred']);
			}else{
				$data['error_cred'] = '';
			}
					

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];
				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			if (isset($this->request->get['filter_name'])) {
	            $filter_name = $this->request->get['filter_name'];
	        } else {
	            $filter_name = null;
	        }

	        if (isset($this->request->get['filter_fecha'])) {
	            $filter_fecha = $this->request->get['filter_fecha'];
	        } else {
	            $filter_fecha = null;
	        }

	        if (isset($this->request->get['filter_fecha_hasta'])) {
	            $filter_fecha_hasta = $this->request->get['filter_fecha_hasta'];
	        } else {
	            $filter_fecha_hasta = null;
	        }


        	$url = '';

	        if (isset($this->request->get['filter_name'])) {
	            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
	        }

	        if (isset($this->request->get['filter_fecha'])) {
	            $url .= '&filter_fecha=' . urlencode(html_entity_decode($this->request->get['filter_fecha'], ENT_QUOTES, 'UTF-8'));
	        }

	        if (isset($this->request->get['filter_fecha_hasta'])) {
	            $url .= '&filter_fecha_hasta=' . urlencode(html_entity_decode($this->request->get['filter_fecha_hasta'], ENT_QUOTES, 'UTF-8'));
	        }

	         if (isset($this->request->get['sort'])) {
	            $url .= '&sort=' . $this->request->get['sort'];
	        }

	        if (isset($this->request->get['order'])) {
	            $url .= '&order=' . $this->request->get['order'];
	        }

	        if (isset($this->request->get['page'])) {
	            $url .= '&page=' . $this->request->get['page'];
	        }

			$data['full_excel'] = $this->url->link('reportes/sms/exp', 'token=' . $this->session->data['token'] . '&full=e'  . $url, 'SSL');
			
			$data['sort_fecha'] = $this->url->link('reportes/sms', 'token=' . $this->session->data['token'] . '&sort=fecha' . $url, 'SSL');                
			$data['sort_nombre'] = $this->url->link('reportes/sms', 'token=' . $this->session->data['token'] . '&sort=nombre' . $url, 'SSL');                



			$this->load->model('sms/reportes');
			
			$filter_data = array(
	            'filter_name'     => $filter_name,
	            'filter_fecha'     => $filter_fecha,
	            'filter_fecha_hasta'=> $filter_fecha_hasta,
	            'sort'            => $sort,
	            'order'           => $order,
	            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
	            'limit'           => $this->config->get('config_limit_admin')
	        );


	        unset($this->session->data['filter_data']);
	        $this->session->data['filter_data'] = $filter_data;
	        
	        
	        $lista_contactos = array();

	        $data['lista_recibidos'] = '';

	        $results = $this->model_sms_reportes->getReportes($filter_data);


	        foreach( $results as $row )
	        { 
	            $data['lista_recibidos'][] = array(
	            		'id_archivo'    	=> $row['id_archivo'],
	                    'fecha'    	=> $row['creacion'],
	                    'nombre'  	=> $row['nombre'],
	                    'ruta'  	=> $row['ruta'],
	                    'tipo_archivo' => $row['tipo_archivo']);
	            
	        }

	        $this->session->data['lista_recibidos'] = $data['lista_recibidos'];

			
			$cantidad_reportes = $this->model_sms_reportes->getCountListaArchivos($filter_data);
        

	        $pagination = new Pagination();
	        $pagination->total = $cantidad_reportes;
	        $pagination->page = $page;
	        $pagination->limit = $this->config->get('config_limit_admin');
	        $pagination->url = $this->url->link('reportes/sms', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

	        $data['pagination'] = $pagination->render();

	        $data['results'] = sprintf($this->language->get('text_pagination'), ($cantidad_reportes) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cantidad_reportes - $this->config->get('config_limit_admin'))) ? $cantidad_reportes : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cantidad_reportes, ceil($cantidad_reportes / $this->config->get('config_limit_admin')));
	        
	        $data['filter_name'] = $filter_name;
	        $data['filter_fecha'] = $filter_fecha;
	        $data['filter_fecha_hasta'] = $filter_fecha_hasta;

	        $data['sort'] = $sort;
	        $data['order'] = $order;

	        $data['header'] = $this->load->controller('common/header');
	        $data['column_left'] = $this->load->controller('common/column_left');
	        $data['footer'] = $this->load->controller('common/footer');


	        $data['column_nombre'] 		= $this->language->get('column_nombre');
	        $data['column_creacion'] 	= $this->language->get('column_creacion');
	        $data['column_archivo'] 	= $this->language->get('column_archivo');
	        $data['column_tipo_archivo']= $this->language->get('column_tipo_archivo');
	        $data['text_no_results'] 	= $this->language->get('text_no_results');

	        $this->response->setOutput($this->load->view('reportes/sms.tpl', $data));
		}

		public function delete()
		{

			$repo = $_POST['repo'];
	        $data['repo'] = $repo;        

	        if(!empty($repo))
	        {

	            $this->load->model('sms/reportes'); 

	            $this->model_sms_reportes->setRuta($this->ruta_Archivo);

	            if($this->model_sms_reportes->deleteReporte($repo))
	            {
	            	$data['estado']     =   true;
	            	$this->session->data['success'] = "Reporte eliminado con exito!";

	            } else {
	            	$data['estado'] = false;
	            	$data['error']  = "Error al eliminar reporte.";
	            	$this->session->data['success'] = 'Ups, algo salió mal. Vuelve a intentar.';
	            }

	                
	        }else{
	        	$data['estado'] = false;
	        	$data['error']  = "Var repo vacía.";
	            $this->session->data['success'] = 'Ups, algo salió mal. Vuelve a intentar.';
	        }
	    	

	    	
	        echo json_encode($data);
		}
	}