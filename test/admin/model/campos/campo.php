<?php
class ModelCamposCampo extends Model { 
	public function addCampo($nombre_tipo, $glosa, $nombre_campo, $id_lista, $posicion, $glosa_campo){
		$this->load->model('campos/tipo_campo');
		$finder = $this->model_campos_tipo_campo->getTipoCampoPorNombre($nombre_tipo);

		if($finder){
			$id_tipo = $finder['id_tipo_campo'];
		}else{
			$id_tipo = $this->model_campos_tipo_campo->addTipoCampo($nombre_tipo,$glosa);
		}

		$glosa_campo = str_replace(' ', '_', $glosa_campo);

		$this->admDB->query("INSERT INTO campo SET campo='". $nombre_campo ."', id_lista=".$id_lista . ", id_tipo_campo = ".$id_tipo . ",glosa='". ucfirst(strtolower($glosa_campo)) ."', posicion=".$posicion);
		return $this->admDB->getLastId();
	}

	public function getCamposPorLista($id_lista){
		$result = $this->admDB->query("SELECT * FROM campo WHERE id_lista = ".$id_lista);
		return $result->rows;
	}
}  
