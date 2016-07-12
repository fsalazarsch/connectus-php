<?php
class Session {
	public $data = array();

	public function __construct() {
		if (!session_id()) {
			ini_set('session.use_only_cookies', 'On');
			ini_set('session.use_trans_sid', 'Off');
			ini_set('session.cookie_httponly', 'On');

			/* Q-mod -- session extend */
            session_set_cookie_params(86400, '/');
            ini_set('session.cache_expire', '240');
            ini_set('session.gc_maxlifetime', '86400');
            //ini_set('session.save_path','/sessions');

			session_set_cookie_params(0, '/');
			session_start();
		}

		$this->data =& $_SESSION;
	}

	public function getId() {
		return session_id();
	}

	public function destroy() {
		return session_destroy();
	}
}