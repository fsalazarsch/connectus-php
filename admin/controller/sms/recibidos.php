<?php
class ControllerSmsRecibidos extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('sms/recibidos');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->getList();
    }

    protected function getList() {
        $this->load->language('sms/recibidos');

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

 
        $this->load->model('sms/recibidos');
        $this->load->model('contactos/listas');


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sms/recibidos', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['products'] = array();

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
        
        $results = $this->model_sms_recibidos->getRecibidos($filter_data);

        foreach( $results as $row ) { 

            $data['lista_recibidos'][] = array(
                    'fecha'    => $row['fecha'],
                    'mensaje'  => $row['mensaje'],
                    'destino'  => $row['destinatario'],
                    'remitente' => $row['remitente']);
            
        }

        $this->session->data['lista_recibidos'] = $data['lista_recibidos'];
    
       
        $data['full_excel'] = $this->url->link('sms/recibidos/exp', 'token=' . $this->session->data['token'] . '&full=e'  . $url, 'SSL');
        $data['full_pdf']   = $this->url->link('sms/recibidos/exp', 'token=' . $this->session->data['token'] . '&full=p'  . $url, 'SSL');
        $data['add']        = $this->url->link('sms/enviar_sms', 'token=' . $this->session->data['token'] . $url, 'SSL');
        
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['column_remitente'] = $this->language->get('column_remitente');
        $data['column_mensaje'] = $this->language->get('column_mensaje');
        $data['column_fecha'] = $this->language->get('column_fecha');
        $data['column_destino'] = $this->language->get('column_destino');



        $data['entry_mensaje']       = $this->language->get('entry_mensaje');
        $data['entry_fecha']         = $this->language->get('entry_fecha');
        $data['entry_remitente']     = $this->language->get('entry_remitente');       
        $data['entry_destino']       = $this->language->get('entry_destino');       

        $data['button_add']          = $this->language->get('button_add');
        $data['button_export_excel'] = $this->language->get('button_export_excel');
        $data['button_export_pdf']   = $this->language->get('button_export_excel');

        
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


        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_remitente'] = $this->url->link('sms/recibidos', 'token=' . $this->session->data['token'] . '&sort=remitente' . $url, 'SSL');
        $data['sort_fecha'] = $this->url->link('sms/recibidos', 'token=' . $this->session->data['token'] . '&sort=fecha' . $url, 'SSL');                

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

        $cantidad_listas = $this->model_sms_recibidos->getCountSmsRecibidos($filter_data);
        

        $pagination = new Pagination();
        $pagination->total = $cantidad_listas;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sms/recibidos', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($cantidad_listas) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cantidad_listas - $this->config->get('config_limit_admin'))) ? $cantidad_listas : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cantidad_listas, ceil($cantidad_listas / $this->config->get('config_limit_admin')));
        
        $data['filter_name'] = $filter_name;
        $data['filter_fecha'] = $filter_fecha;
        $data['filter_fecha_hasta'] = $filter_fecha_hasta;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sms/recibidos.tpl', $data));
    }
  

    public function exp(){

        $this->load->model('contactos/listas');





        if (isset($this->request->get['full'])) {
            $tipo = $this->request->get['full'];
        }else{
            $tipo = '';
        }



        $filter_data = $this->session->data['filter_data'];
        //eliminar paginacion para exportar todos los elementos.
        unset($filter_data['start']);
        unset($filter_data['limit']);


        $id_empresa = $this->session->data['id_empresa'];

        if($tipo!=''){
            if($tipo == 'e'){
                $file_excel = $this->model_contactos_listas->recibidosExcel($filter_data);
                $this->response->redirect($file_excel);
                $this->getList();
            }elseif ($tipo == 'p') {
                $this->model_contactos_listas->recibidosPDF();
            }
        }
    }
   

}