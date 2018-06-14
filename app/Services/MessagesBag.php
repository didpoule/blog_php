<?php

namespace App\Services;


/**
 * Class MessagesBag
 * @package App\Services
 */
class MessagesBag {

	/**
	 * @var array
	 */
	private $messages = [];

	/**
	 * MessagesBag constructor.
	 */
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

	/**
	 * Ajoute un message au conteneur
	 *
	 * @param $message
	 * @param string $type
	 */
	public function addMessage( $message, $type = "info" ) {
		$_SESSION['messages'][] = [
			"type"    => $type,
			"message" => $message
		];
	}

	/**
	 * Dépile un message
	 *
	 * @return mixed
	 */
	public function getMessage() {
		array_shift( $this->messages );

		return array_shift( $_SESSION['messages'] );
	}

	/**
	 * @return array
	 */
	public function getMessages() {
		return $this->messages;
	}
}
