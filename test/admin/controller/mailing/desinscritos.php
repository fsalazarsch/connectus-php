<?php
class ControllerMailingDesinscritos extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('mailing/desinscritos');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->getList();
    }

    protected function getList() {

        $this->load->language('mailing/desinscritos');

        
        if (isset($this->request->get['filter_fecha'])) {
            $filter_fecha = $this->request->get['filter_fecha'];
        } else {
            $filter_fecha = null;
        }        

        if (isset($this->request->get['filter_remitente'])) {
            $filter_remitente = $this->request->get['filter_remitente'];
        } else {
            $filter_remitente = null;
        }

        if (isset($this->request->get['filter_destino'])) {
            $filter_destino = $this->request->get['filter_destino'];
        } else {
            $filter_destino = null;
        }

        if (isset($this->request->get['filter_campania'])) {
            $filter_campania = $this->request->get['filter_campania'];
        } else {
            $filter_campania = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'd.id_desinscrito';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
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
            $page = $this->request->get['page'];
        }else {
            $page = 1;
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
            'href' => $this->url->link('mailing/desinscritos', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );


        $filter_data = array(
            'filter_fecha'    => $filter_fecha,
            'filter_remitente'=> $filter_remitente ,
            'filter_destino'  => $filter_destino,
            'filter_campania' => $filter_campania,
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );
        
        $data['tipo_usuario'] = $this->session->data['tipo_usuario'];

        $this->load->model('mailing/desinscritos');
        $lista_contactos = array();
        $data['desinscritos'] = $this->model_mailing_desinscritos->traerDesinscritos($this->session->data['id_empresa'], $filter_data);

        $data['full_excel'] = $this->url->link('mailing/desinscritos/exp', 'token=' . $this->session->data['token'] . '&full=e'  . $url, 'SSL');
        $data['full_pdf'] = $this->url->link('mailing/desinscritos/exp', 'token=' . $this->session->data['token'] . '&full=p'  . $url, 'SSL');

        $data['inscribir'] = $this->url->link('mailing/desinscritos/inscribir', 'token=' . $this->session->data['token'] , 'SSL');
        
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_fecha']     = $this->language->get('column_fecha');
        $data['column_remitente'] = $this->language->get('column_remitente');
        $data['column_mail']  = $this->language->get('column_mail');
        $data['column_campania']  = $this->language->get('column_compania');
        $data['column_action']    = $this->language->get('column_action');

        $data['entry_nombre'] = $this->language->get('entry_nombre');
        $data['entry_contactos'] = $this->language->get('entry_contactos');
        $data['entry_fecha_creacion'] = $this->language->get('entry_fecha_creacion');


        $data['button_export_excel'] = $this->language->get('button_export_excel');
        $data['button_export_pdf'] = $this->language->get('button_export_pdf');
        $data['button_export_excel_all'] = $this->language->get('button_export_excel_all');
        $data['button_export_pdf_all'] = $this->language->get('button_export_pdf_all');

        $data['button_delete'] = $this->language->get('inscribir');
       

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

        if (isset($this->request->get['filter_fecha'])) {
            $url .= '&filter_fecha=' . urlencode(html_entity_decode($this->request->get['filter_fecha'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_remitente'])) {
            $url .= '&filter_remitente=' . urlencode(html_entity_decode($this->request->get['filter_remitente'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_destino'])) {
            $url .= '&filter_destino=' . urlencode(html_entity_decode($this->request->get['filter_destino'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_campania'])) {
            $url .= '&filter_campania=' . urlencode(html_entity_decode($this->request->get['filter_campania'], ENT_QUOTES, 'UTF-8'));
        }


        $data['filter_fecha']     = $filter_fecha;
        $data['filter_remitente'] = $filter_remitente;
        $data['filter_destino']   = $filter_destino;
        $data['filter_campania']  = $filter_campania;


        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        $data['sort_fecha'] = $this->url->link('mailing/desinscritos', 'token=' . $this->session->data['token'] . '&sort=fecha' . $url, 'SSL');
        $data['sort_remitente'] = $this->url->link('mailing/desinscritos', 'token=' . $this->session->data['token'] . '&sort=remitente' . $url, 'SSL');                
        $data['sort_destino'] = $this->url->link('mailing/desinscritos', 'token=' . $this->session->data['token'] . '&sort=destino' . $url, 'SSL');                
        $data['sort_campania'] = $this->url->link('mailing/desinscritos', 'token=' . $this->session->data['token'] . '&sort=campania' . $url, 'SSL');                

        /*$url = '';        

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }*/

        /* cantidad de registros */
        $this->load->model('mailing/desinscritos');
        $cantidad_listas = $this->model_mailing_desinscritos->cantidad_desinscritos($this->session->data['id_empresa'],$filter_data);
        
        $pagination = new Pagination();
        $pagination->total = $cantidad_listas;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('mailing/desinscritos', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($cantidad_listas) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cantidad_listas - $this->config->get('config_limit_admin'))) ? $cantidad_listas : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cantidad_listas, ceil($cantidad_listas / $this->config->get('config_limit_admin')));
       

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('mailing/desinscritos.tpl', $data));
    }

    public function inscribir()
    {
        $this->load->language('mailing/desinscritos');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('contactos/listas');
        $this->load->model('contactos/contacto');
        
        $url = '';

        if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $id_contacto) {
                $this->model_contactos_contacto->inscribirContacto($id_contacto);
            }


            if (isset($this->request->get['id_lista'])) {
                $id_lista = $this->request->get['id_lista'];
                $url .= '&id_lista=' . $id_lista;
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
            
        }
        
        $this->response->redirect($this->url->link('mailing/desinscritos', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
                $file_excel = $this->model_contactos_listas->excel_desinscritos($id_empresa);
                $this->response->redirect($file_excel);
                $this->getList();
            }elseif ($tipo == 'p') {
                $this->model_contactos_listas->pdf_desinscritos($id_empresa);
            }
        }
    }

}