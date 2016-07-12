<?php
class ModelSmsEnvio extends Model{	

    public function getEnvioSMSUsuario($id_usuario, $data = array() )
    {                
        $sql = "SELECT id_envio,cuando_enviar,nombre_envio, estado, tipo_envio, datos_envio_programado FROM envio";
        $sql .= " WHERE id_empresa = " . $this->session->data['id_empresa'];

        if(!empty($data['tipo_envio'])){
            $sql .= " AND tipo_envio = '" . $data['tipo_envio'] . "'";
        }

        if (!empty($data['tipo_mensaje'])) {
            $sql .= " AND tipo_mensaje = upper('" . $data['tipo_mensaje'] . "')";
        }

        if (!empty($data['filter_nombre'])) {            
            $sql .= " AND nombre_envio LIKE '%" . $this->db->escape($data['filter_nombre']) . "%'";            
        } 



        if(!empty($data['filter_fecha']) && empty($data['filter_fecha_hasta'])){

            # Solo primera fecha
            $sql .= " AND cuando_enviar LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";

        }elseif(empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){

            # Solo segunda fecha
            $sql .= " AND cuando_enviar LIKE '%" . $this->db->escape($data['filter_fecha_hasta']) . "%'";
            
        }elseif(!empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){

            #ambas fechas
            $sql .= " AND ( DATE(cuando_enviar) BETWEEN '". $this->db->escape($data['filter_fecha']) ."' AND '". $this->db->escape($data['filter_fecha_hasta']) ."' )";
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

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
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
                                'estado' => ucwords($envio['estado']),
                                'volumen' => $this->volumenSMS($envio['id_envio']),
                                'malos' => $this->smsMalos($envio['id_envio']),
                                'error' => $this->errorEntrega($envio['id_envio']),
                                'confirmados' => $this->confirmados($envio['id_envio']),
                                'esperando' => $this->esperando($envio['id_envio']),
                                'cant_detalles' => $this->contarDetalle($envio['id_envio'])
                                );
            $datos[] = $arrayName;
        }
       
        return $datos;
    }

    public function getEnvioSMSUnico($id_usuario, $data = array() )
    {                
        $sql = "SELECT id_envio,cuando_enviar,nombre_envio, estado, tipo_envio, datos_envio_programado FROM envio";
        $sql .= " WHERE id_empresa = " . $this->session->data['id_empresa'];

        if(!empty($data['tipo_envio'])){
            $sql .= " AND tipo_envio = '" . $data['tipo_envio'] . "'";
        }

        if (!empty($data['tipo_mensaje'])) {
            $sql .= " AND tipo_mensaje = upper('" . $data['tipo_mensaje'] . "')";
        }

        if (!empty($data['filter_nombre'])) {            
            $sql .= " AND nombre_envio LIKE '%" . $this->db->escape($data['filter_nombre']) . "%'";            
        } 




        if(!empty($data['filter_fecha']) && empty($data['filter_fecha_hasta'])){

            # Solo primera fecha
            $sql .= " AND cuando_enviar LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";

        }elseif(empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){

            # Solo segunda fecha
            $sql .= " AND cuando_enviar LIKE '%" . $this->db->escape($data['filter_fecha_hasta']) . "%'";
            
        }elseif(!empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){

            #ambas fechas
            $sql .= " AND ( DATE(cuando_enviar) BETWEEN '". $this->db->escape($data['filter_fecha']) ."' AND '". $this->db->escape($data['filter_fecha_hasta']) ."' )";
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

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
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
            $mensaje = $this->getMensajeEnvioUnico($envio['id_envio']);
            $detalle = $this->traerDetalleUnico($envio['id_envio']);

            if (isset($detalle['empresa_telefono_receptor'])) {
                $telefonica = $detalle['empresa_telefono_receptor'];
            }else{
                $telefonica = 'desconocida';
            }

            if (isset($mensaje['cuerpo'])) {
            
                $texto = $mensaje['cuerpo'];
            
            }elseif (isset($envio['datos_envio_programado'])) {

                $json = trim(preg_replace('/\s+/', ' ', $envio['datos_envio_programado']));
                $array = json_decode($json, true);  
                $texto = $array['mensaje_a_enviar']; 
            }else{
                $texto = 'vacio';
            }

            if (isset($detalle['estado'])) {
                if ($detalle['estado'] =='') {
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

            $arrayName = array('id_envio' => $envio['id_envio'] ,
                                'fecha'   => $envio['cuando_enviar'],
                                'nombre'  => $envio['nombre_envio'],
                                'destino' => $destino,
                                'tipo'    => $envio['tipo_envio'],
                                'carrier' => $telefonica,
                                'mensaje' => $texto,
                                'estado'  => $this->traducirEstado($estado)
                                );
            $datos[] = $arrayName;
        }


       
        return $datos;
    }

    public function getMensajeEnvioUnico($id_envio)
    {
        $sql = "select m.cuerpo from detalle_envio as d inner join envio as e
                on d.id_envio = e.id_envio inner join mensaje as m
                on e.id_mensaje = m.id_mensaje
                where e.id_envio =" . $id_envio;

        $result = $this->admDB->query($sql);
        return $result->row;
    }

    
    public function estadisticas($id_envio)
    {
        $sql = "select distinct 
                tg.empresa_telefono_receptor as compania, 
                  ( select count(d.id_detalle_envio) from detalle_envio as d 
                    inner join envio as e on d.id_envio = e.id_envio    
                    where upper( d.estado ) in ('DELIVERED','CONFIRMED DELIVERY','SENT')
                    and d.empresa_telefono_receptor = tg.empresa_telefono_receptor and e.id_envio = $id_envio ) as confirmados,
                    
                  ( select count(id_detalle_envio) from detalle_envio as d
                    inner join envio as e on d.id_envio = e.id_envio 
                    where upper( d.estado )in ('WAITING FOR CONFIRMATION','ROUTING','INCOMMING','DEFERRED')
                    and d.empresa_telefono_receptor = tg.empresa_telefono_receptor and e.id_envio = $id_envio ) as esperando,
                    
                  (select count(id_detalle_envio) from detalle_envio as d
                    inner join envio as e on d.id_envio = e.id_envio 
                    inner join tabla_numeracion as t on d.empresa_telefono_receptor = t.compania
                    where upper( d.estado )in ( 'UNDELIVERED','UNKNOWN' )
                    and d.empresa_telefono_receptor = tg.empresa_telefono_receptor and e.id_envio = $id_envio ) as noentregados, 
                  
                  (select count(id_detalle_envio) from detalle_envio as d
                    inner join envio as e on d.id_envio = e.id_envio 
                    where d.empresa_telefono_receptor = tg.empresa_telefono_receptor and d.id_envio = $id_envio ) as volumen 
                from detalle_envio as tg inner join envio as en
                on tg.id_envio = en.id_envio and en.id_envio = $id_envio
                order by compania";
        $result = $this->admDB->query($sql);
        return $result->rows;
    }

    public function traerDetalleUnico($id_envio)
    {
        $sql = "SELECT * FROM detalle_envio WHERE id_envio = " . $id_envio . " LIMIT 1";
        $result = $this->admDB->query($sql);

        return $result->row;
    }

    public function contarDetalle($id_envio, $estado = '')
    {
        $sql = "SELECT count(id_detalle_envio) as volumen from detalle_envio WHERE id_envio = " . $id_envio;

        if (!empty($estado)) {     
            if ($estado == 'confirmados') {
                 $sql .= " AND estado in ('DELIVERED', 'CONFIRMED DELIVERY', 'SENT' )";    
            } elseif ($estado == 'esperando') {
                $sql .= " AND estado in ('WAITING FOR CONFIRMATION','ROUTING', 'INCOMMING','DEFERRED' )";  
            } elseif ($estado == 'error') {
                $sql .= " AND estado in ('UNDELIVERED', 'UNKNOWN', 'INVALID_DNS')";  
            }   
        }

        $volumen = $this->admDB->query($sql);
        $total = $volumen->row['volumen'] ;
        return $total;
    }

    public function desgloseEnvio($id_envio)
    {
        $this->load->model('sms/numeracion');
        $empresas = $this->model_sms_numeracion->get_empresas();
        /*
        $empresas[] = 'CLARO CHILE S.A.';
        $empresas[] = 'TELEFÓNICA MÓVILES CHILE S.A.';
        $empresas[] = 'NEXTEL S.A.';
        $empresas[] = 'ENTEL PCS TELECOMUNICACIONES S.A.';
        $empresas[] = 'VTR MÓVIL S.A.';
        $empresas[] = 'NETLINE TELEFÓNICA MÓVIL LTDA.';
        $empresas[] = 'SOCIEDAD FALABELLA MÓVIL SPA.';
        */
        $empresas[] = 'SIN EMPRESA';
        
        //$this->model_sms_numeracion->preparar_detalles($id_envio);
        $registros = array();

        $totales = array('confirmados' => 0,
                         'esperando' => 0,
                         'error' => 0,
                         'volumen' => 0);

        $sinEmpresa = array('confirmados' => 0,
                         'esperando' => 0,
                         'error' => 0,
                         'volumen' => 0);

       
       foreach ($empresas as $valor) {
            $total_detalles = $this->estadisticaEnvio($id_envio, $valor);
            if ($total_detalles['volumen'] > 0) {
               $registros[$valor] = $total_detalles;
            }
        }

        $sinEmpresa['confirmados'] += $this->confirmados($id_envio);
        $sinEmpresa['esperando'] += $this->esperando($id_envio);
        $sinEmpresa['error'] += $this->errorEntrega($id_envio);
        $sinEmpresa['volumen'] += $this->volumenSMS($id_envio);

        //$registros['sin empresa'] = $sinEmpresa;
        $registros['totales'] = $sinEmpresa;

        return $registros;
    }

    public function get_nombre_envio($id_envio)
    {
        $sql = "select concat(nombre_envio,' | ', cuando_enviar) as nombre from envio where id_envio = " . $id_envio;
        $result = $this->admDB->query($sql);

        return $result->row['nombre'];
    }

    public function estadisticaEnvio($id_envio,$empresa)
    {
        $sql = "SELECT nombre_envio, cuando_enviar FROM envio where id_envio = " . $id_envio . " LIMIT 1";
        $result = $this->admDB->query($sql);
        $aux  = $result->row;

        $datos = array(
            'nombre'  => $aux['nombre_envio'],
            'fecha' => $aux['cuando_enviar'],            
            'confirmados' => $this->confirmadosPorEmpresa($id_envio,$empresa),
            'esperando' => $this->esperandoConfirmarPorEmpresa($id_envio,$empresa),
            'error' => $this->errorEntregaPorEmpresa($id_envio,$empresa),
            'volumen' => $this->volumenSMSPorEmpresa($id_envio,$empresa)
            );
        return $datos;
    }

    public function getMensaje($id_mensaje)
    {
        $sql = "SELECT cuerpo as mensaje from mensaje where id_mensaje = " . $id_mensaje;
        $result = $this->admDB->query($sql);

        return $result->row['mensaje'];
    }

    public function detallesPorEnvio($id_envio, $data=array(),$estado='')
    {  
        $sql = "select E.nombre_envio as nombre,
                E.cuando_enviar as fecha,
                D.fecha as fecha_detalle,
                M.cuerpo as mensaje, 
                D.id_contacto as cont,
                D.estado as estado,
                D.destinatario as destinatario,
                D.empresa_telefono_receptor as compania
                from detalle_envio as D inner join envio as E
                on D.id_envio = E.id_envio
                inner join mensaje as M
                on E.id_mensaje = M.id_mensaje
                where E.id_envio = " . $id_envio;

        if (!empty($estado)) {     
            if ($estado == 'confirmados') {
                 $sql .= " AND D.estado in ('DELIVERED', 'CONFIRMED DELIVERY')";    
            } elseif ($estado == 'esperando') {
                $sql .= " AND D.estado in ('WAITING FOR CONFIRMATION','ROUTING', 'INCOMMING','DEFERRED', 'SENT')";  
            } elseif ($estado == 'error') {
                $sql .= " AND D.estado in ('UNDELIVERED', 'UNKNOWN', 'INVALID_DNS')";  
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

        $contador = 0;
        $datos = array();

        $this->load->model('mensaje/mensaje');
        $mensajes = $this->model_mensaje_mensaje->get_mensajes_reemplazado($id_envio);

        foreach ($rows as $value) {

            $mensajeContacto = $value['mensaje'];

            if (isset($mensajes[$contador])) {
                  $mensajeContacto = $mensajes[$contador];
            }  

            

            $aux = array(
                "numero"       => $contador+1,      
                "nombre_envio" => $value['nombre'],
                "fecha"        => (strtotime($value['fecha_detalle']) !=0)?$value['fecha_detalle']:$value['fecha'],
                //"mensaje"      => $mensajes[$contador],
                "mensaje"      => $mensajeContacto,
                "compania"     => $value['compania'],
                "estado"       => $this->traducirEstado($value['estado']),
                "destinatario" => $value['destinatario']   
                );  
            $contador++;

            $datos[] = $aux;
        }

        //quitar y descomentar
        return $datos;
/*
        if ($estado == 'confirmados') {
            $count = 0;
            $array = array();
            foreach ($datos as $value) {
                if ($value['estado'] == 'Confirmado') {
                    $count++;
                    $aux = array(
                        "numero"       => $count,
                        "fecha"        => $value['fecha'],
                        "nombre_envio" => $value['nombre_envio'],
                        "mensaje"      => $value['mensaje'],
                        "estado"       => $value['estado'],
                        "destinatario" => $value['destinatario']
                        );
                    $array[] = $aux;
                }
            }

            return $array;
            
        }elseif($estado == 'esperando'){
            $count = 0;
            $array = array();
            foreach ($datos as $value) {
                if ($value['estado'] == 'Esperando confirmación' || $value['estado'] =='Pendiente' || $value['estado'] == 'En proceso') {
                    $count++;
                    $aux = array(
                        "numero"       => $count,
                        "fecha"        => $value['fecha'],
                        "nombre_envio" => $value['nombre_envio'],
                        "mensaje" => $value['mensaje'],
                        "estado" => $value['estado'],
                        "destinatario" => $value['destinatario']
                        );
                    $array[] = $aux;
                }
            }

            return $array;

        }elseif($estado == 'error'){
            $count = 0;
            $array = array();
            foreach ($datos as $value) {
                if ($value['estado'] == 'Error de entrega') {
                    $count++;   
                    $aux = array(
                        "numero"       => $count,
                        "fecha"        => $value['fecha'],
                        "nombre_envio" => $value['nombre_envio'],
                        "mensaje" => $value['mensaje'],
                        "estado" => $value['estado'],
                        "destinatario" => $value['destinatario']
                        );
                    $array[] = $aux;
                }
            }
            return $array;

        }else{
            return $datos;
        }*/
    }

   public function decodMensaje($id_envio,$id_contacto,$destinatario)
    {
        $sql = "select id_mensaje as mensaje, id_lista as lista from envio where id_envio = " . $id_envio;
        $aux = $this->admDB->query($sql);
        $msg = $aux->row['mensaje'];
        $lista = $aux->row['lista'];


        $sql = "select cuerpo from mensaje where id_mensaje =" . $msg;
        $aux = $this->admDB->query($sql);
        $generico = $aux->row['cuerpo'];

        $sql = "select campo,glosa from campo where id_lista =" . $lista;
        $aux = $this->admDB->query($sql);
        $campos = $aux->rows;

        $seleccionar = '';
        $count = count($campos);

        $info_replace['fields']  = array();
        $info_replace['in_string'] = array();

        for ($i=0; $i < $count ; $i++) { 
            $seleccionar .= $campos[$i]['campo'] . ' as ' . $campos[$i]['glosa'];
            $info_replace['fields'] = $campos[$i]['campo'];
            $info_replace['in_string'] = $campos[$i]['glosa'];
            
            if ($i < $count - 1) {
                $seleccionar .= ',';
            }
        }


        $this->load->model('contactos/contacto');
        $rep_celular = $this->model_contactos_contacto->getContactoPorCelular($seleccionar, $destinatario, $lista );

        $rep_telefono = $this->model_contactos_contacto->getContactoPorTelefono($seleccionar, $destinatario, $lista );

        if ($rep_celular->num_rows == 1) {
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

        $personalizado = str_ireplace($campos_gen, $campos_contacto, $generico);

        return $personalizado;
    }
 
    public function traducirEstado($estado)
    {
        $retorno = '';

        if($estado == 'DELIVERED' || $estado == 'CONFIRMED DELIVERY'){
            $retorno = 'Confirmado';

        } else if($estado == 'UNDELIVERED' || $estado == 'UNKNOWN'){
            $retorno = 'No entregado';

        } else if($estado == 'WAITING FOR CONFIRMATION' || $estado =='ROUTING'|| $estado == 'INCOMMING' || $estado == 'DEFERRED' || $estado ==  'SENT' ){
            $retorno = 'Esperando confirmación';

        } else if ($estado == 'en proceso' ) {
            $retorno = 'En proceso';

        } else if ($estado == 'desconocido') {
            $retorno = 'Pendiente';

        } else if ($estado == 'terminado') {
            $retorno = 'Terminado';
            
        } else if ($estado == 'INVALID_DNS') {
            $retorno = 'DNS Inválido';

        }

        return $retorno;

    }
    
    /**Traigo los detalles por empresa telefono dependiendo del envio */
    /*public function estadisticaEnvioPorEmpresaTelefono($id_envio,$empresa_telefono)
    {
        $data = array(
            'error'      => $this->errorEntregaPorEmpresa($id_envio,$empresa_telefono),
            'esperando'  => $this->esperandoConfirmarPorEmpresa($id_envio,$empresa_telefono),
            'confirmado' => $this->confirmadosPorEmpresa($id_envio,$empresa_telefono),
            'volumen'    => $this->volumenSMSPorEmpresa($id_envio,$empresa_telefono)
            );

        return $data;
    }*/
    public function confirmadosPorEmpresa($id_envio, $empresa_telefono)
    {
        $sql = "SELECT count(id_detalle_envio) as confirmados
                from detalle_envio 
                where estado in ('DELIVERED','CONFIRMED DELIVERY')
                and id_envio = " . $id_envio ."
                and empresa_telefono_receptor = '" . $empresa_telefono . "'";

        $consulta = $this->admDB->query($sql);
        $result = $consulta->row['confirmados'];

        return $result;
    }
    public function errorEntregaPorEmpresa($id_envio, $empresa_telefono)
    {
        $sql = "SELECT count(id_detalle_envio) as errorEntrega
                from detalle_envio 
                where estado in ( 'UNDELIVERED','UNKNOWN','INVALID_DNS')
                and id_envio = " . $id_envio ."
                and empresa_telefono_receptor = '" . $empresa_telefono . "'";

        $consulta = $this->admDB->query($sql);
        $result = $consulta->row['errorEntrega'];

        return $result;
    }
    public function esperandoConfirmarPorEmpresa($id_envio, $empresa_telefono)
    {
        $sql = "SELECT count(id_detalle_envio) as esperando
                from detalle_envio 
                where estado in ('WAITING FOR CONFIRMATION','ROUTING','INCOMMING','DEFERRED','SENT')
                and id_envio = " . $id_envio ."
                and empresa_telefono_receptor = '" . $empresa_telefono . "'";

        $consulta = $this->admDB->query($sql);
        $result = $consulta->row['esperando'];

        return $result;
    }
    public function volumenSMSPorEmpresa($id_envio, $empresa_telefono)
    {
        $sql = "SELECT count(*) as total
                from detalle_envio 
                where id_envio = " . $id_envio ."
                and empresa_telefono_receptor = '" . $empresa_telefono . "'"; 

        $consulta = $this->admDB->query($sql);
        $result = $consulta->row['total'];

        return $result;
    }




    public function volumenSMS($id_envio)
    {
        $sql = "SELECT COUNT(id_detalle_envio) as totalSMS from detalle_envio where id_envio = " . $id_envio ;
        $result = $this->admDB->query($sql);
        return $result->row['totalSMS'];
    }


    public function smsMalos($id_envio)
    {
        $sql = "SELECT COUNT(*) as malos from detalle_envio where id_envio = " . $id_envio . " AND id_respuesta_servidor = -1 ";
        $result = $this->admDB->query($sql);
        return $result->row['malos'];
    }
    

    public function errorEntrega($id_envio)
    {
        $sql = "SELECT count(id_detalle_envio) as error from detalle_envio where estado in ( 'UNDELIVERED','INVALID_DNS') and id_envio = " . $id_envio;
        $result = $this->admDB->query($sql);
        return $result->row['error'];
    }

    public function confirmados($id_envio)
    {
        $sql = "SELECT count(id_detalle_envio) as confirmados from detalle_envio where estado in ('DELIVERED','CONFIRMED DELIVERY','SENT') and id_envio = " . $id_envio;
        $result = $this->admDB->query($sql);
        return $result->row['confirmados'];
    }

    public function esperando ($id_envio)
    {
        $sql = "SELECT count(id_detalle_envio) as esperando from detalle_envio where estado in ('WAITING FOR CONFIRMATION','ROUTING','INCOMMING','DEFERRED') and id_envio = " . $id_envio;
        $result = $this->admDB->query($sql);
        return $result->row['esperando'];
    }


    //Cambio 18/12/15.- Se agrega parametro $data para contar detalles segun filtro.
    public function cantidadDeEnvios($id_usuario,$tipo,$mensaje, $data = array() )
    {
        $sql = "select count(id_envio) as total from envio where id_empresa =". $this->session->data['id_empresa'];
                //and e.tipo_mensaje = '" . $mensaje . "' and e.tipo_envio = '" . $tipo . "' and e.;

        $implode = array();
        //instrucciones condicionales agregadas
        if(!empty($data['tipo_envio'])){
            $implode[] = " tipo_envio = '" . $data['tipo_envio'] . "'";
        }

        if (!empty($data['tipo_mensaje'])) {
            $implode[] = " tipo_mensaje = upper('" . $data['tipo_mensaje'] . "')";
        }

        if (!empty($data['filter_nombre'])) {             
            $implode[] = " nombre_envio LIKE '%" . $this->db->escape($data['filter_nombre']) . "%'";         
        } 



        if(!empty($data['filter_fecha']) && empty($data['filter_fecha_hasta'])){

            # Solo primera fecha
            $implode[] = " cuando_enviar LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";

        }elseif(empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){

            # Solo segunda fecha
            $implode[] = " cuando_enviar LIKE '%" . $this->db->escape($data['filter_fecha_hasta']) . "%'";
            
        }elseif(!empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){

            #ambas fechas
            $implode[] = " ( DATE(cuando_enviar) BETWEEN '". $this->db->escape($data['filter_fecha']) ."' AND '". $this->db->escape($data['filter_fecha_hasta']) ."' )";
        }



        if (count($implode) > 0) {
            $sql .= " AND " . implode(' AND ', $implode);

        }

        $result = $this->admDB->query($sql);
        return $result->row['total'];
    }




    public function SelectEnvio($id_usuario){         
        
        $sql = "SELECT D.* FROM detalle_envio  AS D
                INNER JOIN envio AS E
                ON D.id_envio = E.id_envio
                WHERE D.estado not in ('DELIVERED','CONFIRMED DELIVERY','SENT') AND E.tipo_mensaje = 'SMS'
                AND E.id_empresa = ". $this->session->data['id_empresa'];       

        $result_envio = $this->admDB->query($sql);
        $resumen = $result_envio->rows;       

        return $resumen;       
    } 

    public function updateDetalleEnvio($id_respuesta_servidor,$estado){         
        
        $sql =  "UPDATE detalle_envio SET estado = '" . $estado. "' WHERE id_respuesta_servidor = '". $id_respuesta_servidor ."'";       

        $result = $this->admDB->query($sql);      

        return $result;       
    } 

    public function refrescar($id_usuario){
        $this->load->extern_library('SmsController');            
        $api = new SmsController();


        $result = $this->SelectEnvio($id_usuario);

        foreach ($result as $key ) {
            $response = $api->getMsgStatusById($key['id_respuesta_servidor'], 'hola'); 

            $arrayName = json_decode(json_encode($response), true);              

            $this->updateDetalleEnvio($key['id_respuesta_servidor'],$arrayName['enquireMsgStatusResult']['status']); 

            $this->admDB->query("update envio set estado = 'terminado' where id_envio = '". $key['id_envio']."'");        

        }

    }

    public function deleteEnvio($id_envio) {

        $sql = "DELETE FROM envio WHERE id_envio = '" . (int)$id_envio . "' ;";
        
        $result = $this->admDB->query($sql);      

        return $result;   
    }

    public function getDatoSMS($id_envio){

        $sql = "SELECT * FROM envio WHERE id_envio = '" . (int)$id_envio . "' ;";

        $result_envio = $this->admDB->query($sql);
        $resumen = $result_envio->rows;       

        return $resumen;
    }

    public function updSMSProgramado($id_envio, $fecha)
    {

        $sql =  "UPDATE envio SET cuando_enviar = '" . $fecha. "' WHERE id_envio = ". $id_envio;       

        $result = $this->admDB->query($sql);      

        return $result;   
    }

}

