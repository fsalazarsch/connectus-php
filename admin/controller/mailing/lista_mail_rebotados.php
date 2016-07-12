<?php
class ControllerMailingListaMailRebotados extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('mailing/lista_mail_rebotados');

        $this->document->setTitle($this->language->get('heading_title'));
        
        
        $this->getList();
    } 

    protected function getList() {

        $this->load->language('mailing/lista_mail_rebotados');

        if (isset($this->request->get['filter'])) {
            $filter_name = $this->request->get['filter'];
        } else {
            $filter_name = null;
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

        if (isset($this->request->get['filter_fecha'])) {
            $url .= '&filter_fecha=' . urlencode(html_entity_decode($this->request->get['filter_fecha'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_email'])) {
            $url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_remitente'])) {
            $url .= '&filter_remitente=' . urlencode(html_entity_decode($this->request->get['filter_remitente'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_correo_remitente'])) {
            $url .= '&filter_correo_remitente=' . urlencode(html_entity_decode($this->request->get['filter_correo_remitente'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_fecha'])) {
            $filter_fecha = $this->request->get['filter_fecha'];
        } else {
            $filter_fecha = null;
        }

        if (isset($this->request->get['filter_email'])) {
            $filter_email = $this->request->get['filter_email'];
        } else {
            $filter_email = null;
        }

        if (isset($this->request->get['filter_remitente'])) {
            $filter_remitente = $this->request->get['filter_remitente'];
        } else {
            $filter_remitente = null;
        }

        if (isset($this->request->get['filter_correo_remitente'])) {
            $filter_correo_remitente = $this->request->get['filter_correo_remitente'];
        } else {
            $filter_correo_remitente = null;
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
            'href' => $this->url->link('mailing/lista_mail_rebotados', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        $data['products'] = array();

        $filter_data = array(            
            'filter_fecha'  => $filter_fecha,
            'filter_email' => $filter_email,
            'filter_remitente' => $filter_remitente,    
            'filter_correo_remitente' => $filter_correo_remitente,
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );
        

        $this->load->model('mailing/envio');
        
        $cantidad_listas = $this->model_mailing_envio->countRebotesPorUsuario($this->session->data['id_empresa']);
        
        $data['lista_rebotados'] = '';
        $result_rebotados = $this->model_mailing_envio->getRebotesPorUsuario($this->session->data['id_empresa'],$filter_data);

        foreach( $result_rebotados as $row ) {                    
            $data['lista_rebotados'][] = array(
                    'id_envio' => $row['id_envio'],                    
                    'fecha_envio' => ($row['fecha_envio']),
                    'email' => $row['email'],
                    'estado' => 'Rebotado',
                    'nombre_remitente' => $row['nombre_remitente'],
                    'correo_remitente' => $row['correo_remitente'],
                    'edit'           => $this->url->link('mailing/lista_mail_rebotados', 'token=' . $this->session->data['token'] . '&id_envio=' . $row['id_envio'] . $url, 'SSL'),
                    'reenviar'           => $this->url->link('mailing/lista_mail_rebotados/reenviar', 'token=' . $this->session->data['token'] . '&id_envio=' . $row['id_envio'] . $url, 'SSL'));
            
        }
    
        
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_fecha_envio'] = $this->language->get('column_fecha_envio');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_estado'] = $this->language->get('column_estado');
        $data['column_nombre_remitente'] = $this->language->get('column_nombre_remitente');
        $data['column_correo_remitente'] = $this->language->get('column_correo_remitente');
        $data['column_action'] = $this->language->get('column_action');


        $data['entry_nombre'] = $this->language->get('entry_nombre');
        $data['entry_contactos'] = $this->language->get('entry_contactos');
        $data['entry_fecha_creacion'] = $this->language->get('entry_fecha_creacion');

        $data['button_edit'] = $this->language->get('button_eye');

        $data['button_reenviar'] = $this->language->get('text_reenviar');
        
        $data['add'] = $this->url->link('mailing/lista_mail_rebotados', 'token=' . $this->session->data['token'], 'SSL');
        $data['full_excel'] = $this->url->link('mailing/lista_mail_rebotados/exp', 'token=' . $this->session->data['token'] . "&exp=e", 'SSL');
        $data['full_pdf'] = $this->url->link('mailing/lista_mail_rebotados/exp', 'token=' . $this->session->data['token'] . "&exp=p", 'SSL');

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

        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . urlencode(html_entity_decode($this->request->get['filter'], ENT_QUOTES, 'UTF-8'));
        }


        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }


        $data['sort_fecha_envio'] = $this->url->link('mailing/lista_mail_rebotados', 'token=' . $this->session->data['token'] . '&sort=cueando_enviar' . $url, 'SSL');
        $data['sort_email'] = $this->url->link('mailing/lista_mail_rebotados', 'token=' . $this->session->data['token'] . '&sort=destinatario' . $url, 'SSL');                
        $data['sort_nombre_remitente'] = $this->url->link('mailing/lista_mail_rebotados', 'token=' . $this->session->data['token'] . '&sort=remitente' . $url, 'SSL'); 
        $data['sort_correo_remitente'] = $this->url->link('mailing/lista_mail_rebotados', 'token=' . $this->session->data['token'] . '&sort=correo_remitente' . $url, 'SSL'); 

        $url = '';

        if (isset($this->request->get['filter'])) {
            $url .= '&filter=' . urlencode(html_entity_decode($this->request->get['filter'], ENT_QUOTES, 'UTF-8'));
        }

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
        $pagination->url = $this->url->link('mailing/lista_mail_rebotados', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($cantidad_listas) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cantidad_listas - $this->config->get('config_limit_admin'))) ? $cantidad_listas : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cantidad_listas, ceil($cantidad_listas / $this->config->get('config_limit_admin')));

        $data['filter'] = $filter_name;

        $data['filter_fecha']  = $filter_fecha;
        $data['filter_email'] = $filter_email;
        $data['filter_remitente'] = $filter_remitente;
        $data['filter_correo_remitente'] = $filter_correo_remitente;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('mailing/lista_mail_rebotados.tpl', $data));
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
                $file_excel = $this->model_contactos_listas->rebotesExcel($id_empresa);
                $this->response->redirect($file_excel);
                $this->getList();
            }elseif ($tipo == 'p') {
                $this->model_contactos_listas->rebotesPDF($id_empresa);
            }
        }
    }

    public function getEnvio(){
        $id_envio = $this->request->post['id_envio'];

        $this->load->model('mailing/envio');  

        $result_rebotados = $this->model_mailing_envio->getReenvio($id_envio);

        $mensaje = html_entity_decode($result_rebotados[0]['cuerpo'],ENT_QUOTES,'UTF-8');

        $result_rebotados[0]['cuerpo'] = $mensaje;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result_rebotados[0]));

    }


    public function reenviar(){


        $this->load->extern_library('ConnectusController');            
        $api = new ConnectusController();

        $id_envio = $this->request->get['id_envio'];

        $this->load->model('mailing/envio');  

        $result_rebotados = $this->model_mailing_envio->getReenvio($id_envio);  

        if ($result_rebotados[0]['email'] != '') {

            $id_empresa = $this->session->data['id_empresa'];
            $results = $api->sendMail($result_rebotados[0]['nombre_remitente'], $result_rebotados[0]['correo_remitente'], $result_rebotados[0]['email'], $result_rebotados[0]['titulo'], $result_rebotados[0]['cuerpo'],'connectusKey', $id_empresa ,'reenviar', $id_envio);        

            if (!empty($results)) {
                $this->session->data['success'] = "Se a reeenviado su mensaje.";
            }else{
                $this->error['warning'] = "No se pudo reenviar su email.";
            }
            

            $this->response->redirect($this->url->link('mailing/lista_mail_rebotados','token=' . $this->session->data['token'],'SSL'));   

        }else{
            
            $this->error['warning'] = "Este envio no posee un destinatario";
            $this->getList();      

        }

             

    }



}