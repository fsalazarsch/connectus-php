<?php
class ControllerContactosMisListas extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('contactos/mis_listas');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');
        $this->load->language('contactos/mis_listas');

        $this->getList();
    }

    protected function getList() {

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'L.fecha_creacion';
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

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
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

        
        //download file 
        $this->load->model('contactos/listas');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('contactos/mis_listas', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['products'] = array();

        $filter_data = array(
            'filter_name'     => $filter_name,
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );
        

        //$this->load->model('contactos/listas');
        $lista_contactos = array();
        $data['lista_contactos'] = '';
        $result_lista_contactos = $this->model_contactos_listas->getListasEmpresaPorUsuario($this->session->data['id_empresa'],$filter_data);

        foreach ($result_lista_contactos as $result) {
            $cantidad_contactos_por_lista = $this->model_contactos_listas->getCantidadContactoPorLista($result['id_lista']);        
            $data['lista_contactos'][] = array(
                'id_lista'       => $result['id_lista'],                
                'nombre'         => $result['nombre'],
                'fecha_creacion' => $result['fecha_creacion'],                
                'quantity'       => $cantidad_contactos_por_lista,
                'edit'           => $this->url->link('contactos/lista_modo_edit', 'token=' . $this->session->data['token'] . '&id_lista=' . $result['id_lista'] . $url . '&u=1', 'SSL'),
                'view'           => $this->url->link('contactos/lista_modo_edit', 'token=' . $this->session->data['token'] . '&id_lista=' . $result['id_lista'] . $url , 'SSL'),
                'downloadExcel'  => $this->url->link('contactos/mis_listas/expCont', 'token=' . $this->session->data['token'] . '&id_lista=' . $result['id_lista'] . '&d=x' . $url, 'SSL'),
                'downloadPDF'    => $this->url->link('contactos/mis_listas/expCont', 'token=' . $this->session->data['token'] . '&id_lista=' . $result['id_lista'] . '&d=p' . $url, 'SSL')
            );
        }

        $data['full_excel'] = $this->url->link('contactos/mis_listas/exp', 'token=' . $this->session->data['token'] . '&full=e'  . $url, 'SSL');
        $data['full_pdf'] = $this->url->link('contactos/mis_listas/exp', 'token=' . $this->session->data['token'] . '&full=p'  . $url, 'SSL');
        
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_nombre'] = $this->language->get('column_nombre');
        $data['column_contactos'] = $this->language->get('column_contactos');
        $data['column_fecha_creacion'] = $this->language->get('column_fecha_creacion');
        $data['column_action'] = $this->language->get('column_action');

        $data['entry_nombre'] = $this->language->get('entry_nombre');
        $data['entry_contactos'] = $this->language->get('entry_contactos');
        $data['entry_fecha_creacion'] = $this->language->get('entry_fecha_creacion');

        $data['button_edit'] = $this->language->get('button_edit');

        $data['button_add'] = $this->language->get('text_add');
        $data['button_delete'] = $this->language->get('button_delete');
        
        $data['add'] = $this->url->link('contactos/crear_lista_contactos', 'token=' . $this->session->data['token'], 'SSL');

        $data['button_export_excel'] = $this->language->get('button_export_excel');
        $data['button_export_pdf'] = $this->language->get('button_export_pdf');
        $data['button_export_excel_all'] = $this->language->get('button_export_excel_all');
        $data['button_export_pdf_all'] = $this->language->get('button_export_pdf_all');

        $data['button_eye'] = $this->language->get('button_eye');
        $data['button_filter'] = $this->language->get('button_filter');

       $data['delete'] = $this->url->link('contactos/mis_listas/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $data['token'] = $this->session->data['token'];

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

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }


        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_nombre'] = $this->url->link('contactos/mis_listas', 'token=' . $this->session->data['token'] . '&sort=L.nombre' . $url, 'SSL');
        $data['sort_fecha_creacion'] = $this->url->link('contactos/mis_listas', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');                

        $url = '';

        if (isset($this->request->get['filter_name'])) {
       //     $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $cantidad_listas = $this->model_contactos_listas->getCantidadListasPorIdUsuario($this->session->data['id_empresa']);
        
        $pagination = new Pagination();
        $pagination->total = $cantidad_listas;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('contactos/mis_listas', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($cantidad_listas) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cantidad_listas - $this->config->get('config_limit_admin'))) ? $cantidad_listas : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cantidad_listas, ceil($cantidad_listas / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('contactos/mis_listas.tpl', $data));
    }

    public function edit() {
        $this->load->language('contactos/mis_listas');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);

            //$this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }

            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
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

            $this->response->redirect($this->url->link('contactos/mis_listas', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('contactos/mis_listas');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('contactos/listas');
        $this->load->model('contactos/contacto');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            $count = 0;
            foreach ($this->request->post['selected'] as $id_lista) {
                $result = $this->model_contactos_listas->deleteLista($id_lista);
                $resultCon = $this->model_contactos_listas->deleteContacto($id_lista);
                
                if ($result==1) {
                    $count++;
                }                
            }

            if ($count == 1) {
                    $this->session->data['success'] = $count . $this->language->get('success_delete_uni');
            }elseif ($count > 1) {
                    $this->session->data['success'] = $count . $this->language->get('success_delete_multi');
            }

            $url = '';

            if (isset($this->request->get['id_lista'])) {
                $id_lista = $this->request->get['id_lista'];
                $url .= '&id_lista=' . $id_lista;                
            }            

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }
            

            $this->response->redirect($this->url->link('contactos/mis_listas', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->response->redirect($this->url->link('contactos/mis_listas', 'token=' . $this->session->data['token'] . $url, 'SSL'));

    }
    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['product_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }

            if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }
        }

        if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
            $this->error['model'] = $this->language->get('error_model');
        }

        if (utf8_strlen($this->request->post['keyword']) > 0) {
            $this->load->model('catalog/url_alias');

            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

            if ($url_alias_info && isset($this->request->get['product_id']) && $url_alias_info['query'] != 'product_id=' . $this->request->get['product_id']) {
                $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
            }

            if ($url_alias_info && !isset($this->request->get['product_id'])) {
                $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }
    
    /*
    public function expCont(){
        $this->load->model('contactos/listas');
        $this->load->library('FileManager');

        $filer = new FileManager();
        $tipo = isset($this->request->get['d']) ? $this->request->get['d'] : '';
        $id_lista = $this->request->get['id_lista'];

        $aux = $this->model_contactos_listas->getCamposDeLista($id_lista);
        $contactos = $this->model_contactos_listas->getDatosContactosLista($id_lista);
        $lista = $this->model_contactos_listas->getListaPorID($id_lista);
        $data = array();

        if ($tipo=='x') {
            $filer->excel($contactos['nombre_columnas'],$contactos['valores'],$lista['nombre']);
        }elseif ($tipo=='p') {
            $filer->pdf($contactos['nombre_columnas'],$contactos['valores'],$lista['nombre']);
        }
    }
    
    public function exp(){
        $this->load->model('contactos/listas');
        $this->load->library('FileManager');

        $filer = new FileManager();

        $columnas = array('Nombre', 'Fecha de Creación', 'Cant. de contactos');
        $id_empresa = $this->session->data['id_empresa'];

        $data = $this->model_contactos_listas->getListasEmpresaPorUsuario($id_empresa);

        $tipo = isset($this->request->get['full']) ? $this->request->get['full'] : '';        


        if ($tipo == 'e') {
            $filer->excel($columnas, $data, 'Mis listas');
        }elseif($tipo == 'p'){
            $filer->pdf($columnas, $data, 'Mis listas');
        }
    }
    */
    public function expCont(){
         $this->load->model('contactos/listas');
        if (isset($this->request->get['d'])) {
            $tipo = $this->request->get['d'];
        }else{
            $tipo = '';
        }

        $id_lista = $this->request->get['id_lista'];

        if($tipo!=''){
            if($tipo == 'x'){
                
                $file_excel = $this->model_contactos_listas->exportarExcel($id_lista);
                $this->response->redirect($file_excel);
                $this->getList();
                

            }elseif ($tipo == 'p') {
                $this->model_contactos_listas->exportarPDF($id_lista); 
                
            }
        }
    }
    
    public function exp(){
        $this->load->model('contactos/listas');
        if (isset($this->request->get['full'])) {
            $tipo = $this->request->get['full'];
        }else{
            $tipo = '';
        }

        $id_empresa = $this->session->data['id_empresa'];

        if($tipo!=''){
            if($tipo == 'e'){
                $file_excel = $this->model_contactos_listas->exportar_listas_ex($id_empresa);
                $this->response->redirect($file_excel);
                $this->getList();
                
            }elseif ($tipo == 'p') {
                $this->model_contactos_listas->exportar_listas_pdf($id_empresa);
            }
        }
    }


    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'contactos/mis_listas')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
    
    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {        

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }
        
        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = 5;
        }

        $filter_data = array(
            'filter_name'  => $filter_name,            
            'start'        => 0,
            'limit'        => $limit
        );

        $this->load->model('contactos/listas');
        $lista_contactos = array();

        $result_lista_contactos = $this->model_contactos_listas->getListasEmpresaPorUsuario($this->session->data['id_empresa'],$filter_data);

        foreach ($result_lista_contactos as $result) {

            $json[] = array(
                'id_lista'     => $result['id_lista'],                
                'nombre'         => $result['nombre'],
                'fecha_creacion' => $result['fecha_creacion'],                
                'quantity'   => '1'                
            );
        }

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}