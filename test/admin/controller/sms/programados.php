<?php   //DESARROLLO
class ControllerSmsProgramados extends Controller{

    public function index() {

        $this->load->language('sms/programados');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->getList();
    }

    public function getList(){
        
        $this->load->language('sms/programados');
        $this->load->model('sms/programados');


        $this->document->setTitle($this->language->get('heading_title'));


        /*$programados = $this->model_sms_programados->getSMSProgramados();

        echo "<pre>";
        print_r($programados);*/


        

        #recoger variables para filtrar
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

        #colocar en la url los parametros de filtro y orden de datos
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

        
        unset($this->session->data['filter_data']);
        $this->session->data['filter_data'] = $filter_data;

        $data['tipo'] = "SMS";
        $data['token'] = $this->session->data['token'];

       # $this->load->model('sms/envio');

        $id_usuario = $this->session->data['user_id'];


        //FILTRO POR TIPO PARA MOSTRAR TABLA Y COLUMNAS ADECUADAS
        if ($tipo == 'masivo') {

            #$historial      = $this->model_sms_envio->getEnvioSMSUsuario($id_usuario,$filter_data);
            $programados    = $this->model_sms_programados->getSMSProgramados($id_usuario,$filter_data);


            $data['headers'] = array('Fecha','Nombre','Tipo Envio','Estado','Volumen');

            //TOTAL PARA PAGINACION
            $cantidad_envios = $this->model_sms_programados->cantidadDeEnvios($id_usuario,$tipo,'SMS', $filter_data);
        }else{
            
            #$historial      = $this->model_sms_envio->getEnvioSMSUnico($id_usuario,$filter_data);
            $programados    = $this->model_sms_programados->getSMSUnicoProgramado($id_usuario,$filter_data);
            
            $data['headers'] = array('Fecha','Destino','Mensaje','Estado');

            //TOTAL PARA PAGINACION
            $cantidad_envios = $this->model_sms_programados->cantidadDeEnvios($id_usuario,$tipo,'SMS', $filter_data);
        }

        #$data['envios'] = $historial;
        $data['envios'] = $programados;
        $data['api'] = $this->language->get('api');
        $data['unico'] = $this->language->get('unico');
        $data['masivo'] = $this->language->get('masivo');
        $data['refresh'] = $this->language->get('refresh');
        $data['nuevo'] = $this->language->get('nuevo');

        $data['eliminar'] = $this->language->get('eliminar');
        $data['editar'] = $this->language->get('editar');

        $data['titulo'] = $this->language->get('titulo');
        $data['detalle'] = $this->language->get('detalle');

        $data['btn_filtrar'] = $this->language->get('btn_filtrar');
        $data['titulo'] = $this->language->get('titulo_sms');
        $data['detalle'] = $this->language->get('detalle');

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_no_results'] = $this->language->get('text_no_results');

        $data['export_excel'] = $this->language->get('btn_exportar_excel');
        $data['export_pdf'] = $this->language->get('btn_exportar_pdf');

        $data['btn_excel'] = $this->url->link('sms/programados/exp', 'token=' . $this->session->data['token'] . $url . '&exp=e', 'SSL');
        $data['btn_pdf'] =$this->url->link('sms/programados/exp', 'token=' . $this->session->data['token'] . $url . '&exp=p', 'SSL');
        $data['btn_api'] = $this->url->link('sms/programados', 'token=' . $this->session->data['token'] . $url . '&tipo=api', 'SSL');
        $data['btn_unico'] = $this->url->link('sms/programados', 'token=' . $this->session->data['token'] . $url . '&tipo=unico', 'SSL');
        $data['btn_masivo'] = $this->url->link('sms/programados', 'token=' . $this->session->data['token'] . $url . '&tipo=masivo', 'SSL');
        $data['btn_nuevo'] = $this->url->link('sms/enviar_sms', 'token=' . $this->session->data['token'] . $url , 'SSL');
        $data['btn_refresh'] = $this->url->link('sms/programados', 'token=' . $this->session->data['token'] . $url , 'SSL');
       // $data['filtrar'] = $this->url->link('sms/historial', 'token=' . $this->session->data['token'] . $url . '&tipo=masivo', 'SSL');

        # breadcrumbs de navegacion
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sms/programados', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );
        
        # paginacion y numeracion
        $pagination = new Pagination();
        $pagination->total = $cantidad_envios;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('sms/programados', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');      

         $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($cantidad_envios) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cantidad_envios - $this->config->get('config_limit_admin'))) ? $cantidad_envios : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cantidad_envios, ceil($cantidad_envios / $this->config->get('config_limit_admin')));

        //agregado 18/12/15 para bloquear bonotes exportar cuando la cantidad de registros sea cero
        $data['generar'] = $cantidad_envios;

        //$result = $this->model_mailing_enviar_mail->historialEnviosMail($id_usuario,'masivo');
        //array_push($result, array());

        $data['estadistica'] = $this->url->link('sms/estadisticas', 'token=' . $this->session->data['token'] , 'SSL');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('sms/programados.tpl', $data));
        

        
    }

    public function refresh(){
        $this->load->model('sms/envio');        
        $this->model_sms_envio->refrescar($this->session->data['user_id']);
    }

    public function exp(){
        $this->load->model('sms/programados');
        $this->load->library('FileManager');
        
        $filter_data = $this->session->data['filter_data'];
        //eliminar paginacion para exportar todos los elementos.
        unset($filter_data['start']);
        unset($filter_data['limit']);


        if ($filter_data['tipo_envio'] == "masivo") {


            $headers = array("Fecha Envio","Nombre Envio","Tipo", "Estado","Volumen");            
            $no_incluir = array('id_envio','cant_detalles');


            $historial = $this->model_sms_programados->getSMSProgramados($this->session->data['user_id'], $filter_data);


            
        }elseif ($filter_data['tipo_envio'] == "unico" || $filter_data['tipo_envio'] == "api") {
            $headers = array('Fecha','Asunto','Destinatario','Tipo','Mensaje','Estado');
            $no_incluir = array('id_envio','carrier');

            $historial = $this->model_sms_programados->getSMSUnicoProgramado($this->session->data['user_id'], $filter_data);
        }

        $filer = new FileManager();
        
        if (isset($this->request->get['exp'])) {
            if ($this->request->get['exp']=='e') {
                $filer->excel($headers, $historial, 'SMS Programados',$no_incluir);
            }else{
                $filer->pdf($headers, $historial, 'SMS Programados',$no_incluir);
            }
         }
    }

    public function delete(){

        $sms = $_POST['sms'];
        $data['sms'] = $sms;        

        if(!empty($sms)){
            $sms = explode(',', $sms);

            $this->load->model('sms/envio'); 

            foreach ($sms as $idsms) {

                # obtenemos el id de consumo
                $id_consumo = $this->model_sms_envio->getIDConsumo($idsms);
                
                # eliminamos el envío
                $estado = $this->model_sms_envio->deleteEnvio($idsms);#ID_ENVIO
                $data['eliminados'][] = $idsms;

                # eliminamos el consumo
                $this->model_sms_envio->deleteConsumo($id_consumo);
            }

            $data['estado']     =   true;
            $this->session->data['success'] = "Los mensajes se han eliminado con exito.";    
        }else{
            $this->session->data['success'] = 'Ups, algo salió mal. Vuelve a intentar.';
        }
    
        echo json_encode($data);
    }

    public function getDataSMS()
    {
        $idsms = $_POST['sms'];

        if (!empty($idsms)) {
                
            $this->load->model('sms/envio');      
            $datos = $this->model_sms_envio->getDatoSMS($idsms);#ID_ENVIO

            $data['nombre_envio']   = $datos[0]['nombre_envio'];

            $fecha = explode(' ', $datos[0]['cuando_enviar']);
            $data['fecha'] = $fecha[0];

            $hora = explode(':', $fecha[1]);

            $data['hora'] = $hora[0].":".$hora[1];

            $data['estado'] = true;

        }else{
            $data['estado'] = false;
            $data['error'] = "Error con las variables.";
        }

        echo json_encode($data);
    }

    public function updSMS()
    {
        $idsms = $_POST['sms'];
        $fecha = $_POST['fecha'];
        $hora  = $_POST['hora'];

        $data['estado'] = true;

        if(!isset($hora)){
            $this->session->data['success'] = 'Ups, algo salió mal. Vuelve a intentar.';
            $data['error'] = "Problemas con la variable idsms.";
            $data['estado'] = false;
        }

        if(!isset($hora)){
            $this->session->data['success'] = 'Ups, algo salió mal. Vuelve a intentar.';
            $data['error'] = "Problemas con la variable hora.";
            $data['estado'] = false;
        }

        if(!isset($fecha)){
            $this->session->data['success'] = 'Ups, algo salió mal. Vuelve a intentar.';
            $data['error'] = "Problemas con la variable fecha.";
            $data['estado'] = false;
        }

        if($data['estado'])
        {

            $fecha = $fecha." ".$hora;
            $this->load->model('sms/envio');      
            $estado = $this->model_sms_envio->updSMSProgramado($idsms, $fecha);#ID_ENVIO

            if($estado)
            {
                $this->session->data['success'] = 'Se ha actualizado el SMS.';
            }else{
                $this->session->data['success'] = 'Ups, algo salió mal. Vuelve a intentar.';
            }
        }
        


        echo json_encode($data);
    }
}