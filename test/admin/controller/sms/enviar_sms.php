<?php

    require_once __DIR__.'/../../../connectus/SMS/SMSController.php';

    define ('CARACTERES_SMS', 160);

class ControllerSmsEnviarSms extends Controller {  

    private $error = array();

    public function index() {
        $this->load->language('sms/enviar_sms');        
        $this->document->setTitle($this->language->get('heading_title'));
        $this->getForm();
    }

    public function getForm(){
        $this->load->language('sms/enviar_sms');   
        $this->document->setTitle($this->language->get('heading_title')); 

        $data['text_form'] = $this->language->get('heading_title');


        $this->load->model('contactos/listas');

        $data['texto_unico'] = $this->language->get('texto_unico');
        $data['texto_masivo'] = $this->language->get('texto_masivo');
        $data['texto_nuevo'] = $this->language->get('texto_nuevo');
        $data['texto_predefinido'] = $this->language->get('texto_predefinido');
        $data['texto_campos'] = $this->language->get('texto_campos');
        $data['texto_guardar_predefinido'] = $this->language->get('texto_guardar_predefinido');
        $data['placeholder_mensaje_a_enviar'] = $this->language->get('placeholder_mensaje_a_enviar');
        $data['placeholder_nuevo_predefinido'] = $this->language->get('placeholder_nuevo_predefinido');
        $data['placeholder_titulo_envio'] = $this->language->get('placeholder_titulo_envio');
        
    
        $lista_contactos = array();

        $result_lista_contactos = $this->model_contactos_listas->getListasEmpresaPorUsuarioSms($this->session->data['id_empresa']);

        foreach( $result_lista_contactos as $row ) {
            $filtro = array('nombre' => $row['nombre'],
                               'id_lista' => $row['id_lista']);

            $lista_contactos[] = $filtro;
        }

        $data['lista_contactos'] = $lista_contactos;


        $lista_codigos = array();
        $this->load->model('codigos/listas');
        $result_lista_codigos = $this->model_codigos_listas->getLista();

        foreach( $result_lista_codigos as $row ) {
            $filtro = array('pais' => $row['pais'],
                            'codigo' => $row['codigo']);

            $lista_codigos[] = $filtro;
        }

        $data['lista_codigos'] = $lista_codigos;


        $lista_predefinidos = array();

        $this->load->model('sms/lista_sms_predefinidos');
                    
        $data['lista_preferidos'] = '';
        $result_preferidos = $this->model_sms_lista_sms_predefinidos->getSmsPredefinidos($this->session->data['id_empresa']);
        
        foreach( $result_preferidos as $row ) {
            $filtro = array('titulo' => utf8_encode($row['titulo']),
                               'cuerpo' => utf8_encode($row['cuerpo']));

            $lista_predefinidos[] = $filtro;
        }

        $data['lista_preferidos'] = $lista_predefinidos;

        if (isset($this->session->data['error_cred'])) {
            $data['error_cred'] = $this->session->data['error_cred'];
            unset($this->session->data['error_cred']);
        }else{
            $data['error_cred'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['warning'])) {
            $data['error_warning'] = $this->session->data['warning'];

            unset($this->session->data['warning']);
        } else {
            $data['error_warning'] = '';
        }        

        ///Errores de campos    
        if (isset($this->error['error_numero'])) {
            $data['error_numero'] = $this->language->get('error_numero');
        } else {
            $data['error_numero'] = '';
        }

        if (isset($this->error['error_mensaje_a_enviar'])) {
            $data['error_mensaje_a_enviar'] = $this->language->get('error_mensaje_a_enviar');
            
        } else {
            $data['error_mensaje_a_enviar'] = '';
        }

        if (isset($this->error['error_nombre_mensaje_predefinido'])) {
            $data['error_nombre_mensaje_predefinido'] = $this->language->get('error_nombre_mensaje_predefinido');
        } else {
            $data['error_nombre_mensaje_predefinido'] = '';
        }

        if (isset($this->error['error_remitente'])) {
            $data['error_remitente'] = $this->language->get('error_remitente');
        } else {
            $data['error_remitente'] = array();
        }

        if (isset($this->error['error_fecha_envio'])) {
            $data['error_fecha_envio'] = $this->language->get('error_fecha_envio');
            
        } else {
            $data['error_fecha_envio'] = '';
        }

        if (isset($this->error['error_hora_envio'])) {
            $data['error_hora_envio'] = $this->language->get('error_hora_envio');
            
        } else {
            $data['error_hora_envio'] = '';
        }


        if (isset($this->request->post['numero'])) {
            $data['numero'] = $this->request->post['numero'];
        } else {
            $data['numero'] = '';
        }

        if (isset($this->request->post['mensaje_a_enviar'])) {
            $data['mensaje_a_enviar'] = $this->request->post['mensaje_a_enviar'];
        } else {            

            $this->load->model('sms/enviar_sms');  

            //$equivalentes = $this->model_sms_enviar_sms->getdetalles();

            $data['mensaje_a_enviar'] = '';
        }

        if (isset($this->request->post['nombre_mensaje_predefinido'])) {
            $data['nombre_mensaje_predefinido'] = $this->request->post['nombre_mensaje_predefinido'];
         } else {
            $data['nombre_mensaje_predefinido'] = '';
        }

        if (isset($this->request->post['remitente'])) {
            $data['remitente'] = $this->request->post['remitente'];
         } else {
            $data['remitente'] = '';
        }


        if (isset($this->request->post['fecha_envio'])) {
            $data['fecha_envio'] = $this->request->post['fecha_envio'];
        } else {
            
            $fecha = date("Y-m-d");
            $data['fecha_envio'] = $fecha; 
        }


        if (isset($this->request->post['hora_envio'])) {
            $data['hora_envio'] = $this->request->post['hora_envio'];
        }else {
            date_default_timezone_set('America/Santiago');
            $hora = date("H:i");
            $currentDate = strtotime($hora);
            $futureDate = $currentDate+(60*5);
            $formatDate = date("H:i", $futureDate);
            
            $data['hora_envio'] = ($hora);            
        }

        $data['breadcrumbs'] = array();
        
        $data['token'] = $this->session->data['token'];

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('sms/enviar_sms', 'token=' . $this->session->data['token'], 'SSL')
        );
        $data['heading_title'] = $this->language->get('heading_title');

        $data['action'] = $this->url->link('sms/enviar_sms/send', 'token=' . $this->session->data['token'], 'SSL');
      

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

       



        $this->response->setOutput($this->load->view('sms/enviar_sms.tpl', $data));
    }


    public function send() {

        $this->load->model('sms/enviar_sms');  
               

        $numero = $this->request->post['numero'];


        $mensaje_a_enviar = $this->request->post['mensaje_a_enviar'];

        $remitente = $this->request->post['remitente'];
        
        $nombre_mensaje_predefinido = $this->request->post['nombre_mensaje_predefinido'];                


        # llamamos al controlador que nos devolvera la api/driver correspondiente
        $sms = new SMSController($this->session->data['id_empresa']);

        if(!is_null($sms->error)){
            echo "Error: ".$sms->error;
            return false;
        }

        $api = $sms->getAPI();
        
        $tipo = $this->request->post['options'];
        $modo_envio = $this->request->post['tipo_envio'];

        $titulo = $this->request->post['titulo'];             
        $check_predefinido = $this->request->post['check_predefinido'];
        
        if ($tipo == 'unico') {

            $equivalentes = $this->model_sms_enviar_sms->getEquivalencia('equivalencia_sms');

            $valor1 = $equivalentes['valor1'];
            $valor2 = $equivalentes['valor2'];

            $consumo = 1 / $valor2;


            $car = mb_strlen($mensaje_a_enviar);
            if($car > CARACTERES_SMS){
                $consumo = ceil($car / CARACTERES_SMS);
            }

            //echo "nc: $car <br> carc_sms:".CARACTERES_SMS."<br>consumo: $consumo<br>";

            $is_predefinido  = 0;
            if ($check_predefinido == 'true') {
                $is_predefinido  = 1;
            }


            if ($this->model_sms_enviar_sms->checkCreditosDisponibles($this->session->data['id_empresa'] , $consumo)) {

                $id_consumo = $api->insertConsumo($this->session->data['id_empresa'], 0, $consumo, $consumo);

                if ($modo_envio == 'ahora') {
                    
                    //$this->session->data['success'] = "Envío exitoso"; 

                    $response = $api->sendSms($numero, $mensaje_a_enviar, $remitente, 'connectusKey', $modo_envio, $this->session->data['id_empresa'], $titulo, $is_predefinido,$nombre_mensaje_predefinido, null, $id_consumo);
            
                    # $api->insertConsumo($this->session->data['id_empresa'], 0, $consumo, $consumo);

                    if ($response != 'Problemas con el servidor. Inténtelo más tarde.') {
                        $this->session->data['success'] = $response;                                                        
                    }else {

                        $this->deleteConsumo($id_consumo);
                        $this->session->data['warning'] = 'Problemas con el servidor. Inténtelo más tarde.';                                
                    }

                }elseif ($modo_envio == 'programado') { 

                        $datos_envio_programado = array('destinatario' =>$numero ,
                                            'mensaje_a_enviar' =>$mensaje_a_enviar ,
                                            'remitente' =>$remitente ,
                                            'connectusKey' =>'connectusKey',
                                            'id_empresa' => $this->session->data['id_empresa'],
                                            'id_usuario' => $this->session->data['user_id']);                                 


                        $fecha_envio = $this->request->post['fecha_envio'];
                        
                        $hora_envio = $this->request->post['hora_envio'];                        

                        //OK
                        $id_mensaje = $this->model_sms_enviar_sms->addSms($titulo, $mensaje_a_enviar, $remitente , $is_predefinido, $this->session->data['id_empresa'],$nombre_mensaje_predefinido);

                        $result = $this->model_sms_enviar_sms->addSmsEnvioProgramado('pendiente' ,$remitente , $id_mensaje,'unico','SMS',$titulo, $this->session->data['id_empresa'] ,$fecha_envio.' '. $hora_envio, json_encode($datos_envio_programado, JSON_UNESCAPED_UNICODE), null, $id_consumo);                         

                        #$api->insertConsumo($this->session->data['id_empresa'], 0, $consumo, $consumo);

                        if ($result) {
                            $this->session->data['success'] = "Envío programado exitósamente";                            
                        }else{
                            $this->deleteConsumo($id_consumo);
                            $this->session->data['success'] = 'Ups, algo salió mal. Vuelve a intentar.';
                        }

                }  

            }else{
                $this->session->data['error_cred'] = "No posees créditos suficientes.";
            }
            
        }elseif ($tipo == 'masivo') {
           // $this->session->data['success'] = "Envío exitoso";

            $this->load->model('contactos/listas'); 

            $id_lista = $this->request->post['entry_lista'];
            $result = '';
            $result = $this->model_contactos_listas->getDatosContactosLista($id_lista);
            array_push($result['nombre_columnas'], 'id_contacto');         

            $is_predefinido  = 0;
            if ($check_predefinido == 'true') {
                $is_predefinido  = 1;
            }

            $count = count($result['valores']);
            
            $equivalentes = $this->model_sms_enviar_sms->getEquivalencia('equivalencia_sms');                                        
            $valor1 = $equivalentes['valor1'];
            $valor2 = $equivalentes['valor2'];  
            $consumo = $count / $valor2;


            $car = mb_strlen($mensaje_a_enviar);
            if($car > CARACTERES_SMS){
                $consumo = ceil($car / CARACTERES_SMS);
            }



            if ($this->model_sms_enviar_sms->checkCreditosDisponibles($this->session->data['id_empresa'] , $consumo)) {

                $id_consumo = $api->insertConsumo($this->session->data['id_empresa'], 0, $consumo, $consumo);

                if ($modo_envio == 'ahora') {                                                                                                                                            

                    /* Envio normal sin demonio (Respaldo de funciones antiguas)
                    if($api->sendMassSms($mensaje_a_enviar, 'connectusKey', $this->session->data['id_empresa'], 'ahora', $is_predefinido, $result, $titulo, $id_lista, $nombre_mensaje_predefinido,$remitente)){
                        $this->session->data['success'] = "Envío exitoso";
                        $this->response->redirect($this->url->link('sms/enviar_sms','token=' . $this->session->data['token'],'SSL'));
                    }*/ 

                    $datos_envio_programado = array('destinatarios' => $result,
                                            'mensaje_a_enviar' =>$mensaje_a_enviar,
                                            'remitente' =>$remitente,
                                            'connectusKey' =>'connectusKey', 
                                            'id_empresa' => $this->session->data['id_empresa'],
                                            'id_lista' => $id_lista);                      

                    $fecha_envio = $this->request->post['fecha_envio'];
                    
                    $hora_envio = $this->request->post['hora_envio'];                                            
                      
                    $id_mensaje = $this->model_sms_enviar_sms->addSms($titulo, $mensaje_a_enviar, $remitente , $is_predefinido, $this->session->data['id_empresa'], $nombre_mensaje_predefinido);

                    $result = $this->model_sms_enviar_sms->addSmsEnvioProgramado('pendiente' ,$remitente , $id_mensaje,'masivo','SMS',$titulo, $this->session->data['id_empresa'] ,'', json_encode($datos_envio_programado, JSON_UNESCAPED_UNICODE) , $id_lista, $id_consumo);                         

                    #$api->insertConsumo($this->session->data['id_empresa'], 0, $consumo, $consumo);

                    if ($result) {
                        $this->session->data['success'] = "Envío exitoso. Verifica el estado del envío en el historial";                     
                    }else{
                        $this->deleteConsumo($id_consumo);
                        $this->session->data['success'] = 'Ups, algo salió mal. Reintente por favor.';
                    }                    
                }elseif ($modo_envio == 'programado') { 

                    $datos_envio_programado = array('destinatarios' => $result,
                                            'mensaje_a_enviar' =>$mensaje_a_enviar,
                                            'remitente' =>$remitente,
                                            'connectusKey' =>'connectusKey', 
                                            'id_empresa' => $this->session->data['id_empresa'],
                                            'id_lista' => $id_lista);                      

                    $fecha_envio = $this->request->post['fecha_envio'];
                    
                    $hora_envio = $this->request->post['hora_envio'];                                            
                      
                    $id_mensaje = $this->model_sms_enviar_sms->addSms($titulo, $mensaje_a_enviar, $remitente , $is_predefinido, $this->session->data['id_empresa'], $nombre_mensaje_predefinido);

                    $result = $this->model_sms_enviar_sms->addSmsEnvioProgramado('pendiente' ,$remitente , $id_mensaje,'masivo','SMS',$titulo, $this->session->data['id_empresa'] ,$fecha_envio.' '. $hora_envio, json_encode($datos_envio_programado, JSON_UNESCAPED_UNICODE) , $id_lista, $id_consumo);                         

                    #$api->insertConsumo($this->session->data['id_empresa'], 0, $consumo, $consumo);

                    if ($result) {
                        $this->session->data['success'] = "Envío programado ingresado exitosamente";                        
                    }else{
                        $this->deleteConsumo($id_consumo);
                        $this->session->data['success'] = 'Ups, algo salió mal. Reintente por favor.';
                    }
                } 
            }else{
                $this->session->data['error_cred'] = "No posees créditos suficientes.";                
            }
        }                             
    }

    public function validateForm(){

        if (empty($this->request->post['numero'])) {
            $this->error['error_numero'] = $this->language->get('error_numero');
        }
            
        if (empty($this->request->post['mensaje_a_enviar'])) {
            $this->error['error_mensaje_a_enviar'] = $this->language->get('error_mensaje_a_enviar');
        }

        if (isset($this->request->post['check_predefinido'])) {
            if (empty($this->request->post['nombre_mensaje_predefinido'])) {
                $this->error['error_nombre_mensaje_predefinido'] = $this->language->get('error_nombre_mensaje');
            }
        }   

        if (empty($this->request->post['remitente'])) {
            $this->error['error_remitente'] = $this->language->get('error_remitente');
        }

        if ($this->request->post['tipo_envio'] == 'programado') {
            
            if (empty($this->request->post['fecha_envio'])) {
                $this->error['error_fecha_envio'] = $this->language->get('error_fecha_envio');
            }

            if (empty($this->request->post['hora_envio'])) {
                $this->error['error_hora_envio'] = $this->language->get('error_hora_envio');
            }
        }

        return !$this->error;
    }

    public function deleteConsumo($id)
    {
        $this->load->model('sms/envio');
        $result = $this->model_sms_envio->deleteConsumo($id);
    }

}