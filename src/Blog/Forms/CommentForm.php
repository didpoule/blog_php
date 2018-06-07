<?php

namespace Blog\Forms;

use Blog\Entity\Comment;
use Blog\Services\Form;

class CommentForm extends Form {

	/**
	 * CommentForm constructor.
	 *
	 * @param null $postId
	 * @param null $token
	 */
	public function __construct( $postId = null, $token = null ) {
		self::$entity = Comment::class;
		self::$name = 'comment';

		$this->action = "/comment/insert";
		$this->fields = [
			"author"  => [
				"type"  => "text",
				"label" => "Pseudonyme"
			],
			"content" => [
				"type"  => "textarea",
				"label" => "message"
			],
			"post"    => [
				"type"  => "hidden",
				"value" => $postId
			],
			"token"   => [
				"type"  => "hidden",
				"value" => $token
			]
		];
	}
}