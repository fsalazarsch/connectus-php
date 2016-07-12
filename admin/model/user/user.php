<?php
class ModelUserUser extends Model {
	public function addUser($data,$id_empresa) {
		/*insert connectus con grupo cliente = 10 predeterminado*/		
        $user_token = md5(uniqid(rand(), true));        
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', user_group_id = '10', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int)$data['status'] . "', date_added = NOW(), id_empresa = '". $id_empresa ."', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR'])."', token = '". $user_token ."'");
	} 

	//crea una copia del usuario insertado para el frontend asignandole el correo como nombre de usuario y sin imagen.
	public function reflejarUsuario($data, $id_empresa){	
        $user_token = md5(uniqid(rand(), true));
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['email']) . "', user_group_id = '10', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '', status = '0', date_added = NOW(), id_empresa = '". $id_empresa ."', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR'])."', token = '". $user_token ."'");

		$user_id = $this->db->getLastId();
		//reflejar en base datos administrativa
		$this->admDB->query("INSERT INTO rel_usuario SET id_empresa = " . $id_empresa . ", is_desinscrito = 0, user_id = " . $user_id);

		return $user_id;
	}

	public function nombreModoEmpresa(){
		$result = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . $this->session->data['id_empresa']);
		$empresa = $result->row;

		$nombre  = array();

		if (strlen($empresa['razon_social']) > 12)  {
			$aux = substr($empresa['razon_social'],strlen($empresa['razon_social']) * -1 , 10);
			$nombre['mostrar']  =  "Salir " . $aux . "...";
			$nombre['completo'] = $empresa['razon_social'];
		}else{
			$nombre['mostrar']  =  "Salir " . $empresa['razon_social'];
			$nombre['completo'] = $empresa['razon_social'];
		}
		return $nombre;
	}

	public function editUser($user_id, $data) {
		$sql = "UPDATE `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', user_group_id = '10', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['image']) . "',id_empresa=" . (int)$this->request->post['sel_empresa'] . ", status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'";
		
		$this->db->query($sql);

		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
	}

	public function editPassword($user_id, $password) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function editCode($email, $code) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function deleteUser($user_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
	}

	public function getUser($user_id) {
		$query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int)$user_id . "'");

		return $query->row;
	}

	public function getUserByUsername($username) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape($username) . "'");
		return $query->row;
	}

	public function getUserByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

		return $query->row;
	}

	public function traerEmpresa($id_empresa)
	{
		$sql = $this->db->query("SELECT razon_social as empresa FROM " . DB_PREFIX . "customer WHERE customer_id = " . $id_empresa . " LIMIT 1");
		$result = $sql->row;

		if ($result) {
			return $result['empresa'];
		}else{
			return "Sin Empresa";
		}		
	}

	public function getUsers($data,$id_empresa = '') {

		$sql = "SELECT * FROM " . DB_PREFIX . "user ";

		if ($id_empresa != ''){
			$sql .= " WHERE id_empresa = " . $id_empresa;
		}

		if (isset($data['filter_username'])) {
			if (empty($id_empresa)) {
				$sql .= " WHERE username like '" . $this->db->escape($data['filter_username']) . "%'";
			}else{
				$sql .= " AND username like '" . $this->db->escape($data['filter_username']) . "%'";
			}
		}

		if (isset($data['filter_empresa'])) {
			if (empty($id_empresa) && !isset($data['filter_username'])) {
				$aux = $this->getEmpresasPorNombre($this->db->escape($data['filter_empresa']));
				$sql .= " WHERE id_empresa in (" . $aux . " )" ;
			}else{
				$aux = $this->getEmpresasPorNombre($this->db->escape($data['filter_empresa']));
				$sql .= " AND id_empresa in (" . $aux . ")";
			}
		}

		$sort_data = array(
			'username',
			'empresa',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY user_id";
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

		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getEmpresasPorNombre($nombre_empresa)
	{
		$sql = "SELECT customer_id from `" . DB_PREFIX . "customer` WHERE razon_social like '" . $this->db->escape($nombre_empresa) . "%'";
		$result = $this->db->query($sql);
		$pieces = $result->rows;

		$empresas = '';
		$cant = $result->num_rows;


		for ($i=0; $i < $cant ; $i++) { 
			$empresas .= $pieces[$i]['customer_id'];
			if ($i < ($cant -1)) {
				$empresas .= ',';
			}
		}

		return $empresas;
	}

	public function validarUserById($user_id){
		$ACTIVADO = 1;
		$this->db->query("UPDATE " . DB_PREFIX ."user SET status = ". $ACTIVADO ." WHERE user_id = " . $user_id);
	}

	public function getTotalUsers($data, $id_empresa = '') {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` ";

		if ($id_empresa != ''){
			$sql .= " WHERE id_empresa = " . $id_empresa;
		}

		if (isset($data['filter_username'])) {
			if (empty($id_empresa)) {
				$sql .= " WHERE username like '" . $this->db->escape($data['filter_username']) . "%'";
			}else{
				$sql .= " AND username like '" . $this->db->escape($data['filter_username']) . "%'";
			}
		}

		if (isset($data['filter_empresa'])) {
			if (empty($id_empresa) && !isset($data['filter_username'])) {
				$aux = $this->getEmpresasPorNombre($this->db->escape($data['filter_empresa']));
				$sql .= " WHERE id_empresa in (" . $aux . " )" ;
			}else{
				$aux = $this->getEmpresasPorNombre($this->db->escape($data['filter_empresa']));
				$sql .= " AND id_empresa in (" . $aux . ")";
			}
		}

		$query = $this->db->query($sql);

		$total = isset($query->row['total']) ? $query->row['total'] : 0;

		return $total;
	}

	public function getTotalUsersByGroupId($user_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int)$user_group_id . "'");

		return $query->row['total'];
	}

	public function getTotalUsersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getEmpresaUsuario($id_usuario){
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."customer AS E LEFT JOIN ".DB_PREFIX."user  AS U ON E.customer_id = U.id_empresa WHERE U.user_id =". $id_usuario);
		
		return $query->row;
	}
}