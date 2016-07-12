<?php

	require_once __DIR__.'/../../../connectus/MAIL/MAILController.php';


class ControllerMailingEnviarMail extends Controller {
	private $error = array();

	public function index() {
        $this->load->language('mailing/enviar_mail');        
        $this->document->setTitle($this->language->get('heading_title'));
        $this->getForm();
    }

	public function getForm() {
		$this->load->language('mailing/enviar_mail');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('contactos/listas');
		//textos y labels
		$data['heading_title'] = $this->language->get('heading_title');
		$data['texto_unico'] = $this->language->get('texto_unico');
		$data['texto_masivo'] = $this->language->get('texto_masivo');
		$data['texto_nuevo'] = $this->language->get('texto_nuevo');
		$data['texto_predefinido'] = $this->language->get('texto_predefinido');
		$data['texto_campos'] = $this->language->get('texto_campos');
		$data['texto_guardar_predefinido'] = $this->language->get('texto_guardar_predefinido');
		//placeholder 
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_editor'] = $this->language->get('entry_editor');
		$data['entry_nombre_predefinido'] = $this->language->get('entry_nombre_predefinido');


		$data['text_btn_adjuntar'] = $this->language->get('text_btn_adjuntar');
		$data['button_send'] = $this->language->get('button_send');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('mailing/enviar_mail', 'token=' . $this->session->data['token'], 'SSL')
		);


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

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

        if (isset($this->error['error_email_destinatario'])) {
            $data['error_email_destinatario'] = $this->language->get('error_destinatario');
        } else {
            $data['error_email_destinatario'] = '';
        }

        if (isset($this->error['error_nombre_envio'])) {
            $data['error_nombre_envio'] = $this->language->get('error_nombre_envio');
        } else {
            $data['error_nombre_envio'] = '';
        }

        if (isset($this->error['error_asunto'])) {
            $data['error_asunto'] = $this->language->get('error_asunto');
        } else {
            $data['error_asunto'] = '';
        }

        if (isset($this->error['error_lista_destinatarios'])) {
            $data['error_lista_destinatarios'] = $this->language->get('error_lista_destinatarios');
        } else {
            $data['error_lista_destinatarios'] = '';
        }

        if (isset($this->error['error_email_remitente'])) {
            $data['error_email_remitente'] = $this->language->get('error_email_remitente');
        } else {
            $data['error_email_remitente'] = '';
        }

        if (isset($this->error['error_nombre_remitente'])) {
            $data['error_nombre_remitente'] = $this->language->get('error_nombre_remitente');
        } else {
            $data['error_nombre_remitente'] = '';
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

        //autocompletar los campos
		if (isset($this->request->post['email_remitente'])) {
            $data['email_remitente'] = $this->request->post['email_remitente'];
         } else {
            $data['email_remitente'] = '';
        }

        if (isset($this->request->post['nombre_remitente'])) {
            $data['nombre_remitente'] = $this->request->post['nombre_remitente'];
         } else {
            $data['nombre_remitente'] = '';
        }

      	if (isset($this->request->post['fecha_envio'])) {
            $data['fecha_envio'] = $this->request->post['fecha_envio'];
        } else {
            
            $fecha = date("Y-m-d");
            $data['fecha_envio'] = $fecha; 
        }

        if (isset($this->request->post['nombre_mensaje_predefinido'])) {
            $data['nombre_mensaje_predefinido'] = $this->request->post['nombre_mensaje_predefinido'];
         } else {
            $data['nombre_mensaje_predefinido'] = '';
        }

          $data['placeholder_nuevo_predefinido'] = $this->language->get('placeholder_nuevo_predefinido');


        if (isset($this->request->post['hora_envio'])) {
            $data['hora_envio'] = $this->request->post['hora_envio'];
        }else {
            date_default_timezone_set('America/Santiago');
            $hora = date("H:i");

            //Agregar 10 min a la hora actual
            $currentDate = strtotime($hora);
			$futureDate = $currentDate+(60*5);
			$formatDate = date("H:i", $futureDate);
            
            $data['hora_envio'] = ($hora);            
        }

		if(isset($this->request->post['input_email'])){
			$data['email'] = $this->request->post['input_email'];
		}else{
			$data['email'] = '';
		}

		if(isset($this->request->post['nombre_envio'])){
            $data['nombre_envio'] = $this->request->post['nombre_envio'];
        }else{
            $data['nombre_envio'] = '';
        }

        if(isset($this->request->post['asunto'])){
            $data['asunto'] = $this->request->post['asunto'];
        }else{
            $data['asunto'] = '';
        }

		if(isset($this->request->post['options']) ){
			if ($this->request->post['options'] == 'unico') {
				$data['radio_unico'] = true;				
			}else{				
				$data['radio_masivo'] = true;
			}	
		}

		if(isset($this->request->post['check_tipo_mensaje'])){
			$data['tipo_mensaje'] = $this->request->post['check_tipo_mensaje'];
		}else{
			$data['tipo_mensaje'] = '';
		}

		if(isset($this->request->post['mensaje_a_enviar'])){
			
			$data['mensaje'] = html_entity_decode($this->request->post['mensaje_a_enviar'], ENT_QUOTES, 'UTF-8');
		}else{

			$this->load->model('mailing/enviar_mail');   
			//$result = $this->model_mailing_enviar_mail->getdetalles();				
			$data['mensaje'] = '';
			
		}

		if(isset($this->request->post['uploadFile'])){
			$data['archivos'] = $this->request->post['uploadFile'];
		}else{
			$data['archivos'] = '';
		}		
		

		if(isset($this->request->post['check_guardar_predefinido'])){
			$data['preferencia'] = $this->request->post['check_guardar_predefinido'];
		}else{
			$data['preferencia'] = '';
		}

		if(isset($this->request->post['nombre_mensaje_predefinido'])){
			$data['nom_predefinido'] = $this->request->post['nombre_mensaje_predefinido'];
		}else{
			$data['nom_predefinido'] = '';
		}

		if(isset($this->request->post['uploadFile'])){
			$data['nombre_archivo'] = $this->request->post['uploadFile'];
		}else{
			$data['nombre_archivo'] = '';
		}

		if(isset($this->request->post['uploadBtn'])){
			$data['archivo'] = $this->request->post['uploadBtn'];
		}else{
			$data['archivo'] = '';
		}

		$data['model'] = $this->model_contactos_listas;

		$lista_contactos = array();
        $result_lista_contactos = $this->model_contactos_listas->getListasEmpresaPorUsuarioMail($this->session->data['id_empresa']);

        foreach( $result_lista_contactos as $row ) {
            $filtro = array('nombre' => $row['nombre'],
                               'id_lista' => $row['id_lista']);

            $lista_contactos[] = $filtro;
        }

        $data['lista_contactos'] = $lista_contactos;

        $lista_predefinidos = array();

        $result_preferidos = $this->model_contactos_listas->getMailPredefinidos($this->session->data['id_empresa']);
        
        foreach( $result_preferidos as $row ) {

            if(empty($row['nombre_predefinido'])){
                $nombre_predefinido = html_entity_decode($row['titulo'], ENT_QUOTES, 'UTF-8');
            }else{
                $nombre_predefinido = html_entity_decode($row['nombre_predefinido'], ENT_QUOTES, 'UTF-8');
            }

            $filtro = array('titulo' => $nombre_predefinido ,
                               'cuerpo' => html_entity_decode($row['cuerpo'], ENT_QUOTES, 'UTF-8') );

            $lista_predefinidos[] = $filtro;
        }

        $data['lista_preferidos'] = $lista_predefinidos;


		if(isset($this->error['error_nombre_mensaje_predefinido'])){
			$data['error_nombre_mensaje_predefinido'] = $this->error['error_nombre_mensaje_predefinido'];
		}else{
			$data['error_nombre_mensaje_predefinido'] = '';
		}

		$data['cancel'] = $this->url->link('mailing/enviar_mail', 'token=' . $this->session->data['token'], 'SSL');	
		$data['action']	= $this->url->link('mailing/enviar_mail/enviar', 'token=' . $this->session->data['token'], 'SSL');	

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		 

		$this->response->setOutput($this->load->view('mailing/enviar_mail.tpl', $data));
	}


	public function enviar() {


		if(isset($_FILES['file']['name'])){
			for($i=0; $i<count($_FILES['file']['name']); $i++) {			  
				  $tmpFilePath = $_FILES['file']['tmp_name'][$i];			  
				  if ($tmpFilePath != ""){
				    $newFilePath = DIR_FILE_ATTACHMENT. $_FILES['file']['name'][$i];			   
				    move_uploaded_file($tmpFilePath, $newFilePath);			      			    
				  }
			}  
		}

		 if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		 	$this->load->model('user/user');            

            $this->load->model('mailing/enviar_mail');  
		 	//API Envio.



			$conector = $this->model_mailing_enviar_mail->getConector($this->session->data['id_empresa']);

			/*
			if ($conector == 3) {
				$this->load->extern_library('MandrillController');            
            	$api = new MandrillController();	

			}elseif($conector == 2){
				$this->load->extern_library('ConnectusController');            
            	$api = new ConnectusController();
			}
			*/

			# llamamos al controlador que nos devolvera la api/driver correspondiente
	        $mail = new MAILController($this->session->data['id_empresa']);

	        if(!is_null($mail->error)){
	            echo "Error: ".$mail->error;
	            return false;
	        }

	        $api = $mail->getAPI();

		    $user = $this->model_user_user->getUserByUsername($this->session->data['username']);

			$asunto = $this->request->post['asunto'];
			$message  = '<html dir="ltr" lang="es">' . "\n";
			$message .= '  <head>' . "\n";				
			$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";			
			$message .= '  </head>' . "\n";
			$message .= '  <body>'  . "\n";
			$message .= 	 html_entity_decode($this->request->post['mensaje_a_enviar'], ENT_QUOTES, 'UTF-8') . "\n";			
			$message .= ' </body>' . "\n"; 
			$message .= '</html>' . "\n";

		 	$tipo_envio = $this->request->post['options'];
		 	$modo_envio = $this->request->post['tipo-envio'];

            $nombre_envio = $this->request->post['nombre_envio'];


			 	if ($tipo_envio == 'unico') {

			 		$destinatario = $this->request->post['input_email'];	
					
					$equivalentes = $this->model_mailing_enviar_mail->getEquivalencia('equivalencia_mail');
					$valor1 = $equivalentes['valor1'];
					$valor2 = $equivalentes['valor2'];		
					$consumo = 1 / $valor2;

					$is_predefinido  = 0;
			 		$titulo = $asunto;
			 		
			 		if (isset($this->request->post['check_guardar_predefinido'])) {


			 			$is_predefinido = 1;

                        if(empty($asunto)){
                            $asunto = $this->request->post['nombre_mensaje_predefinido'];
                        }

			 			$titulo = $this->request->post['nombre_mensaje_predefinido'];
			 		}

					
				 	if ($this->model_mailing_enviar_mail->checkCreditosDisponibles($this->session->data['id_empresa'] , $consumo)) {

                        # Obtenemos el ID del consumo, y lo guardamos en el envío !!
                        $id_consumo = $api->insertConsumo($this->session->data['id_empresa'] , $consumo, 0 , $consumo); // Se descuenta inmediato


				 		if ($modo_envio == 'ahora') {			 	
					 		//Envio unico				 						 	

					 		if($api->sendMail($this->request->post['nombre_remitente'], $this->request->post['email_remitente'], $destinatario, $asunto, $message, 'connectusKey',$this->session->data['id_empresa'], 'ahora',$is_predefinido, null, $titulo, $nombre_envio, $id_consumo)){
                                #$id_consumo = $api->insertConsumo($this->session->data['id_empresa'] , $consumo, 0 , $consumo); // Se descuenta inmediato
                                $this->session->data['success'] = "Envío exitoso";
					 			$this->response->redirect($this->url->link('mailing/enviar_mail','token=' . $this->session->data['token'],'SSL'));		
					 		
                            }else{
                                $this->deleteConsumo($id_consumo);
					 			$this->error['warning'] = 'Ups, algo salió mal. Reintente por favor.';
					 		}

				 		}elseif ($modo_envio == 'programado') {	

			 				$datos_envio_programado = array('nombre_remitente' => $this->request->post['nombre_remitente'] ,
					 								'email_remitente' =>$this->request->post['email_remitente'],
					 								'destinatario' => $destinatario,
					 								'asunto' =>$asunto ,
					 								'message' => $this->request->post['mensaje_a_enviar'] ,
					 								'connectusKey' =>'connectusKey',
					 								'id_empresa' => $this->session->data['id_empresa'] ,
					 								'id_lista' => null);

					        $fecha_envio = $this->request->post['fecha_envio'];
					        
					        $hora_envio = $this->request->post['hora_envio'];

							//OK
							$id_mensaje = $this->model_mailing_enviar_mail->addMail($asunto,  ($this->request->post['mensaje_a_enviar'])  ,$this->request->post['nombre_remitente'] ,$this->request->post['email_remitente'], $is_predefinido, $this->session->data['id_empresa'], null, $titulo, $nombre_envio);   

					        $result = $this->model_mailing_enviar_mail->addMailEnvioProgramado('pendiente' ,$this->request->post['nombre_remitente'] ,$this->request->post['email_remitente'],'', $id_mensaje,'unico','MAIL', $nombre_envio, $is_predefinido ,$this->session->data['id_empresa'] ,$fecha_envio.' '. $hora_envio, json_encode($datos_envio_programado, JSON_UNESCAPED_UNICODE), $id_consumo);							

							if ($result) {
								#$id_consumo = $api->insertConsumo($this->session->data['id_empresa'] , $consumo, 0 , $consumo); // Se descuenta inmediato
								$this->session->data['success'] = " Envío programado ingresado exitosamente";
								$this->response->redirect($this->url->link('mailing/enviar_mail','token=' . $this->session->data['token'],'SSL'));		
						 	}else{
                                $this->deleteConsumo($id_consumo);
						 		$this->session->data['success'] = 'Ups, algo salió mal. Reintente por favor.';
						 	}
				 		}
				 	}else{
						$this->session->data['error_cred'] = "No posees créditos suficientes.";
						$this->response->redirect($this->url->link('mailing/enviar_mail','token=' . $this->session->data['token'],'SSL'));	
				 	}


			 	}elseif($tipo_envio=='masivo'){
								

			 		//Envio masivo	
			 		$id_lista = $this->request->post['entry_lista_contactos'];

			 		$this->load->model('contactos/listas');
			        $lista_contactos = array();
			        $data['lista_contactos'] = '';
			        //$result_lista_contactos = $this->model_contactos_listas->getContactosPorLista($id_lista);	
					$result = '';
			        $result = $this->model_contactos_listas->getDatosContactosLista($id_lista);	
								        
			        array_push($result['nombre_columnas'], 'id_contacto');

			        $correos_buenos = 0;
			        
					for ($i=0; $i < sizeof($result['valores']); $i++) {

                        for ($j=0; $j < sizeof($result['valores'][$i]); $j++) {
                            
                            if ($result['campos_de_contacto'][$j] == 'email') {

                                if(preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $result['valores'][$i][$result['campos_de_contacto'][$j]]  )){                                  
                                    
                                    $destinatario = $result['valores'][$i][$result['campos_de_contacto'][$j]];
                                    

                                    if(! $this->model_mailing_enviar_mail->checkDesincrito( $destinatario , $this->session->data['id_empresa']) )
                                    {           
                                        $correos_buenos++;
                                    }else{
                                        # si se encuentra desincrito le damos null
                                        # para luego eliminar
                                        $result['valores'][$i] = null;
                                    }
                                }
                            }
                        }
                    }

                    # eliminamos los null del array
                    $arr = array();
                    foreach ($result['valores'] as $value){
                        if(!is_null($value)){
                            $arr[] = $value;
                        }
                    }

                    $result['valores'] = $arr;

					$equivalentes = $this->model_mailing_enviar_mail->getEquivalencia('equivalencia_mail');
					$valor1 = $equivalentes['valor1'];
					$valor2 = $equivalentes['valor2'];		
					$consumo = $correos_buenos / $valor2;

					
				 	if ($this->model_mailing_enviar_mail->checkCreditosDisponibles($this->session->data['id_empresa'] , $consumo)) {
				 						 	
					 	if ($result != '') {
					 		$is_predefinido  = 0;
					 		$titulo = $asunto;
					 		
					 		if (isset($this->request->post['check_guardar_predefinido'])) {
					 			$is_predefinido = 1;
					 			$titulo = $this->request->post['nombre_mensaje_predefinido'];
					 		}

                            $id_consumo = $api->insertConsumo($this->session->data['id_empresa'] , $consumo, 0 , $consumo); // Se descuenta inmediato

					 		if ($modo_envio == 'ahora') {

					 			$datos_envio_programado = array('nombre_remitente' => $this->request->post['nombre_remitente'] ,
	 								'email_remitente' =>$this->request->post['email_remitente'],
	 								'destinatarios' => $result,
	 								'asunto' =>$asunto ,
	 								'message' => $this->request->post['mensaje_a_enviar'],
	 								'connectusKey' =>'connectusKey',
	 								'id_empresa' => $this->session->data['id_empresa'] ,
	 								'id_lista' => $id_lista);

						      
								//OK
								$id_mensaje = $this->model_mailing_enviar_mail->addMail($titulo, ($this->request->post['mensaje_a_enviar'])  ,$this->request->post['nombre_remitente'] ,$this->request->post['email_remitente'], $is_predefinido, $this->session->data['id_empresa'], null, $titulo, $nombre_envio);

                                                                                                                                                                                                                                        #$asunto
						        $result = $this->model_mailing_enviar_mail->addMailEnvioProgramado('pendiente' ,$this->request->post['nombre_remitente'] ,$this->request->post['email_remitente'],$id_lista, $id_mensaje,'masivo','MAIL',$nombre_envio, 0 ,$this->session->data['id_empresa'] ,'', json_encode($datos_envio_programado, JSON_UNESCAPED_UNICODE), $id_consumo);							

						        //$this->session->data['success'] = $result;
						        
						        
								if ($result) {
									#$api->insertConsumo($this->session->data['id_empresa'] , $consumo, 0 , $consumo); // Se descuenta inmediato
		                        	$this->session->data['success'] = "Envío exitoso. Verifica el estado del envío en el historial";   
		                        	$this->response->redirect($this->url->link('mailing/enviar_mail','token=' . $this->session->data['token'],'SSL'));	                  
			                    }else{
                                    $this->deleteConsumo($id_consumo);
			                        $this->session->data['success'] = 'Ups, algo salió mal. Reintente por favor.';
			                    }
			                    
						 	}elseif ($modo_envio == 'programado') {
						 		
						 		$datos_envio_programado = array('nombre_remitente' => $this->request->post['nombre_remitente'] ,
						 								'email_remitente' =>$this->request->post['email_remitente'],
						 								'destinatarios' => $result,
						 								'asunto' =>$asunto ,
						 								'message' => $this->request->post['mensaje_a_enviar'],
						 								'connectusKey' =>'connectusKey',
						 								'id_empresa' => $this->session->data['id_empresa'] ,
						 								'id_lista' => $id_lista);

						        $fecha_envio = $this->request->post['fecha_envio'];
						        
						        $hora_envio = $this->request->post['hora_envio'];							
								
								$id_mensaje = $this->model_mailing_enviar_mail->addMail($titulo, ($this->request->post['mensaje_a_enviar'])  ,$this->request->post['nombre_remitente'] ,$this->request->post['email_remitente'], $is_predefinido, $this->session->data['id_empresa'], null, $titulo, $nombre_envio);

						        $result = $this->model_mailing_enviar_mail->addMailEnvioProgramado('pendiente' ,$this->request->post['nombre_remitente'] ,$this->request->post['email_remitente'],$id_lista, $id_mensaje,'masivo','MAIL',$nombre_envio, 0 ,$this->session->data['id_empresa'] ,$fecha_envio.' '. $hora_envio, json_encode($datos_envio_programado, JSON_UNESCAPED_UNICODE), $id_consumo);							

								if ($result) {
									#$api->insertConsumo($this->session->data['id_empresa'] , $consumo, 0 , $consumo); // Se descuenta inmediato
									$this->session->data['success'] = "Envío programado exitosamente";
									$this->response->redirect($this->url->link('mailing/enviar_mail','token=' . $this->session->data['token'],'SSL'));		
							 	}else{
                                    $this->deleteConsumo($id_consumo);
							 		$this->session->data['success'] = 'Ups, algo salió mal. Reintente por favor.';
							 	}					 							
					 		}
					 		
				 		} 

			 		}else{
			 			$this->session->data['error_cred'] = "No posees créditos suficientes.";
						$this->response->redirect($this->url->link('mailing/enviar_mail','token=' . $this->session->data['token'],'SSL'));		
			 		}
		 		}
		 	
		 }			 	
		 			 	
		$this->getForm();		 
	}


	public function validate(){
		$this->load->language('mailing/enviar_mail');
		
		//errores
		$tipo_envios = $this->request->post['options'];
		if($tipo_envios == 'unico'){

			if (empty($this->request->post['input_email'])) {
				$this->error['error_email_destinatario'] = $this->language->get('error_destinatario');
			}else if(!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['input_email'])){
				$this->error['error_email_destinatario'] = $this->language->get('error_destinatario');
			}

		}elseif ($tipo_envios == 'masivo') {
			if(isset($this->request->post['entry_lista_contactos'])){
				if($this->request->post['entry_lista_contactos'] == 'seleccione'){
					$this->error['error_lista_destinatarios'] = $this->language->get('error_select_list');
				}
			}
			
		}	
								
		if(empty($this->request->post['mensaje_a_enviar'])){
			$this->error['error_mensaje_a_enviar'] = $this->language->get('error_mensaje_a_enviar');
		}	
		
		if (isset($this->request->post['check_predefinido'])) {
            if (empty($this->request->post['nombre_mensaje_predefinido'])) {
                $this->error['error_nombre_mensaje_predefinido'] = $this->language->get('error_nombre_mensaje');
            }
        }   

        if(empty($this->request->post['email_remitente'])){
			$this->error['error_email_remitente'] = $this->language->get('error_email_remitente');
		}

	 	if(!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email_remitente'])){
			$this->error['error_email_remitente'] = $this->language->get('error_email_remitente');
		}

		if(empty($this->request->post['nombre_remitente'])){
			$this->error['error_nombre_remitente'] = $this->language->get('error_nombre_remitente');
		}

		if(empty($this->request->post['asunto'])){
            $this->error['error_asunto'] = $this->language->get('error_asunto');
        }


        if(empty($this->request->post['nombre_envio'])){
            $this->error['error_nombre_envio'] = $this->language->get('error_nombre_envio');
        }
   

		return !$this->error;
	}

	public function getCamposPorLista(){
		$json = array();

		$a = $this->request->post['id_lista'];

		$this->load->model('contactos/listas');
		$result = $this->model_contactos_listas->getDatosContactosLista($a);		

		$json[0] = sizeof($result['nombre_columnas']);
		for ($i=0; $i < sizeof($result['nombre_columnas']); $i++) { 

			$json[$i + 1 ] = $result['nombre_columnas'][$i];

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function upload_drag_image()
	{
		$destination = '';
		if ($_FILES['file']['name']) {
            if (!$_FILES['file']['error']) {

                $filename = $_FILES['file']['name'];
                $filename = $this->getNombreArchivo($filename);
                $destination = DIR_FILE_ATTACHMENT . $filename; 

                $location = $_FILES["file"]["tmp_name"];
               	move_uploaded_file($location, $destination);
               	echo DIR_DRAG_FILE.str_ireplace(' ', '_', $filename); 
            } 
		}
	}

    public function getNombreArchivo($filename){

        $archivo = str_ireplace(' ', '_', $filename);

        if(file_exists( DIR_FILE_ATTACHMENT.$archivo )){
            
            $nom = explode('.', $archivo);
            $ext = end($nom);
            $carray = count($nom);
            $nom[$carray - 1] = 1;
            $nom[$carray] = $ext;
            $nom = implode('.', $nom);

            while(file_exists( DIR_FILE_ATTACHMENT.$nom )){

                $nom = explode('.', $nom);
                $n = count($nom);
                $nom[ $n-2 ] ++;
                $nom = implode('.', $nom);
            }
            
            return $nom;
        }
        else{
            return $archivo;
        }
    }

    public function deleteConsumo($id)
    {
        $this->load->model('mailing/envio');
        $result = $this->model_mailing_envio->deleteConsumo($id);
    }

}