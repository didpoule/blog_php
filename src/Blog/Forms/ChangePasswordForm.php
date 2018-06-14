<?php

namespace Blog\Forms;

use Blog\Entity\User;
use App\Services\Form;

/**
 * Class ChangePasswordForm
 * @package Blog\Forms
 */
class ChangePasswordForm extends Form {

	public function __construct() {
		self::$entity = User::class;
		self::$name   = "user";

		self::$action = "/admin/user";
		self::$fields = [
			"username"          => [
				"type"  => "text",
				"label" => "Nom d'utilisateur"
			],
			"password"          => [
				"type"  => "password",
				"label" => "Ancien Mot de passe"
			],
			"newPassword"       => [
				"type"  => "password",
				"label" => "Nouveau Mot de passe"
			],
			"newPasswordRepeat" => [
				"type"  => "password",
				"label" => "Saisir à nouveau"
			],
		];
	}
}