<?php
class ModelMailingDesinscritos extends Model
{	
	public function desinscribir($id_contacto, $id_lista, $id_envio)
	{
		$this->load->model('contactos/listas');
		$lista = $this->model_contactos_listas->getListaPorID($id_lista);

        $consulta_envio = "SELECT correo_remitente as remitente, nombre_envio as asunto FROM envio WHERE id_envio = " . $id_envio;
        $aux_correo = $this->admDB->query($consulta_envio);
        $email = $aux_correo->row['remitente'];
        $asunto = $aux_correo->row['asunto'];

        if ($this->validateDesinscripcion($id_contacto)) {
            $sql = "INSERT INTO desinscrito SET id_contacto = " . $id_contacto . ", campania_desinscrito = '" . $asunto . "', fecha = NOW(), correo_remitente='" . $email . "'";

            $query = "UPDATE contacto SET desinscrito = 1 WHERE id_contacto = " . $id_contacto;

            $d = $this->admDB->query($sql);
            $c = $this->admDB->query($query);

            $cant = $d->num_rows;

            if ($cant) {
               return $this->admDB->getLastId();
            }else{
                return null;
            }
        }
	}

	/*actualmente este metodo no se esta usando, el que esta connectado se encuentra bajo el mismo nombre en contacto_model_listas*/
	public function traerDesinscritos($id_empresa, $data=array())
    {
        $sql = "select c.id_contacto,c.email, d.campania_desinscrito, d.correo_remitente as remitente, d.fecha, l.id_usuario
                from contacto as c inner join desinscrito as d
                on c.id_contacto = d.id_contacto
                inner join lista_contacto as l
                on c.id_lista = l.id_lista
                and l.id_empresa = " . $id_empresa ."
                where c.desinscrito = 1";

        if (!empty($data['filter_fecha'])) {
        	//nombre del contacto
            $sql .= " AND d.fecha LIKE '" . $this->db->escape($data['filter_fecha']) . "%'";
        } 

        if (!empty($data['filter_remitente'])) {
        	//email del contacto
            $sql .= " AND d.correo_remitente LIKE '" . $this->db->escape($data['filter_remitente']) . "%'";
        } 

        if (!empty($data['filter_campania'])) {
        	//nombre de la lista de la que se desinscribió
            $sql .= " AND d.campania_desinscrito LIKE '" . $this->db->escape($data['filter_campania']) . "%'";
        }  

        if (!empty($data['filter_destino'])) {
        	//correo del usuario que hizo el envio del cual se desinscribio
            $sql .= " AND c.email LIKE '" . $this->db->escape($data['filter_destino']) . "%'";
        }     

        $sort_data = array(
            'd.fecha',
            'd.campania_desinscrito',
            'c.email',
            'd.correo_remitente'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY d.id_desinscrito";
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
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }                        

        $result = $this->admDB->query($sql);

        $resumen = $result->rows;       

        $datos = array();
        
        //crear arreglo ordenado para mostrar en el tpl        
        foreach ($resumen as $des) {
            $arrayName = array( 'id_contacto' => $des['id_contacto'],
                                'email'       => $des['email'], 
                                'fecha'       => $des['fecha'],  
                                'remitente'   => $des['remitente'],                              
                                'campania_desinscrito' => $des['campania_desinscrito']);
            $datos[] = $arrayName;
        }
       
        return $datos;
    }

    public function cantidad_desinscritos($id_empresa, $data){
       
        $sql = "select count(id_desinscrito) as cantidad
                from contacto as c inner join desinscrito as d
                on c.id_contacto = d.id_contacto
                inner join lista_contacto as l
                on c.id_lista = l.id_lista
                and l.id_empresa = " . $id_empresa ."
                where c.desinscrito = 1";

        if (!empty($data['filter_fecha'])) {
            //nombre del contacto
            $sql .= " AND d.fecha LIKE '" . $this->db->escape($data['filter_fecha']) . "%'";
        } 

        if (!empty($data['filter_remitente'])) {
            //email del contacto
            $sql .= " AND d.correo_remitente LIKE '" . $this->db->escape($data['filter_remitente']) . "%'";
        } 

        if (!empty($data['filter_campania'])) {
            //nombre de la lista de la que se desinscribió
            $sql .= " AND d.campania_desinscrito LIKE '" . $this->db->escape($data['filter_campania']) . "%'";
        }  

        if (!empty($data['filter_destino'])) {
            //correo del usuario que hizo el envio del cual se desinscribio
            $sql .= " AND c.email LIKE '" . $this->db->escape($data['filter_destino']) . "%'";
        }      

        $result = $this->admDB->query($sql);
        return $result->row['cantidad'];
    }

    public function validateDesinscripcion($id_contacto)
    {
        $sql = "SELECT * FROM desinscrito WHERE id_contacto = " . $id_contacto;
        $result = $this->admDB->query($sql);
        if ($result->num_rows == 0) {
            return true;
        }else{
            return false;
        }
    }

	public function obtenerCorreoRemitente($id_usuario){
		$result = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "user WHERE user_id = " . $id_usuario);
		$correo = $result->row['email'];
		return $correo;
	}
}