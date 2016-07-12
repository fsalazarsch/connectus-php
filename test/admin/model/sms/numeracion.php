<?php
class ModelSmsNumeracion extends Model
{
	public function check_compania($numero)
	{
		$numero_preparado = strlen($numero) > 8 ? substr($numero, strlen($numero) - 8 ) : $numero;

		$cantidad_caracteres = array(6, 5, 4);
		$compania = '';
		

		foreach ($cantidad_caracteres as $value) {
			$finder = $this->find_rango($numero_preparado, $value);
			if ($finder->num_rows == 1) {
				$compania = $finder->row['compania'];
				break;
			}
		}

		return $compania;
	}

	public function find_rango($numero, $length)
	{
		$rango = substr($numero, 0, $length);
		$sql = "SELECT compania FROM tabla_numeracion WHERE rango = ".  $rango ;
		$result = $this->admDB->query($sql);

		return $result;
	}

	public function get_empresas()
	{
		//$sql = "SELECT DISTINCT compania FROM tabla_numeracion";
		$sql = "SELECT compania FROM tabla_numeracion GROUP BY compania";
		$result = $this->admDB->query($sql);

		$empresas = $result->rows;
		$datos = array();

		foreach ($empresas as $key => $value) {
			$datos[] = $value['compania'];
		}

		return $datos;
		
	}

	public function preparar_detalles($id_envio)
	{
		if ($this->start_update($id_envio)) {
			
			$sql = "SELECT id_detalle_envio as id, id_envio, empresa_telefono_receptor, destinatario FROM detalle_envio WHERE id_envio = " . $id_envio;
			$result = $this->admDB->query($sql);
			$detalles = $result->rows;

			foreach ($detalles as $key => $value) {
				$aux = $this->check_compania($value['destinatario']);
				$compania = !empty($aux) ? $aux : 'SIN EMPRESA';

				$this->set_compania_telefonica($value['id'], $compania);
			}
		}

	}

	public function set_compania_telefonica($id_detalle_envio, $compania)
	{
		$this->admDB->query("UPDATE detalle_envio SET empresa_telefono_receptor = '" . $compania . "' WHERE id_detalle_envio = " . $id_detalle_envio );
	}

	public function start_update($id_envio)
	{
		$sql = "SELECT empresa_telefono_receptor FROM detalle_envio WHERE id_envio = " . $id_envio . " LIMIT 1";
		$result = $this->admDB->query($sql);

		return empty($result->row['empresa_telefono_receptor']) ? true : false;
	}
}