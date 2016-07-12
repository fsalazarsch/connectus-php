<?php
class ModelContactosListas extends Model {
   
    public function getListasEmpresaPorUsuario($id_empresa, $data = array()){
                                   
        $sql = 'select * from lista_contacto as L where L.id_empresa = ' . $id_empresa;
        $sql .= " AND L.borrado = 0"  ;     

        if (!empty($data['filter_name'])) {
            $sql .= " AND L.nombre LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }        
        
        $sql .= " GROUP BY L.id_lista";

        $sort_data = array(
            'L.nombre',
            'L.fecha_creacion'            
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY L.fecha_creacion";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        } 

        $result = $this->admDB->query($sql);
        return $result->rows;
    }

    public function getListasEmpresaPorUsuarioSms($id_empresa){                              
        $sql = 'select * FROM lista_contacto as l inner join campo as c
                on c.id_lista = l.id_lista
                where c.campo in ("celular")
                and l.borrado = 0
                and l.id_empresa = ' . $id_empresa;                
        
        $result = $this->admDB->query($sql);
        return $result->rows;       
    }

    public function getListasEmpresaPorUsuarioMail($id_empresa){
        $sql = 'select * FROM lista_contacto as l inner join campo as c
                on c.id_lista = l.id_lista
                where c.campo in ("email")
                and l.borrado = 0
                and l.id_empresa = ' . $id_empresa;                
            
        $result = $this->admDB->query($sql);
        return $result->rows;       
    }

    public function getSMSPredefinidos($id_empresa){ 
        $sql = "select * from mensaje
                  where tipo='SMS'
                  And is_predefinido = 1
                  and id_empresa = ". $id_empresa;

        $result = $this->admDB->query($sql);
        return $result->rows;        
    }

    public function getMailPredefinidos($id_empresa){
        $sql = "select * from mensaje
                where tipo='MAIL'
                And is_predefinido = 1
                and id_empresa = ". $id_empresa;

        $result = $this->admDB->query($sql);
        return $result->rows; 
    }

    public function getListaMensajesPredefinidosPorEmpresa($id_empresa){

        $sql = "select * from rel_usuario as R
                left join lista_contacto as L
                on R.id_usuario = L.id_usuario
                right join envio as E
                ON L.id_lista = E.id_lista
                right join mensaje as M
                ON E.id_mensaje = M.id_mensaje
                where M.is_predefinido = 1
                AND L.id_empresa =".$id_empresa;

        $result = $this->admDB->query($sql);
        return $result->rows;      
    }

    public function listarCampos($lista_id){
 
        $sql = "SELECT * FROM campo where id_lista =". $lista_id;
                  
        $result = $this->admDB->query($sql);
        return $result->rows;
    }

    public function getCantidadContactoPorLista($id_lista){

        $sql = "select COUNT(C.id_contacto) as total
                  from contacto as C
                  where C.id_lista =" . $id_lista ;
        $sql .= " AND C.borrado = 0 ";
                  
        $result = $this->admDB->query($sql);  
        return $result->row['total'];         
    }

    public function getCantidadListasPorIdUsuario($id_empresa){

        $sql = "select COUNT(id_lista) as total
                from lista_contacto as L
                where L.id_empresa =". $id_empresa;
        $sql .= " AND L.borrado = 0"; 
        
        $result = $this->admDB->query($sql);  
        return $result->row['total'];   
    }

    public function getListaPorID($id_lista) {
       
        $sql = 'SELECT * FROM lista_contacto WHERE id_lista = '. $id_lista;
        
        $result = $this->admDB->query($sql);
        return $result->row;
    }

    public function getCamposDeLista($id_lista){
        $result = $this->admDB->query("SELECT * FROM campo WHERE id_lista=".$id_lista);
        return $result->rows;
    }

    public function traerDesinscritos($id_empresa, $data)
    {
        $sql = "select c.nombre, c.id_contacto,c.email, c.telefono, d.campania_desinscrito
                from contacto as c inner join desinscrito as d
                on c.id_contacto = d.id_contacto
                inner join lista_contacto as l
                on c.id_lista = l.id_lista
                and l.id_empresa = " . $id_empresa ."
                where c.desinscrito = 1";

        if (!empty($data['filter_name'])) {
            $sql .= " AND l.nombre LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }        
                

        $sort_data = array(
            'c.nombre'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY c.nombre";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }                        

        $result = $this->admDB->query($sql);

        $resumen = $result->rows;       

        $datos = array();
        
        //crear arreglo ordenado para mostrar en el tpl        
        foreach ($resumen as $des) {
            $arrayName = array('nombre'       => $des['nombre'] ,
                                'id_contacto' => $des['id_contacto'],
                                'email'       => $des['email'],
                                'telefono'    => $des['telefono'],                                
                                'campania_desinscrito' => $des['campania_desinscrito']);
            $datos[] = $arrayName;
        }
       
        return $datos;
    }

    public function getContactosPorLista($id_lista, $data = array()) {
        $sql ="SELECT 
                C.id_contacto, C.id_lista, C.nombre AS nombre_contacto,
                C.email,C.sexo, C.fecha_nac, C.telefono,
                C.celular, C.estado, C.custom1, C.custom2,
                C.custom3,C.custom4, C.custom5,C.custom6,
                C.custom7, C.custom8, C.custom9, C.custom10, 
                L.id_lista, L.nombre, L.fecha_creacion,
                L.ultima_actualizacion, L.id_empresa, L.id_usuario
                FROM contacto AS C 
                INNER JOIN lista_contacto AS L
                ON C.id_lista = L.id_lista
                WHERE L.id_lista =" . $id_lista.
                " AND C.estado = 1
                AND L.borrado = 0 
                AND C.borrado = 0";

        $result = $this->admDB->query($sql); 
        return $result->rows;
    }    

   /* public function getDatosContactosLista_unsorted($id_lista){
        $contactos_info['nombre_columnas'] = array();
        $contactos_info['valores'] = array();

        $result = $this->admDB->query("SELECT * FROM campo WHERE id_lista = ".$id_lista);
        $nombre_campos = $result->rows;

        $consulta = 'SELECT ';
        $cantidad_registros = sizeof($nombre_campos);  

        for ($indice=0; $indice < $cantidad_registros ; $indice++) { 
            if ($indice < $cantidad_registros) {
                $consulta .= $nombre_campos[$indice]['campo'] . ',';
            }
            $contactos_info['nombre_columnas'][] = $nombre_campos[$indice]['glosa']; 
        }

        $consulta .= "id_contacto FROM contacto WHERE id_lista = " . $id_lista;

        $aux = $this->admDB->query($consulta);
        $contactos_info['valores'] = $aux->rows;

        return $contactos_info;
    } */ 

    public function getDatosContactosLista($id_lista, $data = array()){
        $contactos_info['nombre_columnas'] = array();
        $contactos_info['campos_de_contacto'] = array();
        $contactos_info['valores'] = array();

        $result = $this->admDB->query("SELECT * FROM campo WHERE id_lista = " . $id_lista);
        $nombre_campos = $result->rows;

        $sql = 'SELECT ';
        $cantidad_registros = sizeof($nombre_campos);  

        for ($indice=0; $indice < $cantidad_registros ; $indice++) { 
            if ($indice < $cantidad_registros) {
                $sql .= $nombre_campos[$indice]['campo'] . ',';
                $contactos_info['campos_de_contacto'][] = $nombre_campos[$indice]['campo'];
            }
            $contactos_info['nombre_columnas'][] = $nombre_campos[$indice]['glosa']; 
        }

        $contactos_info['campos_de_contacto'][] = 'id_contacto';
        $sql .= " id_contacto FROM contacto WHERE id_lista = " . $id_lista;

        $sql .= " AND borrado = 0 "; 

        if (!empty($data['filter'])) {
            $sql .= " AND nombre LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR email LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR sexo LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR fecha_nac LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR telefono LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR celular LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR custom1 LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR custom2 LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR custom3 LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR custom4 LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR custom5 LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR custom6 LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR custom7 LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR custom8 LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR custom9 LIKE '" . $this->db->escape($data['filter']) . "%' ";
            $sql .= " OR custom10 LIKE '" . $this->db->escape($data['filter']) . "%' ";
        }        
                
        $sql .= " GROUP BY id_contacto ";

        $sort_data = array(
            'nombre',
            'email',
            'sexo',
            'fecha_nac',
            'telefono',
            'celular',
            'custom1',
            'custom2',
            'custom3',
            'custom4',
            'custom5',
            'custom6',
            'custom7',
            'custom8',
            'custom9',          
            'custom10',          
        );


        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY id_lista";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        } 

        $aux = $this->admDB->query($sql);
        $contactos_info['valores'] = $aux->rows;
 
        return $contactos_info;
    }  

    /*Gestion CRUD lista_contactos*/
    public function getLista($id_lista){
        $result_campos = $this->getCamposDeLista($id_lista);

        //arreglos a retornar con nombre y valores
        $datos['nombres'] = array();
        $datos['valores'] = array();

        foreach ($result_campos as $campo => $row) {
            foreach ($row as $valor) {
                $datos['nombres'] = $valor;
            }
        }

        $this->admDB->query();
    }

    public function setFechaActualizacion($id_lista){
        $this->admDB->query('UPDATE lista_contacto SET ultima_actualizacion = NOW() WHERE id_lista =' . $id_lista);
    } 

    public function updateNombreLista($nombre, $id_lista){
        $sql = "UPDATE lista_contacto SET nombre = '" . $nombre . "' WHERE id_lista = " . $id_lista . " AND borrado = 0";
        $result = $this->admDB->query($sql);
        return $result->rows;
    }

    public function deleteLista($id_lista){
        
        $sql = "UPDATE lista_contacto SET borrado = 1 WHERE id_lista = " . $id_lista;
        $result = $this->admDB->query($sql);
        return $result->num_rows;
    }

    public function deleteContacto($id_lista){
        $sql = "UPDATE contacto SET borrado = 1 WHERE id_lista = " . $id_lista;
        $result = $this->admDB->query($sql);
        return $result;
    }


    /*gestion de archivos*/
    public function fullExcel($id_empresa){
        $this->load->library('libreriaExcel/PHPExcel');
        $objPHPExcel = new PHPExcel();
        $listas = $this->getListasEmpresaPorUsuario($id_empresa);
        
        
        foreach ($listas as $clave => $lista) {
            $lista = $this->getListaPorID($lista['id_lista']);
            $contactos = $this->getDatosContactosLista($lista['id_lista']);
            if ($clave>0) {
                $objPHPExcel->createSheet();
            }
            
            $objPHPExcel->setActiveSheetIndex($clave);

            $objPHPExcel->getActiveSheet()->setTitle($lista['nombre']);
            
            $letra_gen  = 65;
            $numero_gen = 1;

            //cargar nombre de las columnas al Excel
            foreach ($contactos['nombre_columnas'] as $nombres) {
                $objPHPExcel->getActiveSheet()->SetCellValue((chr($letra_gen)).($numero_gen),$nombres);
                $letra_gen++;
            }

            $letra_gen = 65; 
            $numero_gen++;

            //cargar datos de las columnas al archivo Excel
            foreach ($contactos['valores'] as $row_index => $row) {
                foreach ($row as $cell_index => $cell_value) {
                    if ($cell_index!='id_contacto') {
                        $objPHPExcel->getActiveSheet()->SetCellValue((chr($letra_gen)).($numero_gen), $cell_value);
                        $letra_gen++; 
                        $objPHPExcel->getActiveSheet()->getColumnDimension(chr($letra_gen))->setAutoSize(true);;
        
                    }
                }
            $letra_gen = 65;
            $numero_gen++;
            }  
        }
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $nombre_archivo = "listas.xlsx";
        $export_result = $objWriter->save(DIR_DOWNLOAD. "Archivos_descargados/". utf8_encode($nombre_archivo));
        $path = DIR_FILE. utf8_encode($nombre_archivo);
        return $path;
    }

    public function fullPDF($user_id){     
    }

    //exporta contactos de una lista
    public function exportarExcel($id_lista){

        $this->load->library('libreriaExcel/PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0); 

        $lista = $this->getListaPorID($id_lista);
        $contactos = $this->getDatosContactosLista($id_lista);

        $letra_gen  = 65;
        $numero_gen = 1;

        //cargar nombre de las columnas al Excel
        foreach ($contactos['nombre_columnas'] as $nombres) {
            $objPHPExcel->getActiveSheet()->SetCellValue((chr($letra_gen)).($numero_gen),$nombres);
            $letra_gen++;
        }

        $letra_gen = 65; 
        $numero_gen++;

        //cargar datos de las columnas al archivo Excel
        foreach ($contactos['valores'] as $row_index => $row) {
            foreach ($row as $cell_index => $cell_value) {
                if ($cell_index!='id_contacto') {
                    $objPHPExcel->getActiveSheet()->SetCellValue((chr($letra_gen)).($numero_gen), $cell_value);
                    $letra_gen++;
                    //columna ancho autosize
                    $objPHPExcel->getActiveSheet()->getColumnDimension(chr($letra_gen))->setAutoSize(true);;
        
                }
            }
            $letra_gen = 65;
            $numero_gen++;
        }

        $nombre_archivo = $lista['nombre']. '.xlsx';        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $export_result = $objWriter->save(DIR_DOWNLOAD. "Archivos_descargados/". utf8_encode($nombre_archivo ));
        
        $path = DIR_FILE. utf8_encode($nombre_archivo);
        return $path;
    }

    public function exportar_listas_ex(){
       /* $names = array("Nombre", "Fecha de Creación", "Contactos");
        $datos = array(
            "nombre"=>array(),
            "creacion"=>array(),
            "cantidad"=>array()
            );

        $cantidades = array();
        
        $listas = $this->getListasEmpresaPorUsuario($this->session->data['id_empresa']);

        foreach ($listas as $lista) {
            array_push($datos['cantidad'], $this->getCantidadContactoPorLista($lista['id_lista']));
            array_push($datos['nombre'], $lista["nombre"]);
            array_push($datos['creacion'], $lista['fecha_creacion']);
        }

        $this->load->library('FileManager');
        $filer = new FileManager();
        $filer->excel($names,$datos,'Mis Listas' );*/
            
            $this->load->library('libreriaExcel/PHPExcel');
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0); 

            $objPHPExcel->getProperties()->setCreator("Nicolas Diaz M")
                                        ->setLastModifiedBy("Nicolas Diaz M")
                                        ->setTitle("Office 2007 XLSX Test Document")
                                        ->setSubject("Archivos exportados")
                                        ->setDescription("Listas de contactos.- Connectus")
                                        ->setKeywords("office 2007 Connectus");
            
            $contactos['nombre_columnas'] = array("Nombre", "Fecha de Creación", "Contactos");
            $datos = array(
                "nombre"=>array(),
                "creacion"=>array(),
                "cantidad"=>array()
                );

            $cantidades = array();
            
            $listas = $this->getListasEmpresaPorUsuario($this->session->data['id_empresa']);

            foreach ($listas as $lista) {
                array_push($datos['cantidad'], $this->getCantidadContactoPorLista($lista['id_lista']));
                array_push($datos['nombre'], $lista["nombre"]);
                array_push($datos['creacion'], $lista['fecha_creacion']);
            }

            $letra_gen  = 65;
            $numero_gen = 1;

            //cargar nombre de las columnas al Excel
            foreach ($datos as $key => $value) {
                $objPHPExcel->getActiveSheet()->SetCellValue((chr($letra_gen)).($numero_gen),$key);
                $letra_gen++;
            }

            $letra_gen = 65;
            $numero_gen++;

            //cargar datos de las columnas al archivo Excel
            foreach ($datos as $row_index => $row) { 
                $objPHPExcel->getActiveSheet()->getColumnDimension(chr($letra_gen))->setAutoSize(true);
                foreach ($row as $cell_index => $cell_value) {
                    $objPHPExcel->getActiveSheet()->SetCellValue((chr($letra_gen)).($numero_gen), $cell_value);                
                    $numero_gen++;
                }
                $numero_gen=2;
                $letra_gen++;           
            }
            

            $nombre_archivo = 'Mis Listas.xlsx';        
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $export_result = $objWriter->save(DIR_DOWNLOAD. "Archivos_descargados/". utf8_encode($nombre_archivo ));
            
            $path = DIR_FILE. utf8_encode($nombre_archivo);
            return $path;
    }

    public function exportar_mensaje_x(){

        $this->load->library('libreriaExcel/PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0); 

        $objPHPExcel->getProperties()->setCreator("Nicolas Diaz M")
                                    ->setLastModifiedBy("Nicolas Diaz M")
                                    ->setTitle("Office 2007 XLSX Test Document")
                                    ->setSubject("Archivos exportados")
                                    ->setDescription("Listas de contactos.- Connectus")
                                    ->setKeywords("office 2007 Connectus");

        $contactos['nombre_columnas'] = array("Título","Mensaje", "Fecha de Creación");
         
        $this->load->model('sms/lista_sms_predefinidos');
        $result_preferidos = $this->model_sms_lista_sms_predefinidos->getSmsPredefinidos($this->session->data['id_empresa']);
        $autor = $this->session->data['firstname'].' '.$this->session->data['lastname'];        

    
        foreach( $result_preferidos as $row ) {                    
            $lista_preferidos[] = array(
                    'titulo' => ($row['titulo']),
                    'mensaje' => ($row['cuerpo']),
                    'autor' => $autor,
                    'fecha_creacion' => $row['fecha_creacion']);
            
        }
        //cargar nombre de las columnas al Excel        
        $objPHPExcel->getActiveSheet()->SetCellValue("A1","Título");        
        $objPHPExcel->getActiveSheet()->SetCellValue("B1","Mensaje");    
        $objPHPExcel->getActiveSheet()->SetCellValue("C1","Autor");    
        $objPHPExcel->getActiveSheet()->SetCellValue("D1","Fecha de Creacion");        

        for ($i=65; $i < 69 ; $i++) { 
            $objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);;
        }

        $letra_gen = 65;
        $numero_gen = 2;

        foreach ($lista_preferidos as $row_index => $row) { 
            foreach ($row as $cell_index => $cell_value) {
                $objPHPExcel->getActiveSheet()->SetCellValue((chr($letra_gen)).($numero_gen), $cell_value);                
                $letra_gen++;
            }
            $letra_gen=65;
            $numero_gen++;           
        }  

        $nombre_archivo = 'SMS Predef.xlsx';        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $export_result = $objWriter->save(DIR_DOWNLOAD. "Archivos_descargados/". utf8_encode($nombre_archivo ));
        
        $path = DIR_FILE. utf8_encode($nombre_archivo);
        return $path;
    }

    public function exportarPDF($id_lista) {

        $this->load->library('libreriaExcel/dompdf_0-6-0_beta3/dompdf_config.inc');
        $this->load->language('contactos/pdf');

        $lista = $this->getListaPorID($id_lista);
        $contactos = $this->getDatosContactosLista($id_lista);
            
        $content= $this->language->get('inicio_pdf');
        
        //cargar nombre de las columnas al Excel
        $content .= "<tr><th>#</th>";
        foreach ($contactos['nombre_columnas'] as $nombres) {
            $content .= "<th>". $nombres ."</th>";
        }
        $content .= "</tr>";

        $aux = 1;
        //cargar datos de las columnas al archivo Excel
        foreach ($contactos['valores'] as $row_index => $row) {
                $content .= "<tr><td>". $aux ."</td>";
                foreach ($row as $cell_index => $cell_value) {
                    if($cell_index != 'id_contacto'){
                        $content .= "<td>". $cell_value ."</td>";                        
                    }
                }$aux++;
                $content .= "</tr>"; 
            
        }

        $content .= $this->language->get('fin_pdf'); 

        $dompdf = new DOMPDF();           
        $dompdf->load_html($content);
        $dompdf->render();
        $dompdf->stream($lista['nombre'].'.pdf');                            
    }

    public function exportar_listas_pdf(){
        $this->load->library('libreriaExcel/dompdf_0-6-0_beta3/dompdf_config.inc');
        $this->load->language('contactos/pdf');
            
       $contactos['nombre_columnas'] = array("Nombre", "Fecha de Creación", "Contactos");
        $datos = array(
            "nombre"=>array(),
            "creacion"=>array(),
            "cantidad"=>array()
            );

        
        $listas = $this->getListasEmpresaPorUsuario($this->session->data['id_empresa']);

        foreach ($listas as $lista) {
            array_push($datos['cantidad'], $this->getCantidadContactoPorLista($lista['id_lista']));
            array_push($datos['nombre'], $lista["nombre"]);
            array_push($datos['creacion'], $lista['fecha_creacion']);
        }

        $content= $this->language->get('inicio_pdf');
        
        //cargar nombre de las columnas al PDF
        $content .= "<tr><th>#</th>";
        $content .= "<th>Nombre</th>";
        $content .= "<th>Fecha de Creaci&oacute;n</th>";
        $content .= "<th>Contactos</th>";
        $content .= "</tr>";


        $aux = 1;
                
        for($i = 0; $i < count($datos['nombre']); $i++){            
            $content .= "<tr><td>". $aux ."</td>";
                $content .= "<td>" . utf8_encode($datos['nombre'][$i]) . "</td>";
                $content .= "<td>" . utf8_encode($datos['creacion'][$i]) . "</td>";
                $content .= "<td>" . utf8_encode($datos['cantidad'][$i]). "</td>";
            
            $aux++;
            $content .= "</tr>";
        }

        $content .= $this->language->get('fin_pdf'); 

        $dompdf = new DOMPDF();           
        $dompdf->load_html($content);
        $dompdf->render();
        $dompdf->stream('Mis listas.pdf'); 
    }

    public function exportar_sms_predef_pdf(){
        $this->load->library('libreriaExcel/dompdf_0-6-0_beta3/dompdf_config.inc');
        $this->load->language('contactos/pdf');

        $this->load->model('sms/lista_sms_predefinidos');
        $result_preferidos = $this->model_sms_lista_sms_predefinidos->getSmsPredefinidos($this->session->data['id_empresa']);
        $autor = $this->session->data['firstname'].' '.$this->session->data['lastname'];        

    
        foreach( $result_preferidos as $row ) {                    
            $datos[] = array(
                    'titulo' => ($row['titulo']),
                    'mensaje' => ($row['cuerpo']),
                    'autor' => $autor,
                    'fecha_creacion' => $row['fecha_creacion']);
            
        }

        $content= $this->language->get('inicio_pdf');
        
        //cargar nombre de las columnas al PDF
        $content .= "<tr><th>#</th>";
        $content .= "<th>T&iacute;tulo</th>";
        $content .= "<th>Mensaje</th>";
        $content .= "<th>Autor</th>";
        $content .= "<th>Fecha de Creaci&oacute;n</th>";
        $content .= "</tr>";       

        //echo json_encode($datos[0]);

        $aux = 1;
                
        for($i = 0; $i < count($datos); $i++){            
            $content .= "<tr><td>". $aux ."</td>";
                $content .= "<td>" . utf8_encode($datos[$i]['titulo']) . "</td>";
                $content .= "<td>" . utf8_encode($datos[$i]['mensaje']) . "</td>";
                $content .= "<td>" . utf8_encode($datos[$i]['autor']). "</td>";
                $content .= "<td>" . utf8_encode($datos[$i]['fecha_creacion']). "</td>";
            
            $aux++;
            $content .= "</tr>";
        }

        $content .= $this->language->get('fin_pdf'); 


        $dompdf = new DOMPDF();           
        $dompdf->load_html($content);
        $dompdf->render();
        $dompdf->stream('Mis sms predefinidos.pdf'); 
    }

    public function exportar_mail_predef_pdf(){
        $this->load->library('libreriaExcel/dompdf_0-6-0_beta3/dompdf_config.inc');
        $this->load->language('contactos/pdf');

        $this->load->model('mailing/lista_mails_predefinidos');
        
        $cantidad_listas = $this->model_mailing_lista_mails_predefinidos->getCountMailPredefinidos($this->session->data['id_empresa']);
        $data['lista_preferidos'] = '';
        $result_preferidos = $this->model_mailing_lista_mails_predefinidos->getMailPredefinidos($this->session->data['id_empresa']);

        $autor = $this->session->data['firstname'].' '.$this->session->data['lastname'];
        
        foreach( $result_preferidos as $row ) {                    
            $datos[] = array(                    
                    'titulo' => ($row['titulo']),
                    'mensaje' => ($row['titulo']),
                    'autor' => $autor,
                    'fecha_creacion' => $row['fecha_creacion']);
        }

        $content= $this->language->get('inicio_pdf');
        
        //cargar nombre de las columnas al PDF
        $content .= "<tr><th>#</th>";
        $content .= "<th>T&iacute;tulo</th>";
        $content .= "<th>Mensaje</th>";
        $content .= "<th>Autor</th>";
        $content .= "<th>Fecha de Creaci&oacute;n</th>";
        $content .= "</tr>";       

        //echo json_encode($datos[0]);

        $aux = 1;
                
        for($i = 0; $i < count($datos); $i++){            
            $content .= "<tr><td>". $aux ."</td>";
                $content .= "<td>" . utf8_encode($datos[$i]['titulo']) . "</td>";
                $content .= "<td>" . utf8_encode($datos[$i]['mensaje']) . "</td>";
                $content .= "<td>" . utf8_encode($datos[$i]['autor']). "</td>";
                $content .= "<td>" . utf8_encode($datos[$i]['fecha_creacion']). "</td>";
            
            $aux++;
            $content .= "</tr>";
        }

        $content .= $this->language->get('fin_pdf'); 


        $dompdf = new DOMPDF();           
        $dompdf->load_html($content);
        $dompdf->render();
        $dompdf->stream('Mis mail predefinidos.pdf'); 
    }

    public function exportar_mail_x(){

        $this->load->library('libreriaExcel/PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0); 

        $objPHPExcel->getProperties()->setCreator("Nicolas Diaz M")
                                    ->setLastModifiedBy("Nicolas Diaz M")
                                    ->setTitle("Office 2007 XLSX Test Document")
                                    ->setSubject("Archivos exportados")
                                    ->setDescription("Listas de contactos.- Connectus")
                                    ->setKeywords("office 2007 Connectus");

         $this->load->model('mailing/lista_mails_predefinidos');
        
        $cantidad_listas = $this->model_mailing_lista_mails_predefinidos->getCountMailPredefinidos($this->session->data['id_empresa']);
        $data['lista_preferidos'] = '';
        $result_preferidos = $this->model_mailing_lista_mails_predefinidos->getMailPredefinidos($this->session->data['id_empresa']);

        $autor = $this->session->data['firstname'].' '.$this->session->data['lastname'];
        
        foreach( $result_preferidos as $row ) {                    
            $datos[] = array(                    
                    'titulo' => ($row['titulo']),
                    'mensaje' => ($row['titulo']),
                    'autor' => $autor,
                    'fecha_creacion' => $row['fecha_creacion']);
        }

        //cargar nombre de las columnas al Excel        
        $objPHPExcel->getActiveSheet()->SetCellValue("A1","Título");        
        $objPHPExcel->getActiveSheet()->SetCellValue("B1","Mensaje");    
        $objPHPExcel->getActiveSheet()->SetCellValue("C1","Autor");    
        $objPHPExcel->getActiveSheet()->SetCellValue("D1","Fecha de Creacion");        

        $letra_gen = 65;
        $numero_gen = 2;

        for ($i=65; $i < 69 ; $i++) { 
            $objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);;
        }

        foreach ($datos as $row_index => $row) { 
            foreach ($row as $cell_index => $cell_value) {
                $objPHPExcel->getActiveSheet()->SetCellValue((chr($letra_gen)).($numero_gen), $cell_value);                
                $letra_gen++;
            }
            $letra_gen=65;
            $numero_gen++;           
        } 

        $nombre_archivo = 'Mail Predef.xlsx';        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $export_result = $objWriter->save(DIR_DOWNLOAD. "Archivos_descargados/". utf8_encode($nombre_archivo ));
        
        $path = DIR_FILE. utf8_encode($nombre_archivo);
        return $path;
    }

    public function exportar_historial_excel($datos)
    {
       $this->load->library('libreriaExcel/PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0); 

        $objPHPExcel->getProperties()->setCreator("Nicolas Diaz M")
                                    ->setLastModifiedBy("Nicolas Diaz M")
                                    ->setTitle("Office 2007 XLSX Resumen")
                                    ->setSubject("Archivos exportados")
                                    ->setDescription("Historial de Envios MAIL.- Connectus")
                                    ->setKeywords("Office 2007 Connectus");      

        $letra_gen = 65;
        $numero_gen = 1;

        
       $objPHPExcel->getActiveSheet()->SetCellValue("A".($numero_gen), "Fecha Envio");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("B".($numero_gen), "Nombre Envio");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("C".($numero_gen), "Estado");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("D".($numero_gen), "Volumen");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("E".($numero_gen), "Entregados");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("F".($numero_gen), "Malos");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("G".($numero_gen), "Rebotes");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("H".($numero_gen), "Click");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("I".($numero_gen), "Leidos");

        $numero_gen++;    

        for ($i=65; $i < 74 ; $i++) { 
            $objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);;
        }           


        foreach ($datos as $value) {
           $objPHPExcel->getActiveSheet()->SetCellValue("A".($numero_gen), $value['fecha']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("B".($numero_gen), $value['nombre']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("C".($numero_gen), $value['estado']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("D".($numero_gen), $value['volumen']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("E".($numero_gen), $value['entregados']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("F".($numero_gen), $value['malos']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("G".($numero_gen), $value['rebotes']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("H".($numero_gen), $value['click']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("I".($numero_gen), $value['leidos']);                  
           $numero_gen++;
         } 

        
        $nombre_archivo = 'Historial Mailing.xlsx';        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $export_result = $objWriter->save(DIR_DOWNLOAD. "Archivos_descargados/". utf8_encode($nombre_archivo ));
        
        $path = DIR_FILE. utf8_encode($nombre_archivo);
        return $path;
    }

    public function exportar_historial_pdf($datos)
    {
        $this->load->library('libreriaExcel/dompdf_0-6-0_beta3/dompdf_config.inc');
        $this->load->language('contactos/pdf');
        

        $content= $this->language->get('inicio_pdf');
        
        //cargar nombre de las columnas al PDF
        $content .= "<tr><th>#</th>";
        $content .= "<th>Fecha Env&iacute;o</th>";
        $content .= "<th>Nombre</th>";
        $content .= "<th>Estado</th>";
        $content .= "<th>Volumen</th>";
        $content .= "<th>Entregados</th>";
        $content .= "<th>Malos</th>";
        $content .= "<th>Rebotes</th>";
        $content .= "<th>Click</th>";
        $content .= "<th>Le&iacute;dos</th>";
        $content .= "</tr>";       

        $aux = 1;
        foreach ($datos as $value) {
            $content .= "<tr><td>". $aux ."</td>";
            $content .= "<td>" . $value['fecha']. "</td>";                 
            $content .= "<td>" . $value['nombre']. "</td>";                 
            $content .= "<td>" . $value['estado']. "</td>";          
            $content .= "<td>" . $value['volumen']. "</td>";           
            $content .= "<td>" .$value['entregados']. "</td>";             
            $content .= "<td>" .$value['malos']. "</td>";     
            $content .= "<td>" . $value['rebotes']. "</td>";            
            $content .= "<td>" .$value['click']. "</td>";        
            $content .= "<td>" . $value['leidos']. "</td>"; 
            $aux++;
            $content .= "</tr>";          

         }

        $content .= $this->language->get('fin_pdf'); 


        $dompdf = new DOMPDF();           
        $dompdf->load_html($content);
        $dompdf->render();
        $dompdf->stream('Hitorial Mail.pdf'); 
    }

    public function exportar_historial_excel_sms($datos)
    {
       $this->load->library('libreriaExcel/PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0); 

        $objPHPExcel->getProperties()->setCreator("Nicolas Diaz M")
                                     ->setLastModifiedBy("Nicolas Diaz M")
                                     ->setTitle("Office 2007 XLSX Resumen")
                                     ->setSubject("Archivos exportados")
                                     ->setDescription("Historial de Envios MAIL.- Connectus")
                                     ->setKeywords("Office 2007 Connectus");      

        $letra_gen = 65;
        $numero_gen = 1;

        
       $objPHPExcel->getActiveSheet()->SetCellValue("A".($numero_gen), "Fecha Envio");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("B".($numero_gen), "Nombre Envio");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("C".($numero_gen), "Estado");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("D".($numero_gen), "Tipo Envio");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("E".($numero_gen), "Volumen");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("F".($numero_gen), "Entregados");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("G".($numero_gen), "Malos");
       //agregadas
       $objPHPExcel->getActiveSheet()->SetCellValue("H".($numero_gen), "Error");
       $objPHPExcel->getActiveSheet()->SetCellValue("I".($numero_gen), "Por Confirmar");

        $numero_gen++;    

        for ($i=65; $i < 75 ; $i++) { 
            $objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);;
        }           

        foreach ($datos as $value) {
           $objPHPExcel->getActiveSheet()->SetCellValue("A".($numero_gen), $value['fecha']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("B".($numero_gen), $value['nombre']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("C".($numero_gen), $value['estado']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("D".($numero_gen), $value['tipo']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("E".($numero_gen), $value['volumen']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("F".($numero_gen), $value['confirmados']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("G".($numero_gen), $value['malos']); 
           //esperando                 
           $objPHPExcel->getActiveSheet()->SetCellValue("H".($numero_gen), $value['error']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("I".($numero_gen), $value['esperando']);                  
           $numero_gen++;
         } 

        
        $nombre_archivo = 'Historial SMS.xlsx';        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $export_result = $objWriter->save(DIR_DOWNLOAD. "Archivos_descargados/". utf8_encode($nombre_archivo ));
        
        $path = DIR_FILE. utf8_encode($nombre_archivo);
        return $path;
    }

    public function exportar_historial_pdf_sms($datos)
    {
        $this->load->library('libreriaExcel/dompdf_0-6-0_beta3/dompdf_config.inc');
        $this->load->language('contactos/pdf');
        

        $content= $this->language->get('inicio_pdf');
        
        //cargar nombre de las columnas al PDF
        $content .= "<tr><th>#</th>";
        $content .= "<th>Fecha</th>";
        $content .= "<th>Campaña</th>";
        $content .= "<th>Estado</th>";
        $content .= "<th>Tipo</th>";
        $content .= "<th>Volumen</th>";
        $content .= "<th>Confirmados</th>";
        $content .= "<th>Malos</th>";
        $content .= "<th>Error</th>";
        $content .= "<th>Por Confirmar</th>";
        $content .= "</tr>";       


        $aux = 1;
        foreach ($datos as $value) {
            $content .= "<tr><td>". $aux ."</td>";
            $content .= "<td>" . $value['fecha']. "</td>";                 
            $content .= "<td>" . $value['nombre']. "</td>";                 
            $content .= "<td>" . $value['estado']. "</td>";          
            $content .= "<td>" . $value['tipo']. "</td>";          
            $content .= "<td>" . $value['volumen']. "</td>";           
            $content .= "<td>" .$value['confirmados']. "</td>";             
            $content .= "<td>" .$value['malos']. "</td>";
            $content .= "<td>" .$value['error']. "</td>";
            $content .= "<td>" .$value['esperando']. "</td>";
            $aux++;
            $content .= "</tr>";          

         }

        $content .= $this->language->get('fin_pdf'); 


        $dompdf = new DOMPDF();           
        $dompdf->load_html($content);
        $dompdf->render();
        $dompdf->stream('Historial SMS.pdf'); 
    }

    public function excel_desinscritos($id_empresa)
    {
        $this->load->library('libreriaExcel/PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0); 

        $this->load->model('mailing/desinscritos');
        $datos = $this->model_mailing_desinscritos->traerDesinscritos($this->session->data['id_empresa']);

        $letra_gen = 65;
        $numero_gen = 1;

        
       $objPHPExcel->getActiveSheet()->SetCellValue("A".($numero_gen), "Fecha");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("B".($numero_gen), "Email Remitente");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("C".($numero_gen), "Correo Desinscrito");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("D".($numero_gen), "Asunto del Envío"); 

        $numero_gen++;    

        for ($i=65; $i < 70 ; $i++) { 
            $objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);;
        }           

        foreach ($datos as $value) {
           $objPHPExcel->getActiveSheet()->SetCellValue("A".($numero_gen), $value['fecha']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("B".($numero_gen), $value['remitente']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("C".($numero_gen), $value['email']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("D".($numero_gen), $value['campania_desinscrito']);       
           $numero_gen++;
         } 

        
        $nombre_archivo = 'Desinscritos.xlsx';        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $export_result = $objWriter->save(DIR_DOWNLOAD. "Archivos_descargados/". utf8_encode($nombre_archivo ));
        
        $path = DIR_FILE. utf8_encode($nombre_archivo);
        return $path;
    }

    public function pdf_desinscritos($id_usuario)
    {
        $this->load->library('libreriaExcel/dompdf_0-6-0_beta3/dompdf_config.inc');
        $this->load->language('contactos/pdf');
        
        $this->load->model('mailing/desinscritos');
        $datos = $this->model_mailing_desinscritos->traerDesinscritos($this->session->data['id_empresa']);
        $content= $this->language->get('inicio_pdf');
        
        //cargar nombre de las columnas al PDF
        $content .= "<tr><th>#</th>";
        $content .= "<th>Fecha</th>";
        $content .= "<th>Email Remitente</th>";
        $content .= "<th>Correo Desinscrito</th>";
        $content .= "<th>Asunto del Envío</th>";
        $content .= "</tr>";       


        $aux = 1;
        foreach ($datos as $value) {
            $content .= "<tr><td>". $aux ."</td>";
            $content .= "<td>" . $value['fecha']. "</td>";                 
            $content .= "<td>" . $value['remitente']. "</td>";                 
            $content .= "<td>" . $value['email']. "</td>";          
            $content .= "<td>" . $value['campania_desinscrito']. "</td>";
            $aux++;
            $content .= "</tr>";          

         }

        $content .= $this->language->get('fin_pdf'); 


        $dompdf = new DOMPDF();           
        $dompdf->load_html($content);
        $dompdf->render();
        $dompdf->stream('desinscritos.pdf');
    }

     public function rebotesPDF($id_empresa)
    {
        $this->load->library('libreriaExcel/dompdf_0-6-0_beta3/dompdf_config.inc');
        $this->load->language('contactos/pdf');

        $this->load->model('mailing/envio');
         $datos = $this->model_mailing_envio->getRebotesPorUsuario($id_empresa);
        
        $content= $this->language->get('inicio_pdf');
        
        //cargar nombre de las columnas al PDF
        $content .= "<tr><th>#</th>";
        $content .= "<th>Fecha de Envio</th>";
        $content .= "<th>Email</th>";
        $content .= "<th>Nombre Remitente</th>";
        $content .= "<th>Correo Remitente</th>";
        $content .= "</tr>";       


        $aux = 1;
        foreach ($datos as $value) {
            $content .= "<tr><td>". $aux ."</td>";
            $content .= "<td>" . $value['fecha_envio']. "</td>";                 
            $content .= "<td>" . $value['email']. "</td>";                 
            $content .= "<td>" . $value['nombre_remitente']. "</td>";          
            $content .= "<td>" . $value['correo_remitente']. "</td>";
            $aux++;
            $content .= "</tr>";          

         }

        $content .= $this->language->get('fin_pdf'); 


        $dompdf = new DOMPDF();           
        $dompdf->load_html($content);
        $dompdf->render();
        $dompdf->stream('Rebotes.pdf');
    }

    public function rebotesExcel($id_empresa)
    {
        $this->load->library('libreriaExcel/PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0); 

        $this->load->model('mailing/envio');
        $datos = $this->model_mailing_envio->getRebotesPorUsuario($id_empresa);

        $letra_gen = 65;
        $numero_gen = 1;

        
       $objPHPExcel->getActiveSheet()->SetCellValue("A".($numero_gen), "Fecha de Envio");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("B".($numero_gen), "Email");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("C".($numero_gen), "Nombre Remitente");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("D".($numero_gen), "Correo Remitente"); 

        $numero_gen++;    

        for ($i=65; $i < 70 ; $i++) { 
            $objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);;
        }           

        foreach ($datos as $value) {
           $objPHPExcel->getActiveSheet()->SetCellValue("A".($numero_gen), $value['fecha_envio']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("B".($numero_gen), $value['email']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("C".($numero_gen), $value['nombre_remitente']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("D".($numero_gen), $value['correo_remitente']);       
           $numero_gen++;
         } 

        
        $nombre_archivo = 'Rebotes.xlsx';        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $export_result = $objWriter->save(DIR_DOWNLOAD. "Archivos_descargados/". utf8_encode($nombre_archivo ));
        
        $path = DIR_FILE. utf8_encode($nombre_archivo);
        return $path;
    }

    public function recibidosExcel($filter_data)
    {
        $this->load->library('libreriaExcel/PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0); 

        $this->load->model('sms/recibidos');
        $datos = $this->model_sms_recibidos->getRecibidos($filter_data);

        $letra_gen = 65;
        $numero_gen = 1;

        
       $objPHPExcel->getActiveSheet()->SetCellValue("A".($numero_gen), "Fecha");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("B".($numero_gen), "Remitente");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("C".($numero_gen), "Mensaje");                  
       $objPHPExcel->getActiveSheet()->SetCellValue("D".($numero_gen), "Destino"); 

        $numero_gen++;    

        for ($i=65; $i < 70 ; $i++) { 
            $objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setAutoSize(true);;
        }           

        foreach ($datos as $value) {
           $objPHPExcel->getActiveSheet()->SetCellValue("A".($numero_gen), $value['fecha']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("B".($numero_gen), $value['remitente']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("C".($numero_gen), $value['mensaje']);                  
           $objPHPExcel->getActiveSheet()->SetCellValue("D".($numero_gen), $value['destinatario']);       
           $numero_gen++;
         } 

        
        $nombre_archivo = 'Mis recibidos.xlsx';        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $export_result = $objWriter->save(DIR_DOWNLOAD. "Archivos_descargados/". utf8_encode($nombre_archivo ));
        
        $path = DIR_FILE. utf8_encode($nombre_archivo);
        return $path;
    }

     public function recibidosPDF()
    {
        $this->load->library('libreriaExcel/dompdf_0-6-0_beta3/dompdf_config.inc');
        $this->load->language('contactos/pdf');

        $this->load->model('sms/recibidos');
        $datos = $this->model_sms_recibidos->getRecibidos();
        
        $content= $this->language->get('inicio_pdf');
        
        //cargar nombre de las columnas al PDF
        $content .= "<tr><th>#</th>";
        $content .= "<th>Fecha</th>";
        $content .= "<th>Remitente</th>";
        $content .= "<th>Mensaje</th>";
        $content .= "<th>Destino</th>";
        $content .= "</tr>";       


        $aux = 1;
        foreach ($datos as $value) {
            $content .= "<tr><td>". $aux ."</td>";
            $content .= "<td>" . $value['fecha']. "</td>";                 
            $content .= "<td>" . $value['remitente']. "</td>";                 
            $content .= "<td>" . $value['mensaje']. "</td>";          
            $content .= "<td>" . $value['destinatario']. "</td>";
            $aux++;
            $content .= "</tr>";          

         }

        $content .= $this->language->get('fin_pdf'); 


        $dompdf = new DOMPDF();           
        $dompdf->load_html($content);
        $dompdf->render();
        $dompdf->stream('Mis Recibidos.pdf');
    }
}  
             