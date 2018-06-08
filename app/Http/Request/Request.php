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
	 * @var array
	 */
	private $post;

	/**
	 * Request constructor.
	 */
	public function __construct() {

		session_start();

		$this->url    = $_SERVER['REQUEST_URI'];
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->cookie = $_COOKIE;
		$this->post   = $_POST;
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
	 * @return mixed
	 */
	public function getCookie( $name ) {
		return isset( $this->cookie[ $name ] ) ? $this->cookie[ $name ] : false;
	}

	/**
	 * Définit un cookie
	 *
	 * @param $name
	 * @param $value
	 * @param $expire int N jours
	 */
	public function setCookie( $name, $value, $expire, $path = "/" ) {
		if ( isset( $this->cookie[ $name ] ) ) {
			unset( $this->cookie[ $name ] );
		}
		setcookie( $name, htmlspecialchars( $value ), time() + ( $expire * 86400 ), $path, null, false, true );
	}

	/**
	 * Définit une variable post
	 *
	 * @param $name
	 * @param bool $value
	 */
	public function setPost( $name, $value = true ) {
		$this->post[ $name ] = $value;
	}

	/**
	 * @param $name
	 *
	 * @return bool|mixed
	 */
	public function getPost( $name = null ) {
		if ( is_null( $name ) ) {
			return $this->post;
		}

		return isset( $this->post[ $name ] ) ? $this->post[ $name ] : false;
	}

	public function getToken() {
		if ( ! isset( $_SESSION['token'] ) ) {
			$_SESSION['token'] = uniqid();
		}

		return $_SESSION['token'];
	}

	public function setToken() {
		$_SESSION['token'] = uniqid();
	}

}