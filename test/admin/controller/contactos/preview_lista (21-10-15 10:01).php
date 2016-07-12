<?php
class ControllerContactosPreviewLista extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('contactos/preview_lista');
		$this->document->setTitle($this->language->get('heading_title'));		

		$this->getList();
	}


	public function getList() {
		$this->load->language('contactos/preview_lista');
		$this->document->setTitle($this->language->get('heading_title'));

		//carga de datos de csv para tpl
		$datos = $this->session->data['datos_json'];
		$data['datos'] = $datos;
		$data['encabezado'] = $this->session->data['encabezado'];
		
		$url = '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('contactos/preview_lista', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (isset($this->session->data['datos_csv'])) {
			unset($this->session->data['datos_csv']);
		} 
		

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');

		
		$data['action'] = $this->url->link('contactos/preview_lista/guardarListaXpress','token=' . $this->session->data['token'],'SSL');	
		$data['cancel']	= $this->url->link('contactos/crear_lista_contactos','token=' . $this->session->data['token'],'SSL'); 


        if (isset($this->session->data['error_no_seleccion'])) {
            $data['error_warning'] = $this->language->get('error_no_columna_seleccionada');
            unset($this->session->data['error_no_seleccion']);
        } else {
            $data['error_warning'] = '';
        } 

        if (isset($this->session->data['invalid_file'])) {
           $data['error_file'] = $this->language->get('error_file');
           unset($this->session->data['invalid_file']);
       }
       else{
            $data['error_file'] = '';
       }       

        if (isset($this->session->data['error_no_email'])) {
            $data['error_warning_campo'] = $this->language->get('error_no_telefono_no_email');
            unset($this->session->data['error_no_email']);
        } else {
            $data['error_warning_campo'] = '';
        }

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		//carga de los tipos de campo
		$this->load->model("campos/tipo_campo");
		$fila = $this->model_campos_tipo_campo->getTipos();
		$data['tipoCampo'] = $this->model_campos_tipo_campo->getTipos();
		
	    $tabla = array();	
		for ($i=0; $i < count($datos) ; $i++) { 
			$columna_temp = array(); 
			$columna_temp[] = $i;             
            foreach ($datos[$i] as $box) {               
            	$columna_temp[] = $box; 
            }
            array_push($tabla, $columna_temp);
	    }

	    $this->session->data['datos_para_exportar'] = $tabla;
        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('contactos/preview_lista.tpl', $data));		
	}

	public function guardarListaXpress(){
		$datos = $this->session->data['datos_json'];
		$nombre_lista = $this->session->data['nombre_lista'];
		$input_name[] = $this->session->data['input_name'];
		
		
		$data['datos'] = $datos;
		$this->session->data['array_selects_value'] = array();
		$indice_columna_insertar = array();



		if(isset($this->request->post['hidden_field'])){
			$hidden = $this->request->post['hidden_field'];
			$this->session->data['array_selects_value'] = explode(',', $hidden);
		}

		if(isset($this->request->post['hidden_input_name'])){
			$hidden_input_name = $this->request->post['hidden_input_name'];
			$arra_input_value = explode(',', $hidden_input_name);
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			//obtener el usuario actual 
		    $this->load->model('user/user');
		    $user = $this->model_user_user->getUserByUsername($this->session->data['username']);

		    //insertar lista
		    $this->load->model('contactos/contacto');
		    $this->load->model('campos/campo');

		    $id_lista = $this->model_contactos_contacto->addLista($nombre_lista,$this->session->data['id_empresa'],$user['user_id']);
		    $campos = $this->session->data['array_selects_value'];

					    
			$consulta = "INSERT INTO contacto (";
		   	$custom_number = 1;
			$count = count($campos);
			for ($i=0; $i < $count; $i++) {
				
				if(strtolower($campos[$i]) != 'ignorar' && strtolower($campos[$i]) != 'texto'){

					if ($i < $count) {
						$consulta .= $campos[$i] . ',';
						$this->model_campos_campo->addCampo($campos[$i], '', $campos[$i], $id_lista, $i, $arra_input_value[$i]);
					}
					$indice_columna_insertar[] = $i;
				}elseif (strtolower($campos[$i]) == 'texto') {
					if ($i < $count){
						$consulta .= 'custom'.$custom_number . ',';
						$nombre_campo = $arra_input_value[$i];					
						$this->model_campos_campo->addCampo($campos[$i], '', 'custom'.$custom_number, $id_lista, $i, $arra_input_value[$i]);
					}
					$custom_number++; 
					$indice_columna_insertar[] = $i;
				}

			}

			$consulta .= 'id_lista) VALUES ';
				
			//recorrer datos para insertar en database
			$count_campos = count($datos);
			$quitarComa = true;
			foreach ($datos as $col_index => $columna) {

				if ($col_index > 0)
					$registro = '(';
				else 
					$registro = '';

				foreach ($columna as $row_index => $row_value) {
				//if ( $this->rowValidate($columna) ) {
					if($row_index < count($columna) && $col_index > 0 ){

						if(strtolower($campos[$row_index]) != 'ignorar'){
							//quitamos cualquiera de los caracteres especificados entre comillas del inicio y fin del primer parametro.
							$sin_espacios = trim($row_value);
							$value_clean  = trim($sin_espacios, "'!?¿¡#$%&/)=*+`^{}[]|-_/<>");
							$registro .= '"'. $value_clean . '",';
						}
					}
				}


				if ($col_index > 0) {
					if ($col_index < (count($datos)-1)) {
					$registro .= $id_lista . '),';
					}elseif ($col_index < (count($datos))) {
						$registro .= $id_lista . ')';
						$quitarComa = false;
					}
				}

				$consulta .= $registro;			 
			}
			
			
			if ($quitarComa) {
				$consulta = rtrim($consulta,',');
			}

		    $result = $this->admDB->query($consulta);
		    if ($result->num_rows > 0) {
		    	$this->response->redirect($this->url->link('contactos/mis_listas','token=' . $this->session->data['token'],'SSL'));	
		    }
		}else{			
			$this->getList();
		}		
	}

	//valida que los campos telefono y mail esten si o si en la lista que se subio
	/*public function rowValidate($row){
		//nombre de los campos a insertar
		$campos = $this->session->data['array_selects_value'];
		$error = 0;
		for ($i=0; $i < count($row) ; $i++) { 			
			if ($campos[$i] == 'email') {
				if (empty($row[$i])) {
					$error++;
				}
			}
			if ($campos[$i] == 'telefono') {
				if (empty($row[$i])) {
					$error++;
				}
			}
			if ($campos[$i] == 'celular') {
				if (empty($row[$i])) {
					$error++;
				}
			}
		}

		
		if ($error == 0) {
			return true;			
		}else{
			return false;
		}
	}
	*/


	public function validate(){
		$array_selects_value = $this->session->data['array_selects_value']; 
		
        if (!in_array('email', $array_selects_value) && !in_array('telefono', $array_selects_value) && !in_array('celular', $array_selects_value)) {
            $this->error['error_no_email_no_telefono'] = $this->language->get('error_no_telefono_no_email');
            $this->session->data['error_no_email'] = $this->language->get('error_no_telefono_no_email');
        }

        $cantidad_ignorar = array_count_values($array_selects_value);
        $aux = isset($cantidad_ignorar['ignorar']) ? $cantidad_ignorar['ignorar'] : 0 ;
        if(count($array_selects_value)==$aux){
            $this->error['error_no_columna_seleccionada'] = $this->language->get('error_no_columna_seleccionada');
            $this->session->data['error_no_seleccion'] = $this->language->get('error_no_columna_seleccionada');
        }

		return !$this->error;	
	}
}