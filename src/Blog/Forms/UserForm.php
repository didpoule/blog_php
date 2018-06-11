<?php

namespace Blog\Forms;

use Blog\Entity\User;
use App\Services\Form;

class UserForm extends Form {

	public function __construct($action) {
		self::$entity = User::class;
		self::$name = "user";

		self::$action = $action;
		self::$fields = [
			"username"  => [
				"type"  => "text",
				"label" => "Nom d'utilisateur"
			],
			"password" => [
				"type"  => "password",
				"label" => "Mot de passe"
			],

		];
	}
}