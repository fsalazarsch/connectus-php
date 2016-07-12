<?php
class ModelMailingEnvio extends Model{	
	public function getEnvioPorUsuario($id_empresa, $data = array() )
	{
		$sql =  "SELECT id_envio,cuando_enviar,nombre_envio, estado,tipo_envio, correos_malos, datos_envio_programado FROM envio";
        $sql .= " WHERE id_empresa = " . $id_empresa;

        if(!empty($data['tipo_envio'])){
        	$sql .= " AND tipo_envio = '" . $data['tipo_envio'] . "'";
        }

        if (!empty($data['tipo_mensaje'])) {
        	$sql .= " AND tipo_mensaje = upper('" . $data['tipo_mensaje'] . "')";
        }

        if (!empty($data['filter_nombre'])) {            
            $sql .= " AND nombre_envio LIKE '%" . $this->db->escape($data['filter_nombre']) . "%'";            
        } 

        if (!empty($data['filter_fecha'])) {
            $sql .= " AND cuando_enviar LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";
        }
 
        $sort_data = array(
                    'nombre_envio',
                    'cuando_enviar',
                    'estado'           
            	);

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY cuando_enviar";
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
                $data['limit'] = 15;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        //envios del usuario consultado
        $result_envio = $this->admDB->query($sql);
        $resumen = $result_envio->rows; 
 
        $datos = array();
        
        //crear arreglo ordenado para mostrar en el tpl
        foreach ($resumen as $envio) {
            /*
            $json = trim(preg_replace('/\s+/', ' ', $envio['datos_envio_programado']));
            $array = json_decode($json, true);  

            $volumen_pendiente = count($array['destinatarios']['valores']);
            */

            $arrayName = array('id_envio' => $envio['id_envio'] ,
                                'fecha' => $envio['cuando_enviar'],
                                'nombre' => $envio['nombre_envio'],
                                'tipo' => $envio['tipo_envio'],
                                'estado' => $this->traducirEstado($envio['estado']),
                                'volumen' => $this->total_envio($envio['id_envio']),
                                'malos' => $envio['correos_malos'],
                                'entregados' => $this->entregados($envio['id_envio']),
                                'esperando' => $this->esperando($envio['id_envio']),
                                'click' =>  $this->clicks($envio['id_envio']),
                                'leidos' => $this->leidos($envio['id_envio']),
                                'rebotes' =>  $this->rebotes($envio['id_envio']),
                                'cant_detalles' => $this->contarDetalle($envio['id_envio']) );
            $datos[] = $arrayName;
        }
       
        return $datos;
	}

    public function total_envio($id_envio)
    {
        $sql = "select COUNT(id_detalle_envio) as total 
                from detalle_envio 
                where id_envio = " . $id_envio ;
        $result = $this->admDB->query($sql);

        return $result->row['total'];
    }

    public function getEnvioUnicoPorUsuario($id_empresa, $data = array() )
    {
        $sql = "SELECT id_envio,cuando_enviar,nombre_envio, estado,tipo_envio, correos_malos, correo_remitente, remitente , datos_envio_programado FROM envio";
        $sql .= " WHERE id_empresa = " . $id_empresa;

        if(!empty($data['tipo_envio'])){
            $sql .= " AND tipo_envio = '" . $data['tipo_envio'] . "'";
        }

        if (!empty($data['tipo_mensaje'])) {
            $sql .= " AND tipo_mensaje = upper('" . $data['tipo_mensaje'] . "')";
        }

        if (!empty($data['filter_nombre'])) {            
            $sql .= " AND nombre_envio LIKE '%" . $this->db->escape($data['filter_nombre']) . "%'";            
        } 

        if (!empty($data['filter_fecha'])) {
            $sql .= " AND cuando_enviar LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";
        }
 
        $sort_data = array(
                    'nombre_envio',
                    'cuando_enviar',
                    'estado'           
                );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY cuando_enviar";
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
                $data['limit'] = 15;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        

        //envios del usuario consultado
        $result_envio = $this->admDB->query($sql);
        $resumen = $result_envio->rows;       

        $datos = array();
        $count = 0;
        
        //crear arreglo ordenado para mostrar en el tpl
        foreach ($resumen as $envio) {
            $detalle = $this->getDetalleUnico($envio['id_envio']);
            $mensaje = $this->getMensaje($envio['id_envio']);
            
            

            if (isset($mensaje['cuerpo'])) {
                $texto = $mensaje['cuerpo'];
            
            }elseif (isset($envio['datos_envio_programado'])) {

                $json = trim(preg_replace('/\s+/', ' ', $envio['datos_envio_programado']));
                $array = json_decode($json, true);  
                $texto = $array['message']; 
            
            }else{
                $texto = 'vacio';
            }

            if (isset($detalle['estado'])) {
                if ($detalle['estado']=='') {
                    $estado = 'desconocido';;
                }else{
                    $estado = $detalle['estado'];
                }                
            }else{
                $estado = 'desconocido';
            }

            if (isset($detalle['destinatario'])) {
                $destino = $detalle['destinatario'];
            }elseif (isset($envio['datos_envio_programado'])) {
                $json = trim(preg_replace('/\s+/', ' ', $envio['datos_envio_programado']));
                $array = json_decode($json, true);   
                $destino = $array['destinatario']; 
            }else{
                $destino = 'vacio';
            }

            $count++;

            $arrayName = array(  'numero'       => $count,
                                'id_envio'      => $envio['id_envio'] ,
                                'fecha'        => $envio['cuando_enviar'],
                                'nombre'       => $envio['nombre_envio'],
                                'remitente'    => $envio['correo_remitente'],
                                'tipo'         => $envio['tipo_envio'],                                
                                'mensaje'      => html_entity_decode(html_entity_decode($texto)),
                                'estado'       => $this->traducirEstado($estado),
                                'destinatario' => $destino
                                );
            $datos[] = $arrayName;
        }
       
        return $datos;
    }  

    public function getDetalleUnico($id_envio)
    {
        $sql = "SELECT * FROM detalle_envio WHERE id_envio = " . $id_envio . " LIMIT 1";
        $result = $this->admDB->query($sql);

        return $result->row;
    } 

    /*MENSAJE TEXTUAL COMO EL USUARIO LO INGRESA AL EDITOR */
    public function getMensaje($id_envio)
    {
        $sql = "select m.* from detalle_envio as d inner join envio as e
                on d.id_envio = e.id_envio inner join mensaje as m
                on e.id_mensaje = m.id_mensaje
                where e.id_envio =" . $id_envio;

        $result = $this->admDB->query($sql);
        return $result->row;
    } 

    public function decodMensaje($id_envio,$id_contacto,$destinatario)
    {
        $sql = "select id_mensaje as mensaje,tipo_envio as tipo, id_lista as lista , correo_remitente as correo from envio where id_envio = " . $id_envio;
        $aux = $this->admDB->query($sql);
        $msg = $aux->row['mensaje'];
        $lista = $aux->row['lista'];
        $correo = $aux->row['correo'];
        $tipo = $aux->row['tipo'];

        $sql = "select cuerpo from mensaje where id_mensaje =" . $msg;
        $aux = $this->admDB->query($sql);
        $generico = $aux->row['cuerpo'];

        $sql = "select campo,glosa from campo where id_lista =" . $lista;
        $aux = $this->admDB->query($sql);
        $campos = $aux->rows;

        $seleccionar = '';
        $count = count($campos);

        for ($i=0; $i < $count ; $i++) { 
            $seleccionar .= $campos[$i]['campo'] . ' as ' . $campos[$i]['glosa'];
            if ($i < $count - 1) {
                $seleccionar .= ',';
            }
        }

        $this->load->model('contactos/contacto');
        $aux = $this->model_contactos_contacto->getContactoPorEmail($seleccionar, $destinatario, $lista );

        if ($aux->num_rows == 1) {
            $datos = $aux->row;
        }else{
            $aux = $this->model_contactos_contacto->getContactoPoId($seleccionar, $id_contacto, $lista );
            $datos = $aux->row;
        }
        

        $campos_gen = array();
        $campos_contacto = array();

        foreach ($datos as $key => $value) {
            $campos_gen[] = '%' . $key . '%';
            $campos_contacto[] = $value;
        }

        $personalizado['mensaje'] = str_ireplace($campos_gen, $campos_contacto, $generico);
        $personalizado['datos'] = $campos_contacto;
        $personalizado['id_contacto'] = $id_contacto;
        $personalizado['id_envio'] = $id_envio;
        $personalizado['correo'] = $correo;
        $personalizado['destinatario'] = $destinatario;
        $personalizado['tipo'] = $tipo;

        return $personalizado;
    }

    public function contarDetalle($id_envio, $data = array())
    {
        $sql = "SELECT count(id_detalle_envio) volumen from detalle_envio as D WHERE id_envio = " . $id_envio;

        if (isset($data['filter'])) {
            if ($data['filter'] == 'rebote') {
                $sql .= " AND D.estado in ('failed','rejected','stored','complained','bounced','hard_bounce')";
            }elseif ($data['filter'] == 'click') {
                $sql .= " AND D.estado in ('clicked','click')";
            }elseif ($data['filter'] == 'abierto') {
                $sql .= " AND D.estado in ('opened','open')";
            }elseif ($data['filter'] == 'entregado') {
                $sql .= " AND D.estado in ('delivered','accepted', 'sent','send')";
            }elseif ($data['filter'] == 'esperando') {
                $sql .= " AND D.estado in ('queued','en proceso')";
            }elseif ($data['filter'] == 'malo') {
                $sql .= " AND D.estado in ('malo')";
            }
        }

        $volumen = $this->admDB->query($sql);        
        $total = $volumen->num_rows > 0 ? $volumen->row['volumen'] : 0;
        return $total;
    }

	public function volumen_envio($id_envio, $mensaje)
    {
        $sql = "SELECT count(id_detalle_envio) volumen from detalle_envio WHERE id_envio = " . $id_envio;
        $volumen = $this->admDB->query($sql);
        $total = $volumen->row['volumen'] ;

        return $total;
    }

    public function malos($id_envio)
    {
        $sql  = "SELECT count(id_detalle_envio) AS malos from detalle_envio WHERE id_envio = " . $id_envio;
        $sql .= " AND estado in ('malo')";
        $malos = $this->admDB->query($sql);
        return $malos->row['malos'];
    }

	public function entregados($id_envio)
	{
		$sql  = "SELECT count(id_detalle_envio) entregados from detalle_envio WHERE id_envio = " . $id_envio;
		$sql .= " AND estado in ('delivered','accepted','sent','send')";
		$entregados = $this->admDB->query($sql);
		return $entregados->row['entregados'];
	}

    public function esperando($id_envio)
    {
        $sql  = "SELECT count(id_detalle_envio) entregados from detalle_envio WHERE id_envio = " . $id_envio;
        $sql .= " AND estado in ('en proceso','queued')";
        $entregados = $this->admDB->query($sql);
        return $entregados->row['entregados'];
    }

	public function leidos($id_envio)
	{
		$sql  = "SELECT count(id_detalle_envio) AS leidos from detalle_envio WHERE id_envio = " . $id_envio;
		$sql .= " AND estado in ('opened','open')";
		$leidos = $this->admDB->query($sql);
		return $leidos->row['leidos'];
	}

	public function clicks($id_envio)
	{
		$sql  = "SELECT count(id_detalle_envio)  AS clicks from detalle_envio WHERE id_envio = " . $id_envio;
		$sql .= " AND estado in ('clicked','click')";
		$clicks = $this->admDB->query($sql);
		return $clicks->row['clicks'];
	}

	public function rebotes($id_envio)
	{
		$sql  = "SELECT count(id_detalle_envio) as rebotes from detalle_envio WHERE id_envio = " . $id_envio;
		$sql .= " AND estado in ('failed','rejected', 'stored','complained', 'bounced', 'hard_bounce')";
		$rebotes = $this->admDB->query($sql);
		return $rebotes->row['rebotes'];
	}

    public function estadisticaEnvio($id_envio, $data = array() )
    {
        $sql = "SELECT * FROM envio where id_envio = " . $id_envio . " LIMIT 1";  

        $result = $this->admDB->query($sql);
        $aux  = $result->row;

        $datos = array(
            'nombre'  => $aux['nombre_envio'],
            'fecha' => $aux['cuando_enviar'],
            'volumen' => $this->volumen_envio($id_envio,''),
            'entregados' => $this->entregados($id_envio),
            'esperando' => $this->esperando($id_envio),
            'malos' => $aux['correos_malos'],
            'rebotes' => $this->rebotes($id_envio),
            'click' => $this->clicks($id_envio),
            'leidos' => $this->leidos($id_envio),
            );

        return $datos;
    }

    public function detallesPorEnvio($id_envio,$data=array())
    {
        $sql = "select E.nombre_envio as nombre,
                E.cuando_enviar as cuando_enviar,                
                D.estado as estado,
                D.id_contacto,
                E.datos_envio_programado  as detalles,
                D.destinatario as destinatario,
                E.correo_remitente as correo_remitente                 
                from detalle_envio as D inner join envio as E
                on D.id_envio = E.id_envio                
                where E.id_envio = " . $id_envio;

        if (isset($data['filter'])) {
            if ($data['filter'] == 'rebote') {
                $sql .= " AND D.estado in ('failed','rejected','stored','complained','bounced','hard_bounce')";
            }elseif ($data['filter'] == 'click') {
                $sql .= " AND D.estado in ('clicked','click')";
            }elseif ($data['filter'] == 'abierto') {
                $sql .= " AND D.estado in ('opened','open')";
            }elseif ($data['filter'] == 'entregado') {
                $sql .= " AND D.estado in ('delivered','accepted', 'sent','send')";
            }elseif ($data['filter'] == 'esperando') {
                $sql .= " AND D.estado in ('queued','en proceso')";
            }elseif ($data['filter'] == 'malo') {
                $sql .= " AND D.estado in ('malo')";
            }
        }


        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 15;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $result = $this->admDB->query($sql);
        $rows = $result->rows;

        $datos = array();
        $count = 0;
        
        if ($result->num_rows > 0) {

            $json = trim(preg_replace('/\s+/', ' ', $result->row['detalles']));

            $array = json_decode($json, true);  

            $texto = $array['message']; 

            foreach ($rows as $value) {
                $count++;            
                
                $aux = array(
                    "numero"       => $count,
                    "fecha"        => $value['cuando_enviar'],
                    "nombre_envio" => $value['nombre'],
                    "id_contacto"  => $value['id_contacto'],
                    "mensaje" => $texto,
                    "estado" => $this->traducirEstado($value['estado']),
                    "correo_remitente"        => $value['correo_remitente'],
                    "destinatario" => $value['destinatario']
                    );
                $datos[] = $aux;
            }
        }
        return $datos;
    }

    public function getMensajeDeEnvio($id_envio)
    {
        $sql = "select datos_envio_programado 
                from envio 
                where id_envio = " . $id_envio;       

        $result = $this->admDB->query($sql);

        $json = trim(preg_replace('/\s+/', ' ', $result->row['datos_envio_programado']));

        $array = json_decode($json, true);  

        $texto = $array['message']; 
        
        return $texto;
    }

    public function traducirEstado($estado)
    {
        $retorno = '';

        if($estado == 'failed' || $estado == 'rejected' || $estado ==  'stored' || $estado == 'complained' || $estado == 'bounced' || $estado == 'hard_bounce'){
            $retorno = 'Rebote';
        }elseif($estado == 'clicked' || $estado == 'click'){
            $retorno = 'Click';
        }elseif($estado == 'opened' || $estado == 'open'){
            $retorno = 'Abierto';
        }elseif($estado == 'delivered' || $estado == 'accepted' || $estado == 'sent' || $estado == 'send'){
            $retorno = 'Entregado';
        }elseif($estado == 'en proceso' || $estado == 'queued' ){
            $retorno = 'Esperando confirmar';
        }elseif($estado == 'desconocido'){
            $retorno = 'Pendiente';
        }else{
            $retorno = ucwords($estado);
        }


        return $retorno;

    }

	public function cantidadDeEnvios($id_empresa,$tipo,$mensaje)
	{
        $sql = "select count(e.id_envio) as total from envio as d  inner join envio as e on d.id_envio = e.id_envio
                and e.tipo_mensaje = '" . $mensaje . "' and e.tipo_envio = '" . $tipo . "' and e.id_empresa =". $id_empresa;
		$result = $this->admDB->query($sql);
		return $result->row['total'];
	}


    public function countRebotesPorUsuario($id_empresa){
       $sql = "SELECT count(*) as rebotes FROM detalle_envio  AS D
                INNER JOIN envio AS E
                ON D.id_envio = E.id_envio
                WHERE E.tipo_mensaje = 'MAIL'
                AND D.estado in ( 'failed', 
                'rejected','stored','complained','bounced','hard_bounce')
                AND E.id_empresa = ".$id_empresa; 

        $rebotes = $this->admDB->query($sql);
        return $rebotes->row['rebotes'];           
    }

    public function getRebotesPorUsuario($id_empresa, $data = array()){
        $sql = "SELECT * FROM detalle_envio  AS D
                INNER JOIN envio AS E
                ON D.id_envio = E.id_envio
                WHERE E.tipo_mensaje = 'MAIL'
                AND D.estado in ('failed','rejected' ,'stored' ,'complained','bounced','hard_bounce')
                AND E.id_empresa = ".$id_empresa;   

        

        if (!empty($data['filter_fecha'])) {
            $sql .= " AND E.cuando_enviar LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $sql .= " AND E.destinatario LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_remitente']) && !is_null($data['filter_remitente'])) {
            $sql .= " AND E.remitente LIKE '" . $this->db->escape($data['filter_remitente']) . "%'";
        }

        if (isset($data['filter_correo_remitente']) && !is_null($data['filter_correo_remitente'])) {
            $sql .= " AND E.correo_remitente LIKE '" . $this->db->escape($data['filter_correo_remitente']) . "%'";
        }
            

        $sort_data = array(
            'cuando_enviar',
            'destinatario',
            'remitente',
            'correo_remitente'           
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY cuando_enviar";
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
                $data['limit'] = 15;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }     
    

        $result_envio = $this->admDB->query($sql);
        $resumen = $result_envio->rows;       

        $datos = array();
        
        //crear arreglo ordenado para mostrar en el tpl
        foreach ($resumen as $envio) {
            $arrayName = array('id_envio' => $envio['id_envio'] ,
                                'fecha_envio' => $envio['cuando_enviar'],
                                'email' => $envio['destinatario'],
                                'estado' => 'Rebotado',
                                'nombre_remitente' => $envio['remitente'],
                                'correo_remitente' => $envio['correo_remitente']);
            $datos[] = $arrayName;
        }

        return $datos;           
    }


    public function getReenvio($id_envio){
        $sql = "select * from envio as E inner join detalle_envio as D
                on E.id_envio = D.id_envio
                inner join mensaje as M
                on E.id_mensaje = M.id_mensaje
                where E.id_envio = ".$id_envio;

        $result_envio = $this->admDB->query($sql);
        $resumen = $result_envio->rows;       

        $datos = array();
        
        //crear arreglo ordenado para mostrar en el tpl

        //$this->request->post['nombre_remitente'], $this->request->post['email_remitente'], $destinatario, $asunto, $message, 'connectusKey',$this->session->data['user_id'], 'ahora');
        foreach ($resumen as $envio) {
            $arrayName = array('id_envio' => $envio['id_envio'] ,
                                'id_detalle' => $envio['id_detalle_envio'],
                                'fecha_envio' => $envio['cuando_enviar'],
                                'email' => $envio['destinatario'],
                                'titulo' => $envio['titulo'],
                                'cuerpo' => $envio['cuerpo'],
                                'nombre_remitente' => $envio['remitente'],
                                'correo_remitente' => $envio['correo_remitente']);
            $datos[] = $arrayName;
        }

        return $datos;           
    }


    public function refrescar($id_empresa){

        $sql = "SELECT * FROM detalle_envio  AS D
                INNER JOIN envio AS E
                ON D.id_envio = E.id_envio
                WHERE D.estado NOT in ('clicked','click') AND E.tipo_mensaje = 'MAIL'
                AND E.id_empresa =". $this->session->data['id_empresa'];
        
        $result_envio = $this->admDB->query($sql);
        $resumen = $result_envio->rows;       

        $datos = array();
                
        $this->load->extern_library('ConnectusController');            
        $api = new ConnectusController(); 

        foreach ($resumen as $envio) {
            $response = $api->getSendMailById($envio['id_respuesta_servidor']);
            
            $arrayName = json_decode(json_encode($response), true);    

            if (sizeof($arrayName['http_response_body']['items']) != 0) {                            
                $result = $arrayName['http_response_body']['items'][0]['event'];
            }else{
                $result = "failed";
            }                                

            $this->admDB->query("update detalle_envio set estado = '". $result ."' where id_respuesta_servidor = '". $envio['id_respuesta_servidor']."'");

            $this->admDB->query("update envio set estado = 'terminado' where id_envio = '". $envio['id_envio']."'");
        }


    }

}




