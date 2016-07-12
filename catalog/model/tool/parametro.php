<?php
class ModelToolParametro extends Model
{
	public function get_equivalencia($parametro)
	{
		$query = $this->admDB->query("SELECT valor1 AS equivalencia FROM parametro WHERE id_parametro = '" . $this->admDB->escape($parametro) . "'");

		return $query->rows;
	}

	public function set_parametro($new_valor1, $parametro)
	{
		$query = $this->admDB->query("UPDATE parametro SET valor1 = '" . $this->admDB->escape($new_valor1) . "' WHERE id_parametro = '" . $this->admDB->escape($parametro) . "'");

		return $query->rows;
	}
}