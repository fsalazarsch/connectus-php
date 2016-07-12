<?php

    class ModelMailingEnvioProgramado extends Model{

        public function getEnvioPorUsuarioProgramado($id_empresa, $data = array() )
        {
            $sql =  "SELECT id_envio,cuando_enviar,nombre_envio, estado,tipo_envio, correos_malos FROM envio";
            $sql .= " WHERE id_empresa = " . $id_empresa;

            $sql .= " AND estado = 'pendiente' ";

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
                                    'volumen' => $this->volumenEmail($envio['id_envio'])
                                    );



                $datos[] = $arrayName;
            }

            return $datos;
        }

        public function cantidadDeEnviosProgramado($id_usuario,$tipo,$mensaje, $data = array() )
        {
            $sql = "select count(id_envio) as total from envio where id_empresa =". $this->session->data['id_empresa'];
            $sql .= " AND estado='pendiente' ";

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

            if (!empty($data['filter_fecha'])) {
                $implode[] = " cuando_enviar LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";
            }

            if (count($implode) > 0) {
                $sql .= " AND " . implode(' AND ', $implode);

            }

            $result = $this->admDB->query($sql);
            return $result->row['total'];
        }



        public function getEnvioUnicoPorUsuarioProgramado($id_empresa, $data = array() )
        {
            $sql = "SELECT id_envio,cuando_enviar,nombre_envio, estado,tipo_envio, correos_malos, correo_remitente, remitente , datos_envio_programado FROM envio";
            $sql .= " WHERE id_empresa = " . $id_empresa;
            $sql .= " AND estado='pendiente' ";

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

                    if(isset($array['message'])){
                        $texto = $array['message']; 
                    }else{
                        $texto = 'vacio';
                    }
                    
                
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


                 // obtengo el ASUNTO
                $datos_envio = json_decode($envio['datos_envio_programado']);

                if(!empty($datos_envio->asunto)){
                    $asunto = $datos_envio->asunto;
                }else{
                    $asunto = '';
                }



                $arrayName = array(  'numero'       => $count,
                                    'id_envio'      => $envio['id_envio'] ,
                                    'fecha'        => $envio['cuando_enviar'],
                                    'nombre'       => $envio['nombre_envio'],
                                    'asunto'       => $asunto,
                                    'remitente'    => $envio['correo_remitente'],
                                    'tipo'         => $envio['tipo_envio'],                                
                                    'mensaje'      => html_entity_decode(html_entity_decode($texto)),
                                    'estado'       => $this->traducirEstado($estado),
                                    'leidos'        => $this->leidos($envio['id_envio']),
                                    'click'         =>  $this->clicks($envio['id_envio']),
                                    'spam'          => $this->spam($envio['id_envio']),
                                    'destinatario' => $destino
                                    );

                $datos[] = $arrayName;
            }
           
            return $datos;
        }  


        public function getDetalleUnico($id_envio)
        {
            $sql = "SELECT * FROM envio WHERE id_envio = " . $id_envio . " LIMIT 1";
            $result = $this->admDB->query($sql);

            return $result->row;
        } 
       

        public function volumenEmail($id_envio)
        {
            $sql = "SELECT datos_envio_programado as datosJson from envio where id_envio = " . $id_envio ;
            $result = $this->admDB->query($sql);

            $data = json_decode($result->row['datosJson']);

            $num = count($data->destinatarios->valores);

            return $num;
        }


        public function traducirEstado($estado)
        {
            $retorno = '';

            if($estado == 'failed' || $estado == 'rejected' ||  $estado == 'reject' || $estado ==  'stored' || $estado == 'complained' || $estado == 'bounced' || $estado == 'hard_bounce' || $estado == 'soft_bounce'){
                

                $retorno = 'Rebote';
            }elseif($estado == 'clicked' || $estado == 'click'){
                $retorno = 'Click';
            }elseif($estado == 'opened' || $estado == 'open'){
                $retorno = 'Abierto';
            }elseif($estado == 'delivered' || $estado == 'accepted' || $estado == 'sent' || $estado == 'send'){
                $retorno = 'Entregado';
            }elseif($estado == 'en proceso' || $estado == 'queued' || $estado == 'deferral'){
                $retorno = 'Esperando confirmar';
            }elseif($estado == 'desconocido'){
                $retorno = 'Pendiente';
            }elseif($estado == 'unsub'){
                $retorno = 'Desinscrito';

            }else{
                $retorno = ucwords($estado);
            }


            return $retorno;

        }

        public function getMensaje($id_envio)
        {
            $sql = "select m.* from detalle_envio as d inner join envio as e
                    on d.id_envio = e.id_envio inner join mensaje as m
                    on e.id_mensaje = m.id_mensaje
                    where e.id_envio =" . $id_envio;

            $result = $this->admDB->query($sql);
            return $result->row;


        }

        public function leidos($id_envio)
        {

            $sql  = "SELECT sum(estado_open)  AS leidos from detalle_envio WHERE id_envio = " . $id_envio;
            $clicks = $this->admDB->query($sql);
            return $clicks->row['leidos'];

        }

        public function clicks($id_envio)
        {

            $sql  = "SELECT sum(estado_click)  AS clicks from detalle_envio WHERE id_envio = " . $id_envio;
            $clicks = $this->admDB->query($sql);
            return $clicks->row['clicks'];


        }

        public function spam($id_envio)
        {
            /**
             * obtenemos la cantidad de mails considerados Spam
             */

            $sql  = "SELECT sum(estado_spam)  AS spam from detalle_envio WHERE id_envio = " . $id_envio;
            $clicks = $this->admDB->query($sql);
            return $clicks->row['spam'];

        }

    }


?>