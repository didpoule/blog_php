<?php

namespace Blog\Forms;

use Blog\Entity\Post;
use App\Services\Form;

/**
 * Class PostForm
 * @package Blog\Forms
 */
class PostForm extends Form {
	public function __construct( $post = null, $action = null) {
		self::$name   = 'post';
		self::$action = $action;
		self::$entity = Post::class;

		self::$fields = [
			"content" => [
				"type"  => "textarea",
				"value" => isset( $post ) ? $post->getContent() : null
			]
		];
	}
}