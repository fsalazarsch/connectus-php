<?php
class ControllerSmsEstadisticas extends Controller
{
    public function index()
    {
        $this->load->language('sms/historial');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->getList();
    }

    public function getList()
    {
        $this->load->language('sms/historial');
        $data['breadcrumbs'] = array();


        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $url = '';

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sms/historial', 'token=' . $this->session->data['token'] . $url, 'SSL')
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

         if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }


        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->get['envio'])) {
            $id_envio = $this->request->get['envio']; 
            $url .= '&envio=' . $this->request->get['envio'];  

        }else{
            $this->response->redirect($this->url->link('sms/historial','token=' . $this->session->data['token'],'SSL'));
        }

        
        if (isset($this->request->get['est'])) {
            if (strtolower($this->request->get['est']) != 'totales') {
                $filtro = $this->request->get['est'];
                $url .= "&est=" . $filtro;
            }else{
                $filtro = NULL;
            }                     
        }else{
            $filtro = NULL;
        } 

        $data['filt']           =  $filtro;
        $data['btn_confirmado'] =  $this->url->link('sms/estadisticas', 'token=' . $this->session->data['token'] . $url . "&est=confirmados"  , 'SSL');
        $data['confirmados']    =  $this->language->get('Confirmados');

        $data['btn_esperando']  =  $this->url->link('sms/estadisticas', 'token=' . $this->session->data['token'] . $url . "&est=esperando" , 'SSL');
        $data['esperando']      =  $this->language->get('esperando');

        $data['btn_error']      =  $this->url->link('sms/estadisticas', 'token=' . $this->session->data['token'] . $url . "&est=error", 'SSL');
        $data['error']          =  $this->language->get('error');

        $data['btn_total']      =  $this->url->link('sms/estadisticas', 'token=' . $this->session->data['token'] . $url . "&est=totales" , 'SSL');
        $data['todos']          =  $this->language->get('totales');

        /*Exportar*/
        $data['exp_excel'] = $this->url->link('sms/estadisticas/exp', 'token=' . $this->session->data['token'] . $url . "&exp=e" , 'SSL');
        $data['exp_pdf']   = $this->url->link('sms/estadisticas/exp', 'token=' . $this->session->data['token'] . $url . "&exp=p" , 'SSL');

        $filter_data = array(
                        'filter' => $filtro,
                        'start'  => ($page - 1) * $this->config->get('config_limit_admin'),
                        'limit'  => $this->config->get('config_limit_admin')
        );

        $exportar = array( 'filter' => $filtro  );


        $data['filtro_empresa'] = $this->url->link('sms/estadisticas', 'token=' . $this->session->data['token'] . $url , 'SSL');
        $data['historial'] = $this->url->link('sms/historial','token=' . $this->session->data['token'],'SSL');
        $data['add']       = $this->url->link('sms/enviar_sms','token=' . $this->session->data['token'],'SSL');


        $this->load->model('sms/envio');
        //$data['headers'] = array('Carrier','Confirmados','Esperando','Error', 'Volumen');
        $data['headers'] = array('Carrier','Confirmados','Esperando','No entregados', 'Volumen');
        
        $estadisticas = $this->model_sms_envio->desgloseEnvio($id_envio);            
        $data['detalles'] = $this->model_sms_envio->contarDetalle($id_envio);

        /*datos para tabla de resumen por empresa telefonica*/
        //$data['totales'] = $this->model_sms_envio->desgloseEnvio($id_envio);
        $data['totales'] = $estadisticas;
        $cant = $this->model_sms_envio->contarDetalle($id_envio,$filtro);


        //trae los detalles por cada empresa
        $data['registros'] = $this->model_sms_envio->detallesPorEnvio($id_envio,$filter_data,$filtro);
        $this->session->data['exportar'] = $this->model_sms_envio->detallesPorEnvio($id_envio,$exportar,$filtro);

        
        $data['estadistica'] = $estadisticas;
        $data['chart_name']  = $this->model_sms_envio->get_nombre_envio($id_envio);
        
        $data['excel_tip'] = $this->language->get('excel_tip');
        $data['pdf_tip']   = $this->language->get('pdf_tip');

        $data['tipo'] = $this->language->get('tipo');
        $data['valores'] = $this->language->get('valores');
        $data['entregados'] = $this->language->get('entregados');
        $data['malos'] = $this->language->get('malos');
        $data['rebotes'] = $this->language->get('rebotes');
        $data['leidos'] = $this->language->get('leidos');
        $data['click'] = $this->language->get('click');

        $data['titulo'] = $this->language->get('txt_modal');  
        $data['aceptar']   = $this->language->get('aceptar');

        $data['nombre'] =  $this->language->get('nombre');
        $data['fecha'] =  $this->language->get('fecha');
        $data['id'] =  $this->language->get('id');
        $data['mensaje']=  $this->language->get('mensaje');
        $data['estado'] =  $this->language->get('estado');
        $data['destinatario'] =  $this->language->get('destinatario');
        $data['compania'] =  $this->language->get('compania');


        $data['text_no_result'] =  $this->language->get('text_no_results');


        if (isset($this->request->get['page'])) {
            $page =  $this->request->get['page'];
        }else{
            $page=1;
        }

        /*paginacion*/
        $pagination = new Pagination();
        $pagination->total = $cant;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sms/estadisticas', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($cant) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cant - $this->config->get('config_limit_admin'))) ? $cant : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cant, ceil($cant / $this->config->get('config_limit_admin')));
       

        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sms/estadistica.tpl', $data));
    }

    public function exp()
    {
        //datos a exportar 
        $datos = $this->session->data['exportar'];
        $this->load->library('FileManager');

        $filer = new FileManager();

        $headers    = array('Nombre Envio', 'Fecha Envío','Mensaje','Compañia Telefonica', 'Estado', 'Destinatario');
        $no_incluir = array('numero');

        if (isset($this->request->get['exp'])) {

            if ($this->request->get['exp']=='e') {
                $filer->excel($headers, $datos, 'Detalles de envio', $no_incluir);
            }else{
                $filer->pdf($headers, $datos, 'Detalles de envio', $no_incluir);
            }
            
        }

    }

}