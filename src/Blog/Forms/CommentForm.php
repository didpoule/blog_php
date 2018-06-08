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
	public function __construct( $comment = null, $post = null, $token = null ) {
		self::$entity = Comment::class;
		self::$name = 'comment';

		$this->action = "/chapitres/chapitre-" . (isset($post) ? $post->getNumber() : null);
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
				"value" =>  isset($post) ? $post->getId() : null
			],
			"token"   => [
				"type"  => "hidden",
				"value" => $token
			]
		];
	}
}