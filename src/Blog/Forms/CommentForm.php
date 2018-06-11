<?php

namespace Blog\Forms;

use Blog\Entity\Comment;
use Blog\Services\Form;

/**
 * Class CommentForm
 * @package Blog\Forms
 */
class CommentForm extends Form {

	/**
	 * CommentForm constructor.
	 *
	 * @param null $comment
	 * @param null $post
	 * @param null $token
	 * @param bool $admin
	 * @param string $action
	 */
	public function __construct( $comment = null, $post = null, $token = null, $admin = false, $action = null ) {
		self::$entity = Comment::class;
		self::$name   = 'comment';

		if ( $action === null ) {
			$this->action = "/chapitres/chapitre-" . ( isset( $post ) ? $post->getNumber() : null );
		} else {
			$this->action = $action;
		}
		$this->fields = [
			"author"  => [
				"type"  => "text",
				"label" => "Pseudonyme",
				"value" => isset( $comment ) ? $comment->getAuthor() : null
			],
			"content" => [
				"type"  => "textarea",
				"label" => "message",
				"value" => isset( $comment ) ? $comment->getContent() : null
			],
			"post"    => [
				"type"  => "hidden",
				"value" => isset( $post ) ? (!is_int($post) ? $post->getId() : $post) : null
			],
			"token"   => [
				"type"  => "hidden",
				"value" => $token
			]
		];

		if ( $admin ) {
			$this->fields = [
				                "published" => [
					                "type"  => "boolean",
					                "label" => "Publié",
					                "value" => isset( $comment ) ? $comment->getPublished() : null
				                ]
			                ] + $this->fields;
		}
	}
}