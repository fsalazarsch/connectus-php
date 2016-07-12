<?php
class ControllerMailingEstadistica extends Controller
{
    public function index()
    {
        $this->load->language('mailing/estadistica');
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->getList();
    }

    public function getList()
    {
        $this->load->language('mailing/historial');
        $this->document->setTitle($this->language->get('heading_title'));
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
        );

        $url = '';

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('mailing/historial', 'token=' . $this->session->data['token'] . $url, 'SSL')
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

        if (isset($this->request->get['envio'])) {
            $id_envio = $this->request->get['envio'];
            $url .= "&envio=" . $id_envio;           

        }else{
            $this->response->redirect($this->url->link('mailing/historial','token=' . $this->session->data['token'],'SSL'));
        }

        if (isset($this->request->get['page'])) {
            $page =  $this->request->get['page'];
        }else{
            $page = 1;
        }

        if (isset($this->request->get['est'])) {
            $filtro = $this->request->get['est'];
            $url .= "&est=".$filtro;
        }else{
            $filtro = 'totales';
        }

        $data['filt']           =  $filtro;
        $data['btn_entregados'] =  $this->url->link('mailing/estadistica', 'token=' . $this->session->data['token'] . $url . "&est=entregado"  , 'SSL');
        $data['btn_esperando']  =  $this->url->link('mailing/estadistica', 'token=' . $this->session->data['token'] . $url . "&est=esperando"  , 'SSL');
        $data['btn_rebotes']    =  $this->url->link('mailing/estadistica', 'token=' . $this->session->data['token'] . $url . "&est=rebote"  , 'SSL');
        $data['btn_abiertos']   =  $this->url->link('mailing/estadistica', 'token=' . $this->session->data['token'] . $url . "&est=abierto"  , 'SSL');
        $data['btn_click']      =  $this->url->link('mailing/estadistica', 'token=' . $this->session->data['token'] . $url . "&est=click"  , 'SSL');
        $data['btn_spam']       =  $this->url->link('mailing/estadistica', 'token=' . $this->session->data['token'] . $url . "&est=spam"  , 'SSL');
        $data['btn_totales']    =  $this->url->link('mailing/estadistica', 'token=' . $this->session->data['token'] . $url . "&est=totales"  , 'SSL');
        $data['btn_malos']      =  $this->url->link('mailing/estadistica', 'token=' . $this->session->data['token'] . $url . "&est=malo"  , 'SSL');

        $filter_data = array(
            'filter'          => $filtro,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );

        $filter = array(  'filter' => $filtro );

        $data['text_no_results'] =  $this->language->get('text_no_results');

        $this->load->model('mailing/envio');


        $estadisticas = $this->model_mailing_envio->estadisticaEnvio($id_envio,$filter_data);



        $data['detalles'] = $this->model_mailing_envio->detallesPorEnvio($id_envio,$filter_data);

        // obtenemos el asunto del envío
        $data['asunto']     = $estadisticas['asunto'];
        $data['remitente']  = $estadisticas['remitente'];
        

        //Problema en corrección 
        //$this->session->data['exportar'] = $this->model_mailing_envio->detallesPorEnvio($id_envio,$filter);
        unset($this->session->data['filter_data']);
        $this->session->data['idmail'] = $id_envio;
        $this->session->data['filter_data'] = $filtro;

        $data['mensaje_enviado'] = $this->model_mailing_envio->getMensajeDeEnvio($id_envio);


        $data['btn_mensaje'] = $this->url->link('mailing/estadistica/mensaje', 'token=' . $this->session->data['token'] . '&envio=' . $id_envio, 'SSL');

        $data['estadistica'] = $estadisticas;

        $data['tipo'] = $this->language->get('tipo');
        $data['valores'] = $this->language->get('valores');
        $data['entregados'] = $this->language->get('entregados');
        $data['esperando']  = $this->language->get('esperando');
        $data['malos'] = $this->language->get('malos');
        $data['rebotes'] = $this->language->get('rebotes');
        $data['abiertos'] = $this->language->get('abiertos');
        $data['leidos'] = $this->language->get('leidos');
        $data['click'] = $this->language->get('click');
        $data['malo'] = $this->language->get('malo');
        $data['spam'] = $this->language->get('spam');

        $data['historial'] = $this->url->link('mailing/historial','token=' . $this->session->data['token'],'SSL');
        $data['add']       = $this->url->link('mailing/enviar_mail','token=' . $this->session->data['token'],'SSL');
        

        $data['titulo'] = $this->language->get('txt_modal');  
        $data['aceptar']   = $this->language->get('aceptar');

        $data['nombre'] = 'Nombre del Envío';
        $data['mensaje']= 'Mensaje Enviado';
        $data['estado'] = 'Estado';
        $data['destinatario'] = 'Destinatario';

        

        $cant = $this->model_mailing_envio->contarDetalle($id_envio, $filter_data);        

        /*paginacion*/
        $pagination = new Pagination();
        $pagination->total = $cant;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('mailing/estadistica', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($cant) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cant - $this->config->get('config_limit_admin'))) ? $cant : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cant, ceil($cant / $this->config->get('config_limit_admin')));
       
        $data['excel_tip'] = $this->language->get("excel");
        $data['pdf_tip']   = $this->language->get("pdf");

        $data['excel'] =  $this->url->link('mailing/estadistica/exp','token=' . $this->session->data['token'] . "&exp=e",'SSL');
        $data['pdf'] =  $this->url->link('mailing/estadistica/exp','token=' . $this->session->data['token'] . "&exp=p",'SSL');
    
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('mailing/estadistica.tpl', $data));
    }

    public function mensaje()
    {
        if (isset($this->request->get['envio'])) {
            $id_envio = $this->request->get['envio'];
        }

        if (isset($this->request->get['cont'])) {
            $cont = $this->request->get['cont'];
        }

        if (isset($this->request->get['d'])) {
            $dest = $this->request->get['d'];
        }

        if (isset($id_envio) && isset($cont)) {
            $this->load->model('mailing/envio');
            $this->load->language('mailing/historial');

            $data['title'] = $this->language->get('msg_title');

            $mensaje = $this->model_mailing_envio->decodMensaje($id_envio,$cont,$dest);


           
            $message1 = "<br><div style='font-family:Verdana, Arial, Helvetica, sans-serif; text-align:center; font-size:10px; color:#999999;' valign='top'>
                        Para asegurar la entrega de nuestros e-mail en su correo, por favor agregue <span style='color:#0073ae'>".$mensaje['correo']."</span> a su libreta de direcciones<br>
                        Si usted no visualiza bien este mail, haga <a href='" . HTTPS_CATALOG . "viewmail/index2.php?id_contacto=".$mensaje['id_contacto']."&id_envio=".$mensaje['id_envio']."' target='_blank'>click aqu&iacute;</a></div>"  . "\n";       
            
            if ($mensaje['tipo']=='unico') {
                $message2 = '';
                $data['tipo'] = 'unico';
            }else{
                $message2 = " <br> <footer style='font-family:Verdana, Arial, Helvetica, sans-serif; text-align:center; font-size:10px; color:#999999;' valign='top'>Este correo electrónico fue enviado a ".$mensaje['destinatario']." <br>Para anular tu suscripción haz clic <a href='" . HTTPS_CATALOG . "admin/index.php?route=common/login&id_lista=@id_lista&id_envio=".$mensaje['id_envio']."&id_contacto=".$mensaje['id_contacto']."'>aquí</a></footer>" . "\n"; 
                $data['tipo'] = 'masivo';
            }
                    

            $data['mensaje_decod'] = html_entity_decode($mensaje['mensaje'],ENT_QUOTES,'UTF-8');

            $data['mensaje1'] = $message1;
            $data['mensaje2'] = $message2;

            $this->response->setOutput($this->load->view('mailing/email_preview.tpl', $data));
        }
    }


    public function exp()
    {
        $this->load->library('FileManager');
        $this->load->model('mailing/envio');

        $filtro = $this->session->data['filter_data'];
        $filter_data = array( 'filter'   => $filtro );


        $datos = $this->model_mailing_envio->detallesPorEnvio($this->session->data['idmail'], $filter_data);

        $filer = new FileManager();

        $headers    = array('Fecha Envío','Asunto','Estado', 'Remitente', 'Destinatario', 'Abierto total', 'Click', 'Spam');
        $no_incluir = array('numero','id_contacto','nombre_envio','mensaje');


        if (isset($this->request->get['exp'])) {

            if ($this->request->get['exp']=='e') {
               $filer->excel($headers, $datos, 'Detalles de Envio', $no_incluir);
            }else{
                $filer->pdf($headers, $datos, 'Detalles de Envio', $no_incluir);
            }
            
        }

    }
}
