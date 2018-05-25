<?php

namespace App\Http\Request;

/**
 * Class Request
 * @package App\Request
 */
class Request {

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var array
	 */
	private $cookie;

	/**
	 * Request constructor.
	 */
	public function __construct() {

		session_start();

		$this->url    = $_SERVER['REQUEST_URI'];
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->cookie = $_COOKIE;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * @return array
	 */
	public function getCookie() {
		return $this->cookie;
	}
}