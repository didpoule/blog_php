<?php

namespace Blog\Forms;

use Blog\Entity\Post;

/**
 * Class ChapterForm
 * @package Blog\Forms
 */
class ChapterForm extends PostForm {

	/**
	 * ChapterForm constructor.
	 *
	 * @param null $post
	 * @param null $action
	 * @param null $token
	 */
	public function __construct( $post = null, $action = null) {

		self::$name = 'chapter';
		self::$action = $action;
		self::$entity = Post::class;

		self::$fields = [
			"title"   => [
				"type"  => "text",
				"label" => "Titre",
				"value" => isset( $post ) ? $post->getTitle() : null,
			],
			"published" => [
				"type"	=> "boolean",
				"label" => "Publier",
				"value" => isset( $post ) ? $post->getPublished() : null
			],
			"number"  => [
				"type"  => "number",
				"label" => "Numéro",
				"value" => isset( $post ) ? $post->getNumber() : null
			],
			"content" => [
				"type"  => "textarea",
				"label" => "Contenu",
				"value" => isset( $post ) ? $post->getContent() : null
			],
		];
	}
}