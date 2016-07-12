<?php
class ControllerSmsHistorial extends Controller{
    public function index() {
        $this->load->language('sms/historial');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->getList();
    }

    public function getList(){
        
        $this->load->language('sms/historial');
        $this->load->model('mailing/enviar_mail');


        $this->document->setTitle($this->language->get('heading_title'));


        /*recoger variables para filtrar*/
        if(isset($this->request->get['tipo'])){
            $tipo = $this->request->get['tipo'];
        }else{
            $tipo = 'masivo';
        }

        if (isset($this->request->get['filter_nombre'])) {
            $filter_nombre = $this->request->get['filter_nombre'];
        } else {
            $filter_nombre = null;
        }

         if (isset($this->request->get['filter_fecha'])) {
            $filter_fecha = $this->request->get['filter_fecha'];
        } else {
            $filter_fecha = null;
        }

        $data['filter_nombre']  = $filter_nombre;
        $data['filter_fecha']  = $filter_fecha;

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'cuando_enviar';
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

        /*colocar en la url los parametros de filtro y orden de datos*/
        if (isset($this->request->get['filter_nombre'])) {
            $url .= '&filter_nombre=' . urlencode(html_entity_decode($this->request->get['filter_nombre'], ENT_QUOTES, 'UTF-8'));
        }

         if (isset($this->request->get['filter_fecha'])) {
            $url .= '&filter_fecha=' . urlencode(html_entity_decode($this->request->get['filter_fecha'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['tipo'])) {
            $url .= '&tipo=' . $this->request->get['tipo'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }else {
            $url .= '&order=DESC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        } 

        $data['volumen'] = $tipo;

        $filter_data = array(
            'filter_nombre'     => $filter_nombre,
            'filter_fecha'     => $filter_fecha,
            'tipo_mensaje'    => 'SMS',
            'tipo_envio'      => $tipo,
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );

        /*filtros sin limits para exportar los datos filtrados */
        $this->session->data['exportar'] = array(
            'filter_nombre'     => $filter_nombre,
            'filter_fecha'     => $filter_fecha,
            'tipo_mensaje'    => 'SMS',
            'tipo_envio'      => $tipo,
            'sort'            => $sort,
            'order'           => $order
        );


        $data['tipo'] = "SMS";
        $data['token'] = $this->session->data['token'];
        $this->load->model('sms/envio');
        $id_usuario = $this->session->data['user_id'];


        //FILTRO POR TIPO PARA MOSTRAR TABLA Y COLUMNAS ADECUADAS
        if ($tipo == 'masivo') {

            $historial = $this->model_sms_envio->getEnvioSMSUsuario($id_usuario,$filter_data);
            $this->session->data['historial_data'] = $historial;

            //Se saca malos
            //$data['headers'] = array('Fecha','Nombre','Tipo Envio','Estado','Volumen','Confirmados','Malos','Error','Esperando Confirmar','Estadisticas');
            $data['headers'] = array('Fecha','Nombre','Tipo Envio','Estado','Volumen','Confirmados','No entregados','Esperando Confirmar','Estadisticas');

            //TOTAL PARA PAGINACION
            $cantidad_envios = $this->model_sms_envio->cantidadDeEnvios($id_usuario,$tipo,'SMS',$filter_data);
        }else{
            
            $historial = $this->model_sms_envio->getEnvioSMSUnico($id_usuario,$filter_data);
            $this->session->data['historial_data'] = $historial;

            $data['headers'] = array('Fecha','Destino','Carrier','Mensaje','Estado');

            //TOTAL PARA PAGINACION
            $cantidad_envios = $this->model_sms_envio->cantidadDeEnvios($id_usuario,$tipo,'SMS');
        }

        $data['envios'] = $historial;
        $data['api'] = $this->language->get('api');
        $data['unico'] = $this->language->get('unico');
        $data['masivo'] = $this->language->get('masivo');
        $data['refresh'] = $this->language->get('refresh');
        $data['nuevo'] = $this->language->get('nuevo');
        $data['titulo'] = $this->language->get('titulo');
        $data['detalle'] = $this->language->get('detalle');

        $data['btn_filtrar'] = $this->language->get('btn_filtrar');
        $data['titulo'] = $this->language->get('titulo_sms');
        $data['detalle'] = $this->language->get('detalle');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['export_excel'] = $this->language->get('btn_exportar_excel');
        $data['export_pdf'] = $this->language->get('btn_exportar_pdf');

        $data['btn_excel'] = $this->url->link('sms/historial/exp', 'token=' . $this->session->data['token'] . $url . '&exp=e', 'SSL');
        $data['btn_pdf'] =$this->url->link('sms/historial/exp', 'token=' . $this->session->data['token'] . $url . '&exp=p', 'SSL');
        $data['btn_api'] = $this->url->link('sms/historial', 'token=' . $this->session->data['token'] . $url . '&tipo=api', 'SSL');
        $data['btn_unico'] = $this->url->link('sms/historial', 'token=' . $this->session->data['token'] . $url . '&tipo=unico', 'SSL');
        $data['btn_masivo'] = $this->url->link('sms/historial', 'token=' . $this->session->data['token'] . $url . '&tipo=masivo', 'SSL');
        $data['btn_nuevo'] = $this->url->link('sms/enviar_sms', 'token=' . $this->session->data['token'] . $url , 'SSL');
        $data['btn_refresh'] = $this->url->link('sms/historial', 'token=' . $this->session->data['token'] . $url , 'SSL');
       // $data['filtrar'] = $this->url->link('sms/historial', 'token=' . $this->session->data['token'] . $url . '&tipo=masivo', 'SSL');

        /*breadcrumbs de navegacion*/
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sms/historial', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
        
        /*paginacion y numeracion*/
        $pagination = new Pagination();
        $pagination->total = $cantidad_envios;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sms/historial', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');      

         $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($cantidad_envios) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cantidad_envios - $this->config->get('config_limit_admin'))) ? $cantidad_envios : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cantidad_envios, ceil($cantidad_envios / $this->config->get('config_limit_admin')));


        //$result = $this->model_mailing_enviar_mail->historialEnviosMail($id_usuario,'masivo');
        //array_push($result, array());

        $data['estadistica'] = $this->url->link('sms/estadisticas', 'token=' . $this->session->data['token'] , 'SSL');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sms/historial.tpl', $data));
    
    }

    public function refresh(){
        $this->load->model('sms/envio');        
        $this->model_sms_envio->refrescar($this->session->data['user_id']);
    }

    public function exp(){
        $this->load->model('contactos/listas');
        if (isset($this->request->get['exp'])) {
            $tipo = $this->request->get['exp'];
        }else{
            $tipo = '';
        }

        $user_id = $this->session->data['user_id'];
        $this->load->model('sms/envio');
        $this->load->library('FileManager');
        $filer = new FileManager();

        $headers = array('Fecha','Nombre','Tipo Envío', 'Estado', "Volumen", 'No entregados','Confirmados', 'Esperando Confirmar');
        $no_incluir = array('id_envio','cant_detalles');

        $historial = $this->model_sms_envio->getEnvioSMSUsuario($user_id, $this->session->data['exportar']);

        if($tipo!=''){
            if($tipo == 'e'){
                $filer->excel($headers,$historial,' Hostorial SMS', $no_incluir);
            }elseif ($tipo == 'p') {
                 $filer->pdf($headers,$historial,' Hostorial SMS', $no_incluir);
            }
        }
    }
}