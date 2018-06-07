<?php

namespace Blog\Forms;

use Blog\Entity\User;
use Blog\Services\Form;

class UserForm extends Form {

	public function __construct($token = null) {
		self::$entity = User::class;
		self::$name = "user";

		$this->action = "/login";
		$this->fields = [
			"username"  => [
				"type"  => "text",
				"label" => "Nom d'utilisateur"
			],
			"password" => [
				"type"  => "password",
				"label" => "Mot de passe"
			],
			"token"   => [
				"type"  => "hidden",
				"value" => $token
			]
		];
	}
}