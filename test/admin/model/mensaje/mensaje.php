<?php
class ModelMensajeMensaje extends Model
{	
	public function get_mensajes_reemplazado($id_envio)
	{
		$info_masivo = "SELECT datos_envio_programado FROM envio WHERE id_envio = " . $id_envio;
        $help = $this->admDB->query($info_masivo);
        $datos = $help->row['datos_envio_programado'];

		$datos_envio_programado = $this->get_info_masivo($datos);
		$generic_message        = $this->get_generic_message($datos_envio_programado);
		$destinatarios          = $this->get_destinatarios($datos_envio_programado);
		$field_to_replace       = $this->get_generic_field($datos_envio_programado);
		$replace_for            = $this->get_db_fields($datos_envio_programado);

		$contactos = $this->contactos_por_envio($id_envio);

		$array_field = array();
		$array_value = array();
		$mensajes_array = array();

		for ($i=0; $i < count($contactos) ; $i++) {
			foreach ($contactos[$i] as $key => $value) {
				$array_field[] = "%".$key."%";
				$array_value[] = $value;
			}
			
			$mensaje_a_separar = wordwrap(str_ireplace($array_field, $array_value, $generic_message),160,'|',true);
			$mensaje_separado = explode('|',$mensaje_a_separar);

			foreach($mensaje_separado as &$separado){			
				$mensajes_array[] = $separado;
			}

			$array_field = array();
			$array_value = array();
		}
		return $mensajes_array;
	}

	/* Destinatarios del envio masivo */
	public function get_info_masivo($datos)
	{
		$json = trim(preg_replace('/\s+/', ' ', $datos));
		$array = json_decode($json, true);

		return $array;
	}

	/* Mensaje reemplazado para un contacto especifico 
	public function get_specific_contact_info($id_contacto)
	{	
		$generic_message   = $this->get_generic_message();
		$destinatarios     = $this->get_destinatarios();
		$field_to_replace  = $this->get_generic_field();
		$replace_for       = $this->get_db_fields();


		foreach ($destinatarios as $key => $destino) {
			if ($destino['id_contacto'] == $id_contacto ) {
				echo $destino;
			}
		}

		return str_ireplace($field_to_replace, $replace_for, $generic_message);
	}*/

	public function get_destinatarios($datos)
	{
		return $datos['destinatarios']['valores'];
	}

	public function contactos_por_envio($id_envio)
	{
		$lista = $this->get_lista($id_envio);
		$campos = $this->get_campos($lista);

		$count = count($campos);
		$sql = 'select ';

        for ($i=0; $i < $count ; $i++) { 
            $sql .= $campos[$i]['campo'] . ' as ' . $campos[$i]['glosa'];

            if ($i < $count - 1) {
                $sql .= ',';
            }
        }

		$sql .= " from contacto where id_lista = " . $lista;
		$result = $this->admDB->query($sql);

		$contactos = $result->rows;

		return $contactos;
	}

	public function get_lista($envio)
	{
		$select_lista = "select distinct id_lista from envio where id_envio = ". $envio ;
		$aux = $this->admDB->query($select_lista);
		$lista = $aux->row['id_lista'];

		return $lista;
	}

	public function get_campos($id_lista)
	{
		$sql = "SELECT * FROM campo WHERE id_lista = " . $id_lista;
		$aux = $this->admDB->query($sql);
		$result = $aux->rows;

		return $result;
	}
	
	/* Strings dentro del mensaje que deben ser reemplazadas */
	public function get_generic_field($datos)
	{
		return $datos['destinatarios']['nombre_columnas'];
	}

	/* Mensaje generico del envio */
	public function get_generic_message($datos)
	{	
		return $datos['mensaje_a_enviar'];
	}

	/* Nombre de los campos en la tabla contacto*/
	public function get_db_fields($datos)
	{
		return $datos['destinatarios']['campos_de_contacto'];
	}

}
