<?php

namespace Blog\Forms;

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
	public function __construct( $post = null, $action = null, $token = null ) {
		parent::__construct( $post, $action, $token );

		$this->fields = [
			"title"   => [
				"type"  => "text",
				"label" => "Titre",
				"value" => isset( $post ) ? $post->getTitle() : null,
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
			"token"   => [
				"type"  => "hidden",
				"value" => $token
			]
		];
	}
}