<?php   //DESARROLLO
class ControllerMailingListaMailsPredefinidos extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('mailing/lista_mails_predefinidos');

        $this->document->setTitle($this->language->get('heading_title'));
        
        
        $this->getList();
    } 

    protected function getList() {

        $this->load->language('mailing/lista_mails_predefinidos');

        if (isset($this->request->get['filter_fecha'])) {
            $filter_fecha = $this->request->get['filter_fecha'];
        } else {
            $filter_fecha = null;
        }

         if (isset($this->request->get['filter_nombre'])) {
            $filter_nombre = $this->request->get['filter_nombre'];
        } else {
            $filter_nombre = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'L.nombre';
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

        if (isset($this->request->get['filter_nombre'])) {
            $url .= '&filter_nombre=' . urlencode(html_entity_decode($this->request->get['filter_nombre'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_fecha'])) {
            $url .= '&filter_fecha=' . urlencode(html_entity_decode($this->request->get['filter_fecha'], ENT_QUOTES, 'UTF-8'));
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

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('mailing/lista_mails_predefinidos', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['products'] = array();

        $filter_data = array(
            'filter_nombre'	  => $filter_nombre,
            'filter_fecha'      => $filter_fecha,
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );
        

        $this->load->model('mailing/lista_mails_predefinidos');
        
        $cantidad_listas = $this->model_mailing_lista_mails_predefinidos->getCountMailPredefinidos($this->session->data['id_empresa']);
        $data['lista_preferidos'] = '';
        $result_preferidos = $this->model_mailing_lista_mails_predefinidos->getMailPredefinidos($this->session->data['id_empresa'], $filter_data);

        $autor = utf8_decode( $this->session->data['firstname'].' '.$this->session->data['lastname'] );
        
        foreach( $result_preferidos as $row ) {      

            if(empty($row['nombre_predefinido'])){
                $nombre_predefinido = utf8_decode($row['titulo']);
            }else{
                $nombre_predefinido = utf8_decode($row['nombre_predefinido']);
            }

            $data['lista_preferidos'][] = array(
                    'id_mensaje' => ($row['id_mensaje']),
                    'titulo' => ($nombre_predefinido),
                    'autor' => $autor,
                    'fecha_creacion' => $row['fecha_creacion'],
                    'edit'           => $this->url->link('mailing/crear_editar_predefinido/editar', 'token=' . $this->session->data['token'] . '&id_mensaje=' . $row['id_mensaje'] . $url, 'SSL'));
            
        }
    
        
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

        $data['delete'] = $this->url->link('mailing/lista_mails_predefinidos/delete', 'token=' . $this->session->data['token'], 'SSL');
        $data['add'] = $this->url->link('mailing/crear_editar_predefinido', 'token=' . $this->session->data['token'], 'SSL');
        $data['full_excel'] = $this->url->link('mailing/lista_mails_predefinidos/exp', 'token=' . $this->session->data['token'] . "&exp=e", 'SSL');
        $data['full_pdf'] = $this->url->link('mailing/lista_mails_predefinidos/exp', 'token=' . $this->session->data['token'] . "&exp=p", 'SSL');

        $data['button_eye'] = $this->language->get('button_eye');
        $data['button_filter'] = $this->language->get('button_filter');

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


        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_nombre'] = $this->url->link('mailing/lista_mails_predefinidos', 'token=' . $this->session->data['token'] . '&sort=titulo' . $url, 'SSL');
        $data['sort_fecha_creacion'] = $this->url->link('mailing/lista_mails_predefinidos', 'token=' . $this->session->data['token'] . '&sort=fecha_creacion' . $url, 'SSL');                
        $data['sort_autor'] = $this->url->link('mailing/lista_mails_predefinidos', 'token=' . $this->session->data['token'] . '&sort=autor' . $url, 'SSL'); 

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        
        
        $pagination = new Pagination();
        $pagination->total = $cantidad_listas;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('mailing/lista_mails_predefinidos', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($cantidad_listas) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cantidad_listas - $this->config->get('config_limit_admin'))) ? $cantidad_listas : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cantidad_listas, ceil($cantidad_listas / $this->config->get('config_limit_admin')));

        $data['filter_nombre'] = $filter_nombre;
        $data['filter_fecha'] = $filter_fecha;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('mailing/lista_mails_predefinidos.tpl', $data));
    }

    public function autocomplete() {
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
                'filter'  => $filter_name,            
                'start'        => 0,
                'limit'        => $limit
            );


            $this->load->model('mailing/lista_mails_predefinidos');

                        
            $result_preferidos = $this->model_mailing_lista_mails_predefinidos->getMailPredefinidos($this->session->data['id_empresa'],$filter_data);
            
            foreach($result_preferidos as $row ) {
                $json[] = array(
                        'id_mensaje' => utf8_encode($row['id_mensaje']),
                        'titulo' => utf8_encode($row['titulo']));
                
            }           

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function delete() {
        $this->load->language('mailing/lista_mails_predefinidos');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('mailing/lista_mails_predefinidos');

        if (isset($this->request->post['selected'])) {
            
            foreach ($this->request->post['selected'] as $id_mensaje) {
                $this->model_mailing_lista_mails_predefinidos->deleteMensaje($id_mensaje);            
            }

            $this->session->data['success'] = $this->language->get('text_success_borrar');

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

            $this->response->redirect($this->url->link('mailing/lista_mails_predefinidos', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        $this->getList();
    }

    public function exp(){
        $this->load->model('contactos/listas');
        if (isset($this->request->get['exp'])) {
            $tipo = $this->request->get['exp'];
        }else{
            $tipo = '';
        }

        $id_empresa = $this->session->data['id_empresa'];

        if($tipo!=''){
            if($tipo == 'e'){
                $this->model_contactos_listas->exportar_mensaje_x();
                $file_excel = $this->model_contactos_listas->exportar_mail_x($id_empresa);
                $this->response->redirect($file_excel);
                $this->getList();
            }elseif ($tipo == 'p') {
                $this->model_contactos_listas->exportar_mail_predef_pdf();
            }
        }
    }




}