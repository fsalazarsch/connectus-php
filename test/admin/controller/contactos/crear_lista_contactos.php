<?php
class ControllerContactosCrearListaContactos extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('contactos/crear_lista_contactos');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->getForm();
    }

    public function getForm(){

        $this->load->language('contactos/crear_lista_contactos');
 
        $this->document->setTitle($this->language->get('heading_title'));

        $data['text_form'] = $this->language->get('heading_title');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_filename'] = $this->language->get('entry_filename');

        $data['help_filename'] = $this->language->get('help_filename');

        $data['filename'] = $this->language->get('filename');
        $data['text_btn_seleccionar'] = $this->language->get('text_btn_seleccionar');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_upload'] = $this->language->get('button_upload');

        if (isset($this->session->data['warning'])) {
            $this->error['warning'] = $this->session->data['warning'];
            unset($this->session->data['warning']);
        }
       
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['error_file'])) {
            $data['error_file'] = $this->language->get('error_filename');       
        } else {
            $data['error_file'] = '';
        }

        
        if (isset($this->request->post['nombre_lista'])) {
            $data['nombre_lista'] = $this->request->post['nombre_lista'];
        }else{
            $data['nombre_lista'] = '';
        }


        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard_client', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('contactos/crear_lista_contactos', 'token=' . $this->session->data['token'], 'SSL')
        );
        $data['heading_title'] = $this->language->get('heading_title');
        $data['action'] = $this->url->link('contactos/crear_lista_contactos/uploadToServer', 'token=' . $this->session->data['token'], 'SSL');
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('contactos/crear_lista_contactos.tpl', $data));
    }

    /*public function upload() {
        $this->load->language('contactos/crear_lista_contactos');

        $json = array();

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'contactos/crear_lista_contactos')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if($this->validate()){
            if (!$json) {

                $filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
                $file = $filename;
                $array = array();
                $contenido = array();
                //subir el archivo csv
                if(move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file)){
                    //Se movio el archivo
                    $row = 0;
                    if (($handle = fopen(DIR_DOWNLOAD . $file, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
                            if($row >= 0){
                                $num = count($data);
                                $blackpowder = $data;
                                $dynamit = implode(";", $blackpowder);
                                $pieces = explode(";", $dynamit);
                                $contenido[$row] = $pieces;
                            }
                            $row++;
                        } 
                        $this->session->data['success'] = $this->language->get('text_success');
                    }

                }else{
                    //No se movio el archivo
                    $array['error'] = "failed to open stream";
                    $this->session->data['warning'] = $this->language->get('text_warning_move_file');

                }

                $string = implode(',',array_filter($array));
                $json['filename'] = $file;
                $json['contenido'] = $string;
                $json['success'] = $this->language->get('text_upload');
                if(isset($this->request->post['ck_encabezado'])){
                    $this->session->data['encabezado'] = $this->request->post['ck_encabezado'];
                }else{
                    $this->session->data['encabezado'] = false;
                }
                


                $this->session->data['datos_json'] = $contenido;
                $this->session->data['nombre_lista'] = $this->request->post['nombre_lista'];
                $this->response->redirect($this->url->link('contactos/preview_lista', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }else{
            $this->getForm();
        }
    }*/


     public function uploadToServer(){

        $this->load->language('contactos/crear_lista_contactos');
        $json = array();
        $temp_file = $this->request->files['file'];

        // Check user has permission
        if (!$this->user->hasPermission('modify', 'contactos/crear_lista_contactos')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if ($this->validate()) {
            if (!$json) {
                $filename = basename(html_entity_decode($temp_file['name'], ENT_QUOTES, 'UTF-8'));
                $file = $filename;
                $tipo = $temp_file['type'];
                
                $array = array();
                $contenido = array();


                //subir el archivo csv
                if(move_uploaded_file($temp_file['tmp_name'], DIR_DOWNLOAD . $file)){
                    //hacer esto en caso de archivo excel.- la primera opcion es para los .xlsx y la segunda para .xls
                    if($tipo == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $tipo == 'application/vnd.ms-excel'){
                        $this->load->library('libreriaExcel/PHPExcel');
                        $excel_manager = PHPExcel_IOFactory::load(DIR_DOWNLOAD . $file);
                        //obtener hoja activa
                        //$hoja_excel = $excel_manager->getActiveSheet()->toArray(null, true, true, true);

                        $hoja_excel = $excel_manager->getActiveSheet();
                        
                        $maxCell = $hoja_excel->getHighestRowAndColumn();
                        $hoja_excel = $hoja_excel->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);

                        $indice = 0;

                        $columnas_en_archivo = count($hoja_excel[0]);

                        if ($columnas_en_archivo <= 15) {
                            foreach($hoja_excel as $row_index => $row){
                            	$dynamit = implode(";", $row);
                                $pieces = explode(";", $dynamit);
                        		if($this->rowValidate($pieces)){
                                    //$contenido[$row_index] = $pieces;	
                                    $contenido[$indice] = $pieces;	
                                    $indice++;
                        		}
                            }     
                        }else{
                            $this->session->data['warning'] = $this->language->get('error_max_columns');
                            $this->response->redirect($this->url->link('contactos/crear_lista_contactos', 'token=' . $this->session->data['token'], 'SSL'));
                        }                  
                    }elseif($tipo == 'csv'){
                        //Se movio el archivo
                        $row = 0;
                        if (($handle = fopen(DIR_DOWNLOAD . $file, "r")) !== FALSE) {
                            while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
                                if($row >= 0){
                                    $num = count($data);
                                    $blackpowder = $data;
                                    $dynamit = implode(";", $blackpowder);
                                    $pieces = explode(";", $dynamit);
                                    $contenido[$row] = $pieces;
                                }
                                $row++;
                            }
                            $this->session->data['success'] = $this->language->get('text_success');
                        }
                    }

                    if(isset($this->request->post['ck_encabezado'])){
                        $this->session->data['encabezado'] = $this->request->post['ck_encabezado'];
                    }else{
                        $this->session->data['encabezado'] = false;
                    }

                        $this->session->data['datos_json'] = $contenido;
                        $this->session->data['nombre_lista'] = $this->request->post['nombre_lista'];
                        $this->response->redirect($this->url->link('contactos/preview_lista', 'token=' . $this->session->data['token'], 'SSL'));                  
                       
                    
                }else{
                    //No se movio el archivo
                    $array['error'] = "failed to open stream";
                    $this->session->data['warning'] = $this->language->get('text_warning_move_file');
                }
            }
        }else{
            $this->getForm();
        }       
    }

    public function validate()
    {
        if(isset($this->request->post['nombre_lista'])){
           if(utf8_strlen($this->request->post['nombre_lista']) < 1){
                $this->error['warning'] = $this->language->get('error_warning_nombre_lista');
           }
        }else{ $this->error['warning'] = $this->language->get('error_warning_nombre_lista');}

        if(isset($this->request->post['uploadFile'])){
           if(utf8_strlen($this->request->post['uploadFile']) < 3 || utf8_strlen($this->request->post['uploadFile']) > 128){
                $this->error['error_file'] = $this->language->get('error_filename');
           }
        }else{$this->error['error_file'] = $this->language->get('error_filename');}

       return !$this->error;
    }

    public function rowValidate($row){
    	$len = count($row);
    	$cleans = 0;

    	foreach ($row as $key => $value) {    		
    		$cell = ltrim($value, "\x00..\x1F");
            $aux = trim($cell);
    		if (empty($aux)) {
    			$cleans++;                
    		}   
    	}

    	if ($len == $cleans) {
    		return false;
    	}else{
            return true;
        }
    }
}