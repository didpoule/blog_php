<?php

namespace Blog\Forms;

use Blog\Entity\Comment;
use App\Services\Form;

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

	public function __construct( $admin, $action, $comment = null, $post = null ) {


		self::$name = "comment";
		self::$action = $action;
		self::$entity = Comment::class;
		self::$fields = [
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
			"postId"    => [
				"type"  => "hidden",
				"value" => isset( $post ) ?  $post->getId()  : null
			],
		];

		if ( $admin ) {
			self::$fields = [
				                "published" => [
					                "type"  => "boolean",
					                "label" => "Publié",
					                "value" => isset( $comment ) ? $comment->getPublished() : null
				                ]
			                ] + self::$fields;
		}
	}
}