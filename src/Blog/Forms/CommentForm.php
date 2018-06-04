<?php

namespace Blog\Forms;

use Blog\Services\Form;

class CommentForm extends Form {

	public function __construct($postId, $token) {
		$this->action = "/comment/insert";
		$this->fields = [
			"author"  => [
				"type" => "text",
				"label" => "Pseudonyme"
			],
			"content" => [
				"type" => "textarea",
				"label" => "message"
			],
			"post" => [
				"type" => "hidden",
				"value" =>  $postId
			],
			"token" => [
				"type" => "hidden",
				"value" => $token
			]
		];
	}
}