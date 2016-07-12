<?php
class ModelCamposTipoCampo extends Model {
	public function addTipoCampo($nombre_tipo, $glosa=''){
		$this->admDB->query("INSERT INTO tipo_campo SET nombre_tipo='". $nombre_tipo ."',glosa='".$glosa."'");	
		return $this->admDB->getLastId();
	}

	public function getTipoCampoPorNombre($nombre){		
		$result = $this->admDB->query("SELECT * FROM tipo_campo WHERE nombre_tipo='".$nombre."'");
		if($result->row){
			return $result->row;
		}else{
			return false;
		}
	}

	public function getTipos(){
		$result = $this->admDB->query("SELECT * FROM tipo_campo");
		return $result->rows;
	}
}
