<?php

namespace App\Services;

use App\Http\Request\Request;

/**
 * Class MessagesBag
 * @package App\Services
 */
class MessagesBag {

	/**
	 * @var array
	 */
	private $messages = [];

	public function __construct() {

		if ( empty( $_SESSION['messages'] ) ) {
			$_SESSION['messages'] = [];
		}
		$this->messages = $_SESSION['messages'];
	}

	/**
	 * @return array
	 */
	public function getBag() {
		return $_SESSION['messages'];
	}

	public function addMessage( $message, $type = "info" ) {
		$_SESSION['messages'][] = [
			"type"    => $type,
			"message" => $message
		];
	}

	/**
	 * @return mixed
	 */
	public function getMessage() {
		array_shift( $this->messages );

		return array_shift( $_SESSION['messages'] );
	}

	public function getMessages() {
		return $this->messages;
	}
}
