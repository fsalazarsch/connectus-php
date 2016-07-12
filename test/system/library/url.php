<?php
class Url {
	private $domain;
	private $ssl;
	private $rewrite = array();

	public function __construct($domain, $ssl = '') {
		$this->domain = $domain;
		$this->ssl = $ssl;
	}

	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = false) {
		if (!$secure) {
			$url = $this->domain;
		} else {
			$url = $this->ssl;
		}

		$url .= 'index.php?route=' . $route;

		if ($args) {
			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url;
	}

	public function link_validar($route, $args = '', $secure = false) {
        if (!$secure) {
            $url = $this->domain;
        } else {
            $url = $this->ssl;
        }

        $url .= 'index.php?route=' . $route . '&'.$args;

        return $url;
    }

    public function link_2($route, $args = '', $secure = false) {
		if (!$secure) {
			$url = $this->domain;
		} else {
			$url = $this->ssl;
		}

		$url .= 'admin/index.php?route=' . $route;

		if ($args) {
			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url;
	}

	public function frontend($route, $args = '', $secure = false) 
	{
		if (!$secure) {
			$url = $this->domain;
		} else {
			$url = $this->ssl;
		}

		$url = substr($url, 0, strlen($url) - 6 );
		$url .= 'index_oc.php?route=' . $route;

		if ($args) {
			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url;
	}
}
