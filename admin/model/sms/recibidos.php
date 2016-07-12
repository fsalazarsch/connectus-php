<?php

 class ModelSmsRecibidos extends Model
 { 	
 	public function getRecibidos($data = array()){
 		
        $sql = 'SELECT l.destinatario  
                FROM envio as c  
                INNER JOIN detalle_envio as l on c.id_envio = l.id_envio 
                where c.tipo_mensaje = "SMS" AND c.id_empresa = '. $this->session->data['id_empresa']
                .' group by l.destinatario ';

        $result = $this->admDB->query($sql); 

        if ( count($result->rows) > 0){
            foreach ($result->rows as $value) {
                $remitentes[] = $value['destinatario'];
            }

            $remitentes = implode(',', $remitentes);


            $sql = "SELECT * FROM sms_recibido WHERE remitente in ($remitentes) "; 

            if (!empty($data['filter_name'])) { 
                $sql .= " AND remitente LIKE '%" . $this->db->escape($data['filter_name']) . "%'";                                                                  
            }    




            if(!empty($data['filter_fecha']) && empty($data['filter_fecha_hasta'])){

                # Solo primera fecha
                $sql .= " AND fecha LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";

            }elseif(empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){

                # Solo segunda fecha
                $sql .= " AND fecha LIKE '%" . $this->db->escape($data['filter_fecha_hasta']) . "%'";
                
            }elseif(!empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){

                #ambas fechas
                $sql .= " AND ( DATE(fecha) BETWEEN '". $this->db->escape($data['filter_fecha']) ."' AND '". $this->db->escape($data['filter_fecha_hasta']) ."' )";
            }



            $sort_data = array(
                'remitente',
                'fecha'            
            );        

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY s.id_recibido";
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
            
            #echo $sql; return false;

           $result = $this->admDB->query($sql); 
            return $result->rows; 

        } else {
            return [0];
        }

            
 	}


 	public function getCountSmsRecibidos($data){
        
        $sql = 'SELECT l.destinatario  
                FROM envio as c  
                INNER JOIN detalle_envio as l on c.id_envio = l.id_envio 
                where c.tipo_mensaje = "SMS" AND c.id_empresa = '. $this->session->data['id_empresa']
                .' group by l.destinatario ';

        $result = $this->admDB->query($sql); 

        if( count($result->rows) > 0 ){

            foreach ($result->rows as $value) {
                $remitentes[] = $value['destinatario'];
            }

            $remitentes = implode(',', $remitentes);


            $sql = "SELECT count(*) as total FROM sms_recibido WHERE remitente in ($remitentes) "; 

            if (!empty($data['filter_name'])) { 
                $sql .= " AND s.remitente LIKE '%" . $this->db->escape($data['filter_name']) . "%'";                                                                  
            }

            if(!empty($data['filter_fecha']) && empty($data['filter_fecha_hasta'])){

                # Solo primera fecha
                $sql .= " AND fecha LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";

            }elseif(empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){

                # Solo segunda fecha
                $sql .= " AND fecha LIKE '%" . $this->db->escape($data['filter_fecha_hasta']) . "%'";
                
            }elseif(!empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){

                #ambas fechas
                $sql .= " AND ( DATE(fecha) BETWEEN '". $this->db->escape($data['filter_fecha']) ."' AND '". $this->db->escape($data['filter_fecha_hasta']) ."' )";
            }


            $result = $this->admDB->query($sql);

            return isset($result->row['total']) ? $result->row['total'] : 0; 
        }else{
            return 0;
        }

             
	}

 } 
