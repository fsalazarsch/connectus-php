<?php

	class ModelSmsReportes extends Model
	{
		public $id;
		public $ruta;
		public $nombre_archivo;

		public function setRuta($ruta)
		{
			$this->ruta = $ruta;
		}

		public function get_empresa(){
			return $this->customer->getId();
			}

		public function getReportes($data = array())
		{
			$sql = "SELECT * 
					FROM archivos ";
	        if($this->session->data['user_id'] != 1)
	        $sql .= " WHERE tipo = ".get_empresa();

	        

	        if (!empty($data['filter_name'])) {            
	            $sql .= " AND nombre LIKE '%" . $this->db->escape($data['filter_name']) . "%'";            
	        } 



	        if(!empty($data['filter_fecha']) && empty($data['filter_fecha_hasta'])){
	            # Solo primera fecha
	            $sql .= " AND creacion LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";

	        }elseif(empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){
	            # Solo segunda fecha
	            $sql .= " AND creacion LIKE '%" . $this->db->escape($data['filter_fecha_hasta']) . "%'";
	            
	        }elseif(!empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){
	            #ambas fechas
	            $sql .= " AND ( DATE(creacion) BETWEEN '". $this->db->escape($data['filter_fecha']) ."' AND '". $this->db->escape($data['filter_fecha_hasta']) ."' )";
	        }


	 
	        $sort_data = array(
	                    'creacion',
	                    'nombre',         
	                );

	        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
	            $sql .= " ORDER BY " . $data['sort'];
	        } else {
	            $sql .= " ORDER BY creacion";
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
	        $archivos = $result_envio->rows; 

	        $datos = array();
	        
	        //crear arreglo ordenado para mostrar en el tpl
	        foreach ($archivos as $arhivo) {

	            $arrayName = array('id_archivo'		=> $arhivo['id_archivo'] ,
	                                'nombre' 		=> $arhivo['nombre'],
	                                'tipo' 			=> $arhivo['tipo'],
	                                'tipo_archivo' 	=> $arhivo['tipo_archivo'],
	                                'ruta' 			=> $arhivo['ruta'],
	                                'creacion' 		=> $arhivo['creacion']
	                                );

	            $datos[] = $arrayName;
	        }
	       
	        return $datos;
		}

		public function getCountListaArchivos($data)
		{
			

	            $sql = "SELECT count(*) as total FROM archivos ";
	            if($this->session->data['user_id'] != 1)
					$sql .= " WHERE tipo = ".get_empresa();

	            if (!empty($data['filter_name'])) { 
	                $sql .= " AND nombre LIKE '%" . $this->db->escape($data['filter_name']) . "%'";                                                                  
	            }


	            if(!empty($data['filter_fecha']) && empty($data['filter_fecha_hasta'])){

	                # Solo primera fecha
	                $sql .= " AND creacion LIKE '%" . $this->db->escape($data['filter_fecha']) . "%'";

	            }elseif(empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){

	                # Solo segunda fecha
	                $sql .= " AND creacion LIKE '%" . $this->db->escape($data['filter_fecha_hasta']) . "%'";
	                
	            }elseif(!empty($data['filter_fecha']) && !empty($data['filter_fecha_hasta'])){

	                #ambas fechas
	                $sql .= " AND ( DATE(creacion) BETWEEN '". $this->db->escape($data['filter_fecha']) ."' AND '". $this->db->escape($data['filter_fecha_hasta']) ."' )";
	            }

	            $result = $this->admDB->query($sql);

	    	    return isset($result->row['total']) ? $result->row['total'] : 0;  
	        
		}

		public function deleteReporte($id)
		{
			$this->id = $id;
			$this->getDatos();

			$sql = "DELETE FROM archivos WHERE id_archivo = ".$this->id;

	        $this->admDB->query($sql);

	        $this->deleteArchivo();

	        return true;
		}

		private function getDatos()
		{
			$sql = "SELECT * FROM archivos WHERE id_archivo =".$this->id;
			//envios del usuario consultado
	        $result = $this->admDB->query($sql);
	        $reporte = $result->row;

	        $this->nombre_archivo = $reporte['ruta'];
	        $this->nombre = $reporte['nombre'];
		}

		private function deleteArchivo()
		{
			$archivo = $this->ruta.$this->nombre_archivo;
			if(file_exists($archivo)){
				unlink($archivo);
			}
		}
	}
