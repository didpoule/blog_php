<?php

namespace Blog\Forms;

use Blog\Entity\Post;
use Blog\Services\Form;

/**
 * Class PostForm
 * @package Blog\Forms
 */
class PostForm extends Form {
	public function __construct( $post = null, $action = null, $token = null ) {
		self::$entity = Post::class;
		self::$name   = 'post';

		$this->action = $action;
		$this->fields = [
			"content" => [
				"type"  => "textarea",
				"label" => "Contenu",
				"value" => isset( $post ) ? $post->getContent() : null
			],
			"token"   => [
				"type"  => "hidden",
				"value" => $token
			]
		];
	}
}