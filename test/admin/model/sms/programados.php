<?php

 class ModelSmsProgramados extends Model
 { 	

    public function getSMSProgramados($id_usuario, $data = array()){


        $sql = 'SELECT * FROM envio 
                WHERE   id_usuario = '.$id_usuario." 
                    AND estado = 'pendiente' 
                    AND tipo_mensaje = 'SMS' ";

        if(!empty($data['tipo_envio'])){
            $sql .= " AND tipo_envio = '" . $data['tipo_envio'] . "'";
        }    

        if (!empty($data['filter_name'])) { 
            $sql .= " AND remitente LIKE '%" . $this->db->escape($data['filter_name']) . "%'";                                                                  
        }    

        if (!empty($data['filter_fecha'])) {
            $sql .= " AND fecha LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";
        }  

        //$sql .= "   AND cuando_enviar BETWEEN cuando_enviar AND fecha ";              

        $sort_data = array(
            'remitente',
            'cuando_enviar'            
        );        

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY id_envio";
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
        
        //echo $sql;

       $result = $this->admDB->query($sql); 
       $resumen = $result->rows; 

        $datos = array();

        if(count($resumen)>0){
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
                                    'volumen' => $this->volumenSMS($envio['id_envio'])
                                    );
                $datos[] = $arrayName;
            }

            return $datos;

        }else{

            return false;
        }
        
            
       
        
    }


    public function volumenSMS($id_envio)
    {
        $sql = "SELECT datos_envio_programado as datosJson from envio where id_envio = " . $id_envio ;
        $result = $this->admDB->query($sql);

        $data = json_decode($result->row['datosJson']);

        $num = count($data->destinatarios->valores);

        return $num;
    }

 	public function cantidadDeEnvios($id_usuario,$tipo,$mensaje, $data = array() )
    {
        $sql = "SELECT count(id_envio) as total FROM envio 
                WHERE id_usuario =". $id_usuario."
                    AND estado = 'pendiente' 
                    AND tipo_mensaje = 'SMS' ";

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

        if (!empty($data['filter_fecha'])) {
            $implode[] = " cuando_enviar LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";
        }

        if (count($implode) > 0) {
            $sql .= " AND " . implode(' AND ', $implode);

        }

        $result = $this->admDB->query($sql);
        return $result->row['total'];
    }

    public function getSMSUnicoProgramado($id_usuario, $data = array() )
    {                
        $sql = 'SELECT id_envio,cuando_enviar,nombre_envio, estado, tipo_envio, datos_envio_programado 
                FROM envio 
                WHERE   id_usuario = '. $this->session->data['user_id']." 
                    AND estado = 'pendiente' 
                    AND tipo_mensaje = 'SMS' "; 

        if(!empty($data['tipo_envio'])){
            $sql .= " AND tipo_envio = '" . $data['tipo_envio'] . "'";
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

            

            if (isset($mensaje['cuerpo'])) {
            
                $texto = $mensaje['cuerpo'];
            
            }elseif (isset($envio['datos_envio_programado'])) {

                $json = trim(preg_replace('/\s+/', ' ', $envio['datos_envio_programado']));
                $array = json_decode($json, true);  
                $texto = $array['mensaje_a_enviar']; 
            }else{
                $texto = 'vacio';
            }

            

            if (isset($envio['datos_envio_programado'])) {
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
                                'mensaje' => $texto,
                                'estado'  => 'Pendiente'
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

 } 
