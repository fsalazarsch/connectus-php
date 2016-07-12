<?php   //DESARROLLO
class ControllerMailingHistorial extends Controller{
    public function index() {
        $this->load->language('mailing/historial');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->getList();
    }

    public function getList(){
        
        $this->load->language('mailing/historial');
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

        

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        $data['volumen'] = $tipo;

        $filter_data = array(
            'filter_nombre'     => $filter_nombre,
            'filter_fecha'     => $filter_fecha,
            'tipo_mensaje'    => 'MAIL',
            'tipo_envio'      => $tipo,
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );

        unset($this->session->data['filter_data']);
        $this->session->data['filter_data'] = $filter_data;

        $data['tipo'] = "MAIL";
        $data['token'] = $this->session->data['token'];
        $this->load->model('mailing/envio');
        $id_empresa = $this->session->data['id_empresa'];

        if ($tipo == 'masivo') {

            $historial = $this->model_mailing_envio->getEnvioPorUsuario($id_empresa,$filter_data);    
            //$historial =  $this->model_mailing_envio->get_historial($id_empresa,$filter_data);       

            # se quita el 'tipo'
            $data['headers'] = array('Fecha','Nombre','Estado', 'Volumen','Esperando','Entregados','Malos','Rebotes','Estadisticas');
        
            //CANTIDAD PARA PAGINACION            
            $cantidad_envios = $this->model_mailing_envio->cantidadDeEnvios($id_empresa,$tipo,'MAIL',$filter_data);
            
        }else{

            $historial = $this->model_mailing_envio->getEnvioUnicoPorUsuario($id_empresa,$filter_data);

            $data['headers'] = array('Fecha','Email Remitente','Destinatario','Nombre envío', 'Asunto','Estado', 'Abierto', 'Clicks', 'Spam' ,'Acciones');
        
            //CANTIDAD PARA PAGINACION            
            $cantidad_envios = $this->model_mailing_envio->cantidadDeEnvios($id_empresa,$tipo,'MAIL',$filter_data);
        }
        
        $data['btn_mensaje'] = $this->url->link('mailing/estadistica/mensaje', 'token=' . $this->session->data['token'], 'SSL');

        $data['envios'] = $historial;
        $data['api'] = $this->language->get('api');
        $data['unico'] = $this->language->get('unico');
        $data['masivo'] = $this->language->get('masivo');
        $data['txt_refresh'] = $this->language->get('refresh');
        $data['txt_nuevo'] = $this->language->get('nuevo');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['titulo'] = $this->language->get('heading_title');
        $data['detalle'] = $this->language->get('detalle');
        $data['btn_filtrar'] = $this->language->get('btn_filtrar');


        $data['export_excel'] = $this->language->get('btn_exportar_excel'); 
        $data['export_pdf'] = $this->language->get('btn_exportar_pdf');
        $data['refresh'] = $this->language->get('refresh');
        $data['nuevo'] = $this->language->get('nuevo');
        $data['btn_filtrar'] = $this->language->get('btn_filtrar');

        $data['btn_excel'] = $this->url->link('mailing/historial/exp', 'token=' . $this->session->data['token'] . $url . '&exp=e', 'SSL');
        $data['btn_pdf'] =$this->url->link('mailing/historial/exp', 'token=' . $this->session->data['token'] . $url . '&exp=p', 'SSL');
        $data['btn_api'] = $this->url->link('mailing/historial', 'token=' . $this->session->data['token'] . $url . '&tipo=api', 'SSL');
        $data['btn_unico'] = $this->url->link('mailing/historial', 'token=' . $this->session->data['token'] . $url . '&tipo=unico', 'SSL');
        $data['btn_masivo'] = $this->url->link('mailing/historial', 'token=' . $this->session->data['token'] . $url . '&tipo=masivo', 'SSL');
        $data['btn_nuevo'] = $this->url->link('mailing/enviar_mail', 'token=' . $this->session->data['token'] . $url , 'SSL');
        $data['btn_refresh'] = $this->url->link('mailing/historial', 'token=' . $this->session->data['token'] . $url , 'SSL');
        $data['filtrar'] = $this->url->link('mailing/historial', 'token=' . $this->session->data['token'] . $url . '&tipo=masivo', 'SSL');

        /*breadcrumbs de navegacion*/
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('mailing/historial', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
        
        /*paginacion y numeracion*/
        $pagination = new Pagination();
        $pagination->total = $cantidad_envios;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('mailing/historial', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');      

         $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($cantidad_envios) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cantidad_envios - $this->config->get('config_limit_admin'))) ? $cantidad_envios : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cantidad_envios, ceil($cantidad_envios / $this->config->get('config_limit_admin')));

         //agregado 18/12/15 para bloquear bonotes exportar cuando la cantidad de registros sea cero
        $data['generar'] = $cantidad_envios;

        $data['estadistica'] = $this->url->link('mailing/estadistica', 'token=' . $this->session->data['token'], 'SSL');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('mailing/historial.tpl', $data));
    
    }

    public function refresh(){
        $this->load->model('mailing/envio');    
        //$this->model_mailing_envio->refrescar($this->session->data['user_id']);
        $this->model_mailing_envio->refrescar($this->session->data['id_empresa']);
    }

    public function exp(){
        $this->load->model('contactos/listas');
        $this->load->model('mailing/envio');
        $this->load->library('FileManager');

        $filter_data = $this->session->data['filter_data'];
        //eliminar paginacion para exportar todos los elementos.
        unset($filter_data['start']);
        unset($filter_data['limit']);

		if ($filter_data['tipo_envio'] == "masivo") {

			$headers = array("Fecha Envio","Nombre Envio","Tipo", "Estado","Volumen","Malos","Entregados","Rebotes");

			$no_incluir = array('id_envio', 'esperando', 'cant_detalles');

			$historial = $this->model_mailing_envio->getEnvioPorUsuario($this->session->data['id_empresa'],$filter_data);
		

        }elseif ($filter_data['tipo_envio'] == "unico" || $filter_data['tipo_envio'] == 'api') {


			$headers = array('Fecha Envio','Nombre envío','Asunto','Email Remitente','Tipo','Estado',"Abierto total","Click","Spam",'Destinatario');

            $historial = $this->model_mailing_envio->getEnvioUnicoPorUsuario($this->session->data['id_empresa'],$filter_data);
            $no_incluir = array('id_envio', 'esperando', 'cant_detalles','numero','mensaje');
		}
		
		$filer = new FileManager();
        
         if (isset($this->request->get['exp'])) {
         	if ($this->request->get['exp']=='e') {
         		$filer->excel($headers, $historial, 'Historial de Envios',$no_incluir);
         	}else{
                $filer->pdf($headers, $historial, 'Historial de Envios',$no_incluir);
         	}
         }
    }
}